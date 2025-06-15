@extends('layouts.app')

@section('content')
<h1>Produtos</h1>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-3">Cadastrar Produto</h2>

        <form action="{{ route('produtos.store') }}" method="POST" class="card card-body shadow-sm">
            @csrf
            <input type="hidden" name="id" value="{{ old('id') }}" />

            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required />
            </div>

            <div class="mb-3">
                <label for="preco" class="form-label">Preço (R$)</label>
                <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="{{ old('preco') }}" required />
            </div>

            <div class="mb-3">
                <label for="quantidade" class="form-label">Estoque</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" value="{{ old('quantidade') ?? 0 }}" required min="0" />
            </div>

            <button type="submit" class="btn btn-primary w-100">Salvar Produto</button>
        </form>
    </div>
</div>

<h2 class="mt-5 mb-3">Produtos cadastrados</h2>

<div class="table-responsive">
    <table class="table table-sm table-bordered align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>Preço (R$)</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produtos as $produto)
                <tr>
                    <td>{{ $produto->nome }}</td>
                    <td>{{ number_format($produto->preco, 2, ',', '.') }}</td>
                    <td>{{ $produto->estoque ? $produto->estoque->quantidade : 0 }}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <form action="{{ route('produtos.comprar', $produto->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm me-1"
                                    {{ ($produto->estoque && $produto->estoque->quantidade > 0) ? '' : 'disabled' }}>
                                    Comprar
                                </button>
                            </form>
                            <button class="btn btn-warning btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $produto->id }}">
                                Editar
                            </button>
                        </div>
                    </td>
                </tr>
                <div class="modal fade" id="modalEditar{{ $produto->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $produto->id }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form action="{{ route('produtos.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $produto->id }}" />
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalLabel{{ $produto->id }}">Editar Produto</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                              <label for="nome" class="form-label">Nome</label>
                              <input type="text" class="form-control" name="nome" value="{{ $produto->nome }}" required />
                          </div>
                          <div class="mb-3">
                              <label for="preco" class="form-label">Preço (R$)</label>
                              <input type="number" step="0.01" class="form-control" name="preco" value="{{ $produto->preco }}" required />
                          </div>
                          <div class="mb-3">
                              <label for="quantidade" class="form-label">Estoque</label>
                              <input type="number" class="form-control" name="quantidade" value="{{ $produto->estoque->quantidade ?? 0 }}" required />
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-success">Salvar Alterações</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
