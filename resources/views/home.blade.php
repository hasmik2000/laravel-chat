@extends('layouts.app')

@section('content')
    <div class="container collapse" id="collapseUsers">
        <div class="card">
            <div class="card-header">
                All Users
            </div>
            <div class="card-body">
                @foreach($users as $user)
                    <a href="/messages/user/{{ $user->id }}">
                        <div class="user mb-4 p-2">
                            <div class="row">
                                <div class="col-auto">
                                    <img src="{{ asset('images/avatar.png') }}" alt="avatar img" class="img-fluid rounded-circle">
                                </div>
                                <div class="col-6 m-3">
                                    {{ $user->name }}
                                </div>
                                {{--@foreach($user->messages as $message)--}}
                                    <div class="col-auto offset-2">
                                        <span class="badge badge-pill badge-danger"></span>
                                    </div>
                                {{--@endforeach--}}

                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
