@extends('layouts.app')

@section('title', 'Todos os Usuários')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-crown text-warning"></i> Gerenciar Todos os Usuários
        </h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista Completa de Usuários</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Função</th>
                            <th>LDAP</th>
                            <th>Data de Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'superadmin')
                                    <span class="badge badge-warning">Super Admin</span>
                                @elseif($user->role === 'admin')
                                    <span class="badge badge-info">Administrador</span>
                                @else
                                    <span class="badge badge-secondary">Usuário</span>
                                @endif
                            </td>
                            <td>
                                @if($user->ldap_dn)
                                    <i class="fas fa-check text-success" title="Sincronizado com LDAP"></i>
                                @else
                                    <i class="fas fa-times text-danger" title="Não sincronizado"></i>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('superadmin.users.edit', $user->id) }}" 
                                   class="btn btn-sm btn-primary mb-1">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                
                                @if($user->id !== auth()->id())
                                <form action="{{ route('superadmin.users.delete', $user->id) }}" 
                                      method="POST" 
                                      style="display: inline-block;"
                                      onsubmit="return confirm('Tem certeza que deseja deletar este usuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1">
                                        <i class="fas fa-trash"></i> Deletar
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
