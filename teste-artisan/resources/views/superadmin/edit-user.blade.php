@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Editar Usuário</h1>
        <a href="{{ route('superadmin.users') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dados do Usuário</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role">Função</label>
                            <select class="form-control @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role"
                                    required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                    Usuário
                                </option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                                <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>
                                    Super Administrador
                                </option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @if($user->ldap_dn)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>LDAP DN:</strong> {{ $user->ldap_dn }}<br>
                            Este usuário está sincronizado com LDAP.
                        </div>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações Adicionais</h6>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $user->id }}</p>
                    <p><strong>Criado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Atualizado em:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    <hr>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('superadmin.users.delete', $user->id) }}" 
                          method="POST"
                          onsubmit="return confirm('Tem certeza que deseja deletar este usuário? Esta ação não pode ser desfeita.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Deletar Usuário
                        </button>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Você não pode deletar seu próprio usuário.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
