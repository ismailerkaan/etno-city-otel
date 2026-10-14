@extends('admin.layouts.app')
@section('title', 'Yeni Oda Tipi')
@section('content')
    <h2 class="mb-2">Yeni Oda Tipi</h2>
    <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data">@csrf @include('admin.rooms._form')</form>
@endsection
