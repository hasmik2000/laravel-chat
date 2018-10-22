@extends('layouts.app')
@section('content')
    <h1 class="text-center">Chat Application</h1>
    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Chats</div>
                        <div class="panel-body">
                            <message
                                    :messages="{{ json_encode($messages) }}"
                                    :from="{{ Auth::user()->id }}"
                                    :to="{{ $id }}"
                                    color=color[index]">
                            </message>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection