@extends('layouts.app')

@section('title', 'SuperAdmin Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-crown text-warning"></i> SuperAdmin Dashboard
        </h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Usuários</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regular Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Usuários Comuns</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersByRole['user'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admins Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Administradores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersByRole['admin'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SuperAdmins Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Super Admins</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersByRole['superadmin'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-crown fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Recent Users -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Usuários Recentes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Função</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $recentUser)
                                <tr>
                                    <td>{{ $recentUser->name }}</td>
                                    <td>{{ $recentUser->email }}</td>
                                    <td>
                                        @if($recentUser->role === 'superadmin')
                                            <span class="badge badge-warning">Super Admin</span>
                                        @elseif($recentUser->role === 'admin')
                                            <span class="badge badge-info">Admin</span>
                                        @else
                                            <span class="badge badge-secondary">Usuário</span>
                                        @endif
                                    </td>
                                    <td>{{ $recentUser->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ações Rápidas</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('superadmin.users') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-users"></i> Gerenciar Todos os Usuários
                    </a>
                    <a href="{{ route('superadmin.settings') }}" class="btn btn-secondary btn-block mb-2">
                        <i class="fas fa-cog"></i> Configurações do Sistema
                    </a>
                    <hr>
                    <div class="text-center">
                        <p class="mb-2"><strong>Bem-vindo, {{ $user->name }}!</strong></p>
                        <p class="text-muted small">Você tem acesso total ao sistema.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
