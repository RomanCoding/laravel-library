@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{--<files :user="{{ auth()->user() }}" :extensions="{{ $extensions }}"></files>--}}

        <nav class="navbar navbar-expand-sm navbar-light routes my-0 py-0 px-0" style="display: block">
            <button class="navbar-toggler" type="button"
                    data-toggle="collapse" data-target="#routes"
                    aria-controls="routes" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav collapse navbar-collapse center-flex container-fluid" id="routes">
                <router-link class="nav-item nav-link" to="/home">Home</router-link>
                <router-link class="nav-item nav-link" to="/toolkit">Toolkit</router-link>
                <router-link class="nav-item nav-link" to="/partners">Business Partners</router-link>
                <router-link class="nav-item nav-link" to="/webinars">Webinars</router-link>
                <router-link class="nav-item nav-link" to="/video">Video Toolkit</router-link>
                <router-link class="nav-item nav-link" to="/calendar">Calendar</router-link>
                <router-link class="nav-item nav-link" to="/network">Network</router-link>
            </ul>
        </nav>
        <router-view :user="{{ auth()->user() }}" :extensions="{{ $extensions }}"></router-view>
    </div>
@endsection
