@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <files :user="{{ auth()->user() }}"></files>
    </div>
@endsection
