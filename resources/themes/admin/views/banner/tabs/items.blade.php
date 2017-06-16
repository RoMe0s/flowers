<div class="box-body table-responsive no-padding">
    <table class="table table-hover duplication">
        <tbody>
        <tr>
            <th>{!! trans('labels.name') !!}<span class="required">*</span></th>
            <th>@lang('labels.image')<span class="required">*</span></th>
            <th>Буллет</th>
            <th>{!! trans('labels.status') !!}<span class="required">*</span>&nbsp;&nbsp;/<br/>
            {!! trans('labels.position') !!}<span class="required">*</span></th>
            <th>{!! trans('labels.delete') !!}</th>
        </tr>

        @if (count($model->items))
            @foreach($model->items as $item)
                <tr class="duplication-row">
                    <td class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            @foreach (config('app.locales') as $key => $locale)
                                <li @if ($key == 0) class="active" @endif>
                                    <a aria-expanded="false" href="#tab_item_locale_old_{!! $locale !!}_{!! $item->id !!}" data-toggle="tab">
                                        <i class="flag flag-{!! $locale !!}"></i>
                                        @lang('labels.tab_'.$locale)
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach (config('app.locales') as $key => $locale)
                                <div class="tab-pane fade in @if ($key == 0) active @endif" id="tab_item_locale_old_{!! $locale !!}_{!! $item->id !!}">
                                    @include('views.banner.tabs.item_locale', ['id' => $item->id, 'key' => 'old'])
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div class="form-group required @if ($errors->has('items.old.' .$item->id. '.image')) has-error @endif">
                            {!! Form::imageInput('items[old][' .$item->id. '][image]', $item->image, ['required' => true]) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group @if ($errors->has('items.old.' .$item->id. '.bullet')) has-error @endif">
                            {!! Form::imageInput('items[old][' .$item->id. '][bullet]', $item->bullet) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group required @if ($errors->has('items.old.' .$item->id. '.status')) has-error @endif">
                            {!! Form::select('items[old][' .$item->id. '][status]', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], $item->status, ['id' => 'items.old.' .$item->id. '.position', 'class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
                        </div>
                        <div class="form-group required @if ($errors->has('items.old.' .$item->id. '.position')) has-error @endif">
                            {!! Form::text('items[old][' .$item->id. '][position]', $item->position, ['id' => 'items.old.' .$item->id. '.position', 'class' => 'form-control input-sm']) !!}
                        </div>
                    </td>
                    <td class="coll-actions">
                        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $item->id !!}" data-name="items[remove][]"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif

        @if (count(old('items.new')))
            @foreach(old('items.new') as $item_key => $item)
                @if ($item_key !== 'replaseme')
                    <tr class="duplication-row">
                        <td class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                @foreach (config('app.locales') as $key => $locale)
                                    <li @if ($key == 0) class="active" @endif>
                                        <a aria-expanded="false" href="#tab_item_locale_new_{!! $locale !!}_{!! $item_key !!}" data-toggle="tab">
                                            <i class="flag flag-{!! $locale !!}"></i>
                                            @lang('labels.tab_'.$locale)
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach (config('app.locales') as $key => $locale)
                                    <div class="tab-pane fade in @if ($key == 0) active @endif" id="tab_item_locale_new_{!! $locale !!}_{!! $item_key !!}">
                                        @include('views.banner.tabs.item_locale', ['id' => $item_key, 'key' => 'new'])
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('items.new.' .$item_key. '.image')) has-error @endif">
                                {!! Form::imageInput('items[new][' .$item_key. '][image]', $item['image'], ['required']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group @if ($errors->has('items.new.' .$item_key. '.bullet')) has-error @endif">
                                {!! Form::imageInput('items[new][' .$item_key. '][bullet]', $item['bullet']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('items.new.' .$item_key. '.status')) has-error @endif">
                                {!! Form::select('items[new][' .$item_key. '][status]', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], $item['status'], ['id' => 'items.new.' .$item_key. '.status', 'class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
                            </div>
                            <div class="form-group required @if ($errors->has('items.new.' .$item_key. '.position')) has-error @endif">
                                {!! Form::text('items[new][' .$item_key. '][position]', $item['position'], ['id' => 'items.new.' .$item_key. '.position', 'class' => 'form-control input-sm']) !!}
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
            <td class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    @foreach (config('app.locales') as $key => $locale)
                        <li @if ($key == 0) class="active" @endif>
                            <a aria-expanded="false" href="#tab_item_locale_new_{!! $locale !!}_replaseme" data-toggle="tab">
                                <i class="flag flag-{!! $locale !!}"></i>
                                @lang('labels.tab_'.$locale)
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach (config('app.locales') as $key => $locale)
                        <div class="tab-pane fade in @if ($key == 0) active @endif" id="tab_item_locale_new_{!! $locale !!}_replaseme">
                            <div class="form-group required">
                                <input data-name="items[new][replaseme][{!! $locale !!}][name]" placeholder="@lang('labels.name')" data-required="required" class="form-control input-sm">
                            </div>

                            {{--<div>--}}
                                {{--<textarea data-name="items[new][replaseme][{!! $locale !!}][content]" placeholder="@lang('labels.content')" class="form-control input-sm"></textarea>--}}
                            {{--</div>--}}
                        </div>
                    @endforeach
                </div>
            </td>
            <td>
                <div class="form-group required">
                    {!! Form::imageInput(null, null, ['data-required' => 'required', 'data-name' => 'items[new][replaseme][image]']) !!}
                </div>
            </td>
            <td>
                <div class="form-group">
                    {!! Form::imageInput(null, null, ['data-name' => 'items[new][replaseme][bullet]']) !!}
                </div>
            </td>
            <td>
                <div class="form-group required">
                    <select class="form-control select2 input-sm" data-required="required" aria-hidden="true" data-name="items[new][replaseme][status]">
                        <option selected="selected" value="1">@lang('labels.status_on')</option>
                        <option value="0">@lang('labels.status_off')</option>
                    </select>
                </div>
                <div class="form-group required">
                    <input data-name="items[new][replaseme][position]" value="0" data-required="required" class="form-control input-sm">
                </div>
            </td>
            <td class="coll-actions">
                <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
            </td>
        </tr>

        </tbody>
    </table>
</div>