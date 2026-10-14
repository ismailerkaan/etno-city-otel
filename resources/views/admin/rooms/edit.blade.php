@extends('admin.layouts.app')
@section('title', $roomType->name.' Düzenle')
@section('content')
    <div class="d-flex justify-content-between align-items-center gap-1 mb-2"><h2 class="mb-0">{{ $roomType->name }}</h2><form method="POST" action="{{ route('admin.rooms.destroy', $roomType) }}" onsubmit="return confirm('Bu oda tipini silmek istediğinize emin misiniz?')">@csrf @method('DELETE')<button class="btn btn-outline-danger" type="submit"><i data-feather="trash-2" class="me-50"></i>Sil</button></form></div>
    <form method="POST" action="{{ route('admin.rooms.update', $roomType) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.rooms._form')</form>
@endsection
