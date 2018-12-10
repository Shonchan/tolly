@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.(!is_null($dataTypeContent->getKey()) ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.(!is_null($dataTypeContent->getKey()) ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <style type="text/css">.btn-feats{margin-right: 10px}</style>
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                          class="form-edit-add"
                          action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
                          method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                    @if(!is_null($dataTypeContent->getKey()))
                        {{ method_field("PUT") }}
                    @endif

                    <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <input type="hidden" name="var_feats[]" value="">
                        @foreach ($var_feats as $vf)
                            <input type="hidden" name="var_feats[]" value="{{ $vf }}">
                        @endforeach


                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{(!is_null($dataTypeContent->getKey()) ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                            <!-- GET THE DISPLAY OPTIONS -->
                            
                                @if ($loop->iteration == 4)
                                    <div class="form-group col-md-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><h5 class="panel-title">Варианты</h5></div>

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Наименование</th>
                                                        <th>Артикул</th>
                                                        <th>Цена</th>
                                                        <th>Старая цена</th>
                                                        <th>Количество</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (isset($variants) && count($variants)>0)
                                                        @foreach ($variants as $v)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="variants[id][]" value="{{ $v->id }}">
                                                                    <input type="hidden" name="variants[feats][]" value="{{ $v->feats }}">
                                                                    <input class="form-control" type="text" name="variants[name][]" value="{{ $v->name }}">
                                                                </td>
                                                                <td><input class="form-control" type="text" name="variants[sku][]" value="{{ $v->sku }}"></td>
                                                                <td><input class="form-control" type="text" name="variants[price][]" value="{{ $v->price }}"></td>
                                                                <td><input class="form-control" type="text" name="variants[compare_price][]" value="{{ $v->compare_price }}"></td>
                                                                <td><input class="form-control" type="text" name="variants[stock][]" value="{{ $v->stock }}"></td>
                                                                <td><div class='btn btn-warning btn-tb btn-feats'>...</div>
                                                                    @if (!$loop->first)
                                                                        <div class='btn btn-danger btn-tb btn-remV'>Удалить</div>
                                                                @endif</td>
                                                            </tr>
                                                        @endforeach

                                                    @else
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" name="variants[feats][]">
                                                                <input class="form-control" type="text" name="variants[name][]">
                                                            </td>
                                                            <td><input class="form-control" type="text" name="variants[sku][]"></td>
                                                            <td><input class="form-control" type="text" name="variants[price][]"></td>
                                                            <td><input class="form-control" type="text" name="variants[compare_price][]"></td>
                                                            <td><input class="form-control" type="text" name="variants[stock][]"></td>
                                                            <td><div class='btn btn-warning btn-tb btn-feats'>...</div></td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="btn btn-success" id="add-variant">Добавить вариант</div>
                                                        </td>
                                                        <td><button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>



                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><h5 class="panel-title">Свойства</h5></div>

                                            @if (isset($features) && count($features)>0)
                                                <style>
                                                    .mb-0{
                                                        margin-bottom: 0!important;
                                                    }
                                                    ul.list-opts{
                                                        list-style: none;
                                                        margin: 10px;
                                                    }
                                                    ul.list-opts > li {
                                                        margin-bottom: 5px;
                                                    }
                                                    .add-opt, .rem-opt{
                                                        margin-top: 0!important;
                                                    }
                                                    .btn-block{
                                                        background-color: #ffdada;
                                                    }
                                                    .btn-block.blocked{
                                                        color: white;
                                                        background-color: #860303;
                                                    }

                                                </style>
                                                <ul class="list-opts col-md-10">
                                                    @foreach ($features as $f)
                                                        @if(isset($p_options[$f->id]))
                                                            @foreach ($p_options[$f->id] as $o)
                                                                <li class=" row">

                                                                    <div class="col-md-4 mb-0">@if ($loop->first)<input class="form-control" type="text" value="{{ $f->name }}" disabled>@endif</div>
                                                                    <div class="col-md-6 mb-0"><input class="form-control" type="text" value="{{ $o }}" name="features[{{$f->id}}][]"></div>
                                                                    @if ($loop->first)
                                                                        <div class="col-md-1 mb-0"><button type="button" class="btn btn-success add-opt">+</button></div>
                                                                        <div class="col-md-1 mb-0"><button type="button" class="btn btn-block @if(in_array($f->id, $var_feats)){{"blocked"}}@endif">block</button></div>
                                                                    @endif
                                                                    @if (!$loop->first)
                                                                        <div class="col-md-1 mb-0"><button type="button" class="btn btn-danger rem-opt">-</button></div>
                                                                    @endif

                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li class="row">
                                                                <div class="col-md-4 mb-0"><input class="form-control" type="text" value="{{ $f->name }}" disabled></div>
                                                                <div class="col-md-6 mb-0"><input class="form-control" type="text" value="" name="features[{{$f->id}}][]" @if(in_array($f->id, $var_feats)){{"disabled"}}@endif></div>
                                                                <div class="col-md-1 mb-0"><button type="button" class="btn btn-success add-opt">+</button></div>
                                                                <div class="col-md-1 mb-0"><button type="button" class="btn btn-block @if(in_array($f->id, $var_feats)){{"blocked"}}@endif">block</button></div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                    <li class="row"><button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button></li>
                                                </ul>

                                            @endif
                                        </div>
                                    </div>
                                @endif
                            
                            
                            
                                @php
                                    $options = json_decode($row->details);
                                    $display_options = isset($options->display) ? $options->display : NULL;
                                @endphp
                                @if ($options && isset($options->legend) && isset($options->legend->text))
                                    <legend class="text-{{$options->legend->align or 'center'}}" style="background-color: {{$options->legend->bgcolor or '#f0f0f0'}};padding: 5px;">{{$options->legend->text}}</legend>
                                @endif
                                @if ($options && isset($options->formfields_custom))
                                    @include('voyager::formfields.custom.' . $options->formfields_custom)
                                @else
                                    <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width or 12 }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                        {{ $row->slugify }}
                                        <label for="name">{{ $row->display_name }}</label>
                                        @include('voyager::multilingual.input-hidden-bread-edit-add')
                                        @if($row->type == 'relationship')
                                            @include('voyager::formfields.relationship')
                                        @else
                                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                        @endif

                                        @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                          enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                               onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {}
        var $image

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.type != 'date' || elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
            $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', function (e) {
                e.preventDefault();
                $image = $(this).siblings('img');

                params = {
                    slug:   '{{ $dataType->slug }}',
                    image:  $image.data('image'),
                    id:     $image.data('id'),
                    field:  $image.parent().data('field-name'),
                    _token: '{{ csrf_token() }}'
                }

                $('.confirm_delete_name').text($image.data('image'));
                $('#confirm_delete_modal').modal('show');
            });

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $image.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing image.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
