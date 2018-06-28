@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <profile :auth="{{ auth()->user() }}"></profile>
    </div>
@endsection
