@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <extensions :extensions="{{ $extensions }}"></extensions>
    </div>
@endsection
