@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cog"></i> Configurações do Sistema
        </h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- LDAP Configuration Card -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Configurações LDAP</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        As configurações LDAP devem ser gerenciadas no arquivo <code>.env</code> do sistema.
                    </div>
                    
                    <h5>Configurações Atuais</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Servidor LDAP</th>
                            <td>{{ config('ldap.hosts.0') }}</td>
                        </tr>
                        <tr>
                            <th>Porta</th>
                            <td>{{ config('ldap.port') }}</td>
                        </tr>
                        <tr>
                            <th>Base DN</th>
                            <td>{{ config('ldap.base_dn') }}</td>
                        </tr>
                        <tr>
                            <th>SSL Habilitado</th>
                            <td>{{ config('ldap.use_ssl') ? 'Sim' : 'Não' }}</td>
                        </tr>
                        <tr>
                            <th>TLS Habilitado</th>
                            <td>{{ config('ldap.use_tls') ? 'Sim' : 'Não' }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Mapeamento de Roles</h5>
                    <p class="text-muted">Grupos LDAP são mapeados automaticamente para as seguintes funções:</p>
                    <ul>
                        @foreach(config('ldap.role_mapping') as $group => $role)
                        <li><code>{{ $group }}</code> → <strong>{{ ucfirst($role) }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- System Info Card -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Sistema</h6>
                </div>
                <div class="card-body">
                    <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
                    <p><strong>PHP Version:</strong> {{ phpversion() }}</p>
                    <p><strong>Environment:</strong> {{ app()->environment() }}</p>
                    <p class="mb-0"><strong>Debug Mode:</strong> {{ config('app.debug') ? 'Habilitado' : 'Desabilitado' }}</p>
                </div>
            </div>
        </div>

        <!-- Database Info Card -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Banco de Dados</h6>
                </div>
                <div class="card-body">
                    <p><strong>Driver:</strong> {{ config('database.default') }}</p>
                    <p><strong>Host:</strong> {{ config('database.connections.'.config('database.default').'.host') }}</p>
                    <p><strong>Database:</strong> {{ config('database.connections.'.config('database.default').'.database') }}</p>
                    <p class="mb-0"><strong>Port:</strong> {{ config('database.connections.'.config('database.default').'.port') }}</p>
                </div>
            </div>
        </div>

    </div>
@endsection
