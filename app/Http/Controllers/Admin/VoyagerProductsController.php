<?php

namespace App\Http\Controllers\Admin;

use App\Option;
use App\Variant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;


class VoyagerProductsController extends VoyagerBaseController
{
    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $relationships = $this->getRelationships($dataType);

        $dataTypeContent = (strlen($dataType->model_name) != 0)
            ? app($dataType->model_name)->with($relationships)->findOrFail($id)
            : \DB::table($dataType->name)->where('id', $id)->first(); // If Model doest exist, get data from table name

        foreach ($dataType->editRows as $key => $row) {
            $details = json_decode($row->details);
            $dataType->editRows[$key]['col_width'] = isset($details->width) ? $details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        $variants = \DB::table('variants')->where('product_id', '=', $dataTypeContent->id)->get();
        foreach ($variants as &$v) {
            $vf = \DB::table('options')->where('variant_id', '=', $v->id)->pluck('value', 'feature_id')->toArray();
//            \Debugbar::info($vf);
            $v->feats = json_encode($vf);
        }

        $features = \DB::table('features')->where('category_id', '=', $dataTypeContent->categories[0]->id)->get();
        $opts = \DB::table('options as o')
            ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
            ->selectRaw('f.id as feature_id, o.value')
            ->whereIn('o.product_id', (array)$dataTypeContent->id)
//            ->where('o.variant_id', '=', 0)
            ->orderBy('f.position', 'asc')->get();
        $p_options = [];
        foreach ($opts as $o){
            if (isset($p_options[$o->feature_id])) {
                $p_options[ $o->feature_id ][] = $o->value;
            } else {
                $p_options[ $o->feature_id ] = [];
                $p_options[ $o->feature_id ][] = $o->value;
            }
        }

        $var_feats = \DB::table('variant_features')->where('product_id', '=', $dataTypeContent->id)->pluck('feature_id')->toArray();
//        dd($options);
        \Debugbar::info($var_feats);

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'variants', 'features', 'p_options', 'var_feats'));
    }

    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id);

        $vars = $request->variants;

        $variants = [];
        foreach ($vars as $k=>$v) {
            foreach ($v as $i=>$vv){
                $variants[$i][$k] = $vv;
            }
        }


        $vids = [];
        $features = $request->features;
        $var_feats = $request->var_feats;

        \DB::table('variant_features')->where('product_id', '=', $id)->delete();
        $vfa = [];
        foreach ($var_feats as $vf) {
            if(!empty($vf))
                $vfa[] = ['product_id'=>$id, 'feature_id'=>$vf];
        }
        \DB::table('variant_features')->insert($vfa);
//        \DB::table('options')->where('product_id', '=', $id)->whereIn('feature_id', $var_feats)->delete();

        $voids = [];
        foreach ($variants as &$v) {
            $vid = isset($v['id']) ? $v['id'] : '';
            $v['product_id'] = $id;
            if(trim($v['sku']) != '') {
                if(!isset($v['price']) || empty($v['price'])) {
                    $v['price'] = 0;
                }
                if(!isset($v['stock']) || empty($v['stock'])) {
                    $v['stock'] = 0;
                }
                $feats = json_decode($v['feats'], true);
                unset($v['feats']);
                $vid = Variant::updateOrCreate( [ 'sku' => $v[ 'sku' ], 'product_id' => $v[ 'product_id' ] ], $v );
                $vid->external_id = str_pad($vid->id, 7, "0", STR_PAD_LEFT);
                $vid->save();
                $vids[] = $vid->id;

                foreach ($feats as $fid=>$valu) {
                    if(in_array($fid, $var_feats)) {
                        $oid = Option::updateOrCreate( [
//                        'product_id'=> (int)$id,
                            'variant_id' => (int)$vid->id,
                            'feature_id' => (int)$fid,
                            'value' => $valu,
                        ], [
//                        'product_id'=> (int)$id,
                            'variant_id' => (int)$vid->id,
                            'feature_id' => (int)$fid,
                            'value' => $valu,
                        ] );
                        $voids[] = $oid->id;
                    }
                }

            }
        }

//        \Debugbar::info($features);
        \DB::table('options')->whereNotIn('id', $voids)->whereIn('variant_id', $vids)->delete();

        \DB::table('variants')->whereNotIn('id', $vids)->where('product_id', '=', $id)->delete();


        $oids = [];
        foreach ( $features as $fid => $vals ) {
            foreach ( $vals as $vl ) {
                if(trim($vl) != '') {
                    $oid = Option::updateOrCreate([
                        'product_id'=> (int)$id,
                        'feature_id'=>(int)$fid,
                        'value'=>$vl,
                    ], [
                        'product_id'=> (int)$id,
                        'feature_id'=>(int)$fid,
                        'value'=>$vl,
                        ]);
                    $oids[] = $oid->id;
                }
            }
        }

        \DB::table('options')->whereNotIn('id', $oids)->where('product_id', '=', $id)->delete();


        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            event(new BreadDataUpdated($dataType, $data));

            return redirect()
                ->route("voyager.{$dataType->slug}.edit", $id)
                ->with([
                    'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
    }

    //***************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function create(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0)
            ? new $dataType->model_name()
            : false;

        foreach ($dataType->addRows as $key => $row) {
            $details = json_decode($row->details);
            $dataType->addRows[$key]['col_width'] = isset($details->width) ? $details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    /**
     * POST BRE(A)D - Store data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        $vars = $request->variants;

        $variants = [];
        foreach ($vars as $k=>$v) {
            foreach ($v as $i=>$vv){
                $variants[$i][$k] = $vv;
            }
        }

        if (!$request->has('_validate')) {
            $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

            foreach ($variants as &$v) {
               // $vid = isset($v['id']) ? $v['id'] : '';
                $v['product_id'] = $data->id;
                if(!isset($v['price']) || empty($v['price'])) {
                    $v['price'] = 0;
                }
                if(!isset($v['stock']) || empty($v['stock'])) {
                    $v['stock'] = 0;
                }
                $vid = Variant::updateOrCreate(['sku'=>$v['sku'], 'product_id'=>$v['product_id']], $v);
                $vid->external_id = str_pad($vid->id, 7, "0", STR_PAD_LEFT);
                $vid->save();

            }


            event(new BreadDataAdded($dataType, $data));

            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => $data]);
            }

            return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                    'message'    => __('voyager::generic.successfully_added_new')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('delete', app($dataType->model_name));

        // Init array of IDs
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $id) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
            $this->cleanup($dataType, $data);
        }

        $displayName = count($ids) > 1 ? $dataType->display_name_plural : $dataType->display_name_singular;

        $res = $data->destroy($ids);
        $data = $res
            ? [
                'message'    => __('voyager::generic.successfully_deleted')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('voyager::generic.error_deleting')." {$displayName}",
                'alert-type' => 'error',
            ];

        if ($res) {
            event(new BreadDataDeleted($dataType, $data));
        }

        return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
    }

    public function variant_features(Request $request)
    {
//        if($request->has('id')) {
//            $variant = \DB::table('variants as v')->join('products_categories as pc', 'v.product_id', '=', 'pc.product_id')
//                ->select('v.id', 'v.feats', 'pc.category_id')->where('v.id', '=', $request->get('id'))->first();
//
//        }
        if($request->has('feats')) {
            $feats = json_decode($request->get('feats'), true);

        } else {
            $feats = [];
        }

        if($request->has('vfeats')) {
            $vf = [];
            foreach ( $request->get('vfeats') as $f ) {
                if(!empty($f['value']))
                    $vf[]=$f['value'];
            }
            $features = \DB::table('features')->whereIn('id', $vf)->get();
        } else {
            $features = [];
        }

//        \Debugbar::info($vf);


//        $features = \DB::table('features')->where('category_id', '=', $variant->category_id)->get();
        $view = \View::make('ajax.variant_features', compact(['feats', 'features']))->render();

        return response()->json($view);
    }
}
