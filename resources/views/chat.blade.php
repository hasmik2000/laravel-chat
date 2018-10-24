@extends('layouts.app')
@section('content')
    <h1 class="text-center">Chat Application</h1>
    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col-md-7 m-auto">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <message
                                    :messages="{{ json_encode($messages) }}"
                                    :from="{{ Auth::user()->id }}"
                                    :to="{{ $id }}"
                                    color=color[index]"
                                    :user="{{ Auth::user() }}">
                            </message>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection