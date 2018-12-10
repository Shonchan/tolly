<div class="message">
    <style type="text/css">.message{width:50%;}</style>
    <form id = "v_features">
        {{--<input type="hidden" name="id" value="{{ $variant->id }}">--}}
        <ul class="list-opts col-md-10">
            @foreach ($features as $f)

                        <li class=" row">

                            <div class="col-md-4 mb-0"><input class="form-control" type="text" value="{{ $f->name }}" disabled></div>
                            <div class="col-md-6 mb-0"><input class="form-control" type="text" value="@if(isset($feats[$f->id])){{ $feats[$f->id] }}@endif" name="{{$f->id}}"></div>

                        </li>


            @endforeach
            <li class="row"><button class="btn btn-primary save save-vfeats">{{ __('voyager::generic.save') }}</button></li>
        </ul>
    </form>
</div>