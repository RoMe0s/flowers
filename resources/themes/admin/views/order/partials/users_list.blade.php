<select name="user_id" class="form-control select2 input-sm admin-order-user" data-id="{!! $model->id !!}">
    <option value="-1">{!! trans('labels.client') !!}</option>
    @foreach($users as $user)
        <option data-name="{!! $user->name !!}"
                data-email="{!! $user->email !!}"
                data-phone="{!! $user->phone !!}"
                data-discount="{!! $user->getDiscount() !!}"
                value="{!! $user->id !!}"
                @if($model->user_id == $user->id) selected="true" @endif>
            {!! $user->name !!}, {!! $user->phone !!}
        </option>
    @endforeach
</select>