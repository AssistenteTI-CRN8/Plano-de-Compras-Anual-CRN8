@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Welcome Card -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bem-vindo(a), {{ $user->name }}!</h6>
                </div>
                <div class="card-body">
                    <p>Você está logado como <strong>{{ ucfirst($user->role) }}</strong>.</p>
                    <p class="mb-0">Este é seu painel de controle pessoal.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- User Info Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Informações do Usuário</div>
                            <div class="mb-2 text-gray-800">
                                <strong>Nome:</strong> {{ $user->name }}<br>
                                <strong>Email:</strong> {{ $user->email }}<br>
                                <strong>Função:</strong> {{ ucfirst($user->role) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Acesso Rápido</div>
                            <div class="mb-0">
                                @if($user->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary mb-1">
                                        <i class="fas fa-user-shield"></i> Admin Dashboard
                                    </a><br>
                                @endif
                                @if($user->isSuperAdmin())
                                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-sm btn-warning mb-1">
                                        <i class="fas fa-crown"></i> SuperAdmin Dashboard
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-link fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
