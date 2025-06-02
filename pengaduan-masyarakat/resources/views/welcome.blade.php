@extends('layouts.app')

@section('title', 'Pengaduan Masyarakat')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 mb-4">Selamat Datang di PengaduanKu</h1>
            <p class="lead mb-4">Platform pengaduan masyarakat yang transparan dan akuntabel</p>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg w-100">
                        <i class="fas fa-user-plus"></i> Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection