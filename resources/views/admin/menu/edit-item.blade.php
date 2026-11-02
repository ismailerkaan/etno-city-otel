@extends('admin.layouts.app')

@section('title', 'Ürünü Düzenle - '.$item->name)

@section('content')
    <div class="content-header row mb-2">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Ürünü Düzenle</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Yönetim Paneli</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.menu.index') }}">Menümüz</a></li>
                            <li class="breadcrumb-item active">{{ $item->name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">
                <i data-feather="arrow-left" class="me-50"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i data-feather="edit" class="me-50"></i> Menü Ürünü Detayları</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.menu.items.update', $item) }}">
                @csrf
                @method('PUT')

                @include('admin.menu._item-fields', ['prefix' => ''])

                <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="x" class="me-50"></i> İptal
                    </a>
                    <button class="btn btn-primary" type="submit">
                        <i data-feather="save" class="me-50"></i> Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
