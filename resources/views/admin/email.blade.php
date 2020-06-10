@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <email-settings :email-settings="{{ $extensions }}"></email-settings>
    </div>
@endsection
