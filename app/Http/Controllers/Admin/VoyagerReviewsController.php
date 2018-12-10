<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;


class VoyagerReviewsController extends VoyagerBaseController
{

    public function update(Request $request, $id)
    {

        $slug = $this->getSlug($request);
        
        $all_request_data = $request->all();

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($all_request_data, $dataType->editRows, $dataType->name, $id);
        if( $manager_id = \Auth::id()){
            $managerReviewComments = \App\ManagerReviewComments::updateOrCreate(['review_id' => $id], [
                'manager_id' => $manager_id,
                'review_id' => $id,
                'comment' => $all_request_data['comment_manager']
            ]);
            
        }

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            event(new BreadDataUpdated($dataType, $data));

            return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                    'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
    }

}
