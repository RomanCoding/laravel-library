@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <users :data="{{ $users }}"></users>
    </div>
@endsection
