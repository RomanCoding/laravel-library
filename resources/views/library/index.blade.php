@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <files :user="{{ auth()->user() }}" :extensions="{{ $extensions }}"></files>
    </div>
@endsection
