<div class="box-body table-responsive no-padding col-sm-offset-1 col-sm-10">
    <table class="table table-hover duplication">
        <tbody>
        <tr>
            <th class="col-sm-8">{!! trans('labels.link') !!}<span class="required">*</span></th>
            <th class="col-sm-4">{!! trans('labels.delete') !!}</th>
        </tr>

        @if (count($model->result))
            @foreach($model->result as $key => $item)
                <tr class="duplication-row">
                    <td>
                        <div class="form-group required @if ($errors->has('images' .$key)) has-error @endif">
                            {!! Form::imageInput('images[' . $key .']', $item) !!}
                        </div>
                    </td>
                    <td class="coll-actions">
                        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $key !!}"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif


        @if (count(old('images')))
            @foreach(old('images') as $item_key => $item)
                @if ($item_key !== 'replaseme')
                    <tr class="duplication-row">
                        <td>
                            <div class="form-group required @if ($errors->has('images' .$item_key)) has-error @endif">
                                {!! Form::imageInput('images[' .$item_key. ']', $item) !!}
                            </div>
                        </td>
                        <td class="coll-actions">
                            <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        <tr class="duplication-button">
            <td colspan="6" class="text-center">
                <a title="@lang('labels.add_one_more')" class="btn btn-flat btn-primary btn-sm action create"><i class="glyphicon glyphicon-plus"></i></a>
            </td>
        </tr>

        <tr class="duplication-row duplicate">
            <td>
                <div class="form-group required">
                    {!! Form::imageInput(null, null, array('data-name' => 'images[replaseme]')) !!}
                </div>
            </td>
            <td class="coll-actions">
                <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
            </td>
        </tr>

        </tbody>
    </table>
</div>
<div class="clearfix"></div>