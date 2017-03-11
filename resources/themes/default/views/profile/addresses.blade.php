@extends('profile.index')

@section('profile-content')
    @if(!sizeof($addresses))
        <p class="text-center">
            <i>Адресов нет</i>
        </p>
    @else
        <table class="table">
            @foreach($addresses as $address)
                <tr>
                    <td>{{ $address->address }}</td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection