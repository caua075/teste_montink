@extends('layouts.app')

@section('content')
<h1>Carrinho</h1>

@if(count($carrinho) === 0)
    <p>Seu carrinho está vazio.</p>
    <a href="{{ route('produtos.index') }}" class="btn btn-primary">Voltar aos produtos</a>
@else
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Preço Unitário (R$)</th>
            <th>Quantidade</th>
            <th>Subtotal (R$)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($carrinho as $item)
        <tr>
            <td>{{ $item['nome'] }}</td>
            <td>{{ number_format($item['preco'], 2, ',', '.') }}</td>
            <td>{{ $item['quantidade'] }}</td>
            <td>{{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<form method="GET" action="" class="d-flex mb-3 col-md-4" style="gap: 0.5rem;">
    <div class="input-group input-group">
        <input type="text" name="codigo" id="cupom" class="form-control" placeholder="Cupom de desconto">
        <button type="submit" class="btn btn-outline-primary">Aplicar</button>
    </div>
</form>

<form action="{{ route('produtos.finalizarPedido') }}" method="POST">
    @csrf

    <h3 class="mt-4">Endereço de Entrega</h3>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" id="cep" name="cep" class="form-control" placeholder="Digite o CEP" maxlength="9" required />
        </div>
        <div class="col-md-8 mb-3">
            <label class="form-label">Endereço</label>
            <input type="text" id="endereco" class="form-control" readonly />
        </div>
    </div>

    <!-- Hidden inputs para os dados do ViaCEP -->
    <input type="hidden" name="logradouro_hidden" id="logradouro_hidden">
    <input type="hidden" name="bairro_hidden" id="bairro_hidden">
    <input type="hidden" name="localidade_hidden" id="localidade_hidden">
    <input type="hidden" name="uf_hidden" id="uf_hidden">

    <hr/>

    <div class="mb-3">
        <strong>Subtotal: </strong> R$ {{ number_format($subtotal, 2, ',', '.') }}<br/>
        <strong>Frete: </strong> R$ {{ number_format($frete, 2, ',', '.') }}<br/>
        <strong>Total: </strong> R$ {{ number_format($total, 2, ',', '.') }}
    </div>

    <button type="submit" class="btn btn-success mt-3">Finalizar Pedido</button>
    <a href="{{ route('produtos.index') }}" class="btn btn-secondary mt-3">Continuar Comprando</a>
</form>
<div id="erro-cep" class="text-danger mb-3" style="display: none;">CEP inválido ou não encontrado.</div>

@endif
@endsection

@section('scripts')
<script>
document.getElementById('cep').addEventListener('blur', function () {
    let cep = this.value;

    fetch('{{ route("consultar.cep") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ cep: cep })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const d = data.data;
            document.getElementById('endereco').value = `${d.logradouro}, ${d.bairro}, ${d.localidade} - ${d.uf}`;
            document.getElementById('logradouro_hidden').value = d.logradouro;
            document.getElementById('bairro_hidden').value = d.bairro;
            document.getElementById('localidade_hidden').value = d.localidade;
            document.getElementById('uf_hidden').value = d.uf;
        } else {
            alert('CEP não encontrado.');
        }
    })
    .catch(() => {
        alert('Erro ao buscar o CEP.');
    });
});
</script>
@endsection