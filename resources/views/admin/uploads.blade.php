@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <uploads extensions="{{ $extensions }}"></uploads>
    </div>
@endsection
