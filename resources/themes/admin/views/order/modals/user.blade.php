
<table class="table">
    <thead>
    <tr>
        <th class="col-xs-3">Телефон</th>
        <th class="col-xs-4">Email</th>
        <th class="col-xs-4">Имя</th>
        <th class="col-xs-1"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
    <tr>
        <td>{!! $user->phone !!}</td>
        <td>{!! $user->email !!}</td>
        <td>{!! $user->name !!}</td>
        <td>
            <a class="btn btn-success btn-sm btn-flat add-user"
               data-user_id="{!! $user->id !!}"
               data-discount="{!! $user->discount !!}"
               data-name="{{ !empty($user->name) ? $user->name : (!empty($user->phone) ? $user->phone : (!empty($user->email) ? $user->email : $user->id)) }}"
               data-phone="{!! $user->phone !!}"
               data-real_name="{!! $user->name !!}"
            >Добавить</a>
        </td>
    </tr>
    @endforeach
    @if(!sizeof($users))
        <tr>
            <td colspan="4">
                <h4 class="text-center">
                    Ничего не нашлось
                </h4>
            </td>
        </tr>
    @endif
    </tbody>
</table>