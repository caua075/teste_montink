<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProdutoController extends Controller
{
    // Tela inicial: listar e cadastrar produtos
    public function index()
    {
        $produtos = Produto::with('estoque')->get();
        return view('produtos.index', compact('produtos'));
    }

    // Cadastrar novo produto ou atualizar existente
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'quantidade' => 'required|integer|min:0',
        ]);

        $produto = Produto::updateOrCreate(
            ['id' => $request->id],
            ['nome' => $request->nome, 'preco' => $request->preco]
        );

        Estoque::updateOrCreate(
            ['produto_id' => $produto->id],
            ['quantidade' => $request->quantidade]
        );

        return redirect()->route('produtos.index')->with('success', 'Produto salvo!');
    }

    public function editar($id)
    {
        $produto = Produto::with('estoque')->findOrFail($id);
        $produtos = Produto::with('estoque')->get();
    
        return view('produtos.index', compact('produto', 'produtos'));
    }

    // Adicionar item ao carrinho
    public function comprar(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);
        $estoque = $produto->estoque;

        if (!$estoque || $estoque->quantidade <= 0) {
            return back()->with('error', 'Produto sem estoque!');
        }

        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$id])) {
            $carrinho[$id]['quantidade']++;
        } else {
            $carrinho[$id] = [
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'quantidade' => 1
            ];
        }

        $estoque->decrement('quantidade');
        session()->put('carrinho', $carrinho);

        return redirect()->route('carrinho')->with('success', 'Produto adicionado ao carrinho!');
    }

    // Exibir carrinho com cálculo de frete
    public function carrinho()
    {
        $carrinho = session()->get('carrinho', []);
        $subtotal = 0;

        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        // Regras de frete
        if ($subtotal > 200) {
            $frete = 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } else {
            $frete = 20;
        }

        $total = $subtotal + $frete;

        return view('produtos.carrinho', compact('carrinho', 'subtotal', 'frete', 'total'));
    }

    public function consultarCep(Request $request)
    {
        $request->validate([
            'cep' => ['required', 'regex:/^\d{5}-?\d{3}$/']
        ]);

        $cep = str_replace('-', '', $request->cep);
        $url = "https://viacep.com.br/ws/{$cep}/json/";

        try {
            $response = Http::get($url);

            if ($response->successful() && !$response->json('erro')) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'CEP não encontrado.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao consultar o CEP.'], 500);
        }
    }

    public function finalizarPedido(Request $request)
    {
        $carrinho = session()->get('carrinho', []);
        if (empty($carrinho)) {
            return redirect()->route('produtos.index')->with('error', 'Carrinho vazio.');
        }
    
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
    
        // Regras de frete
        if ($subtotal > 200) {
            $frete = 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } else {
            $frete = 20;
        }
    
        $total = $subtotal + $frete;
    
        // Salvar pedido
        \App\Models\Pedido::create([
            'subtotal'   => $subtotal,
            'frete'      => $frete,
            'total'      => $total,
            'cep'        => $request->cep,
            'logradouro' => $request->logradouro_hidden,
            'bairro'     => $request->bairro_hidden,
            'localidade' => $request->localidade_hidden,
            'uf'         => $request->uf_hidden,
        ]);
    
        session()->forget('carrinho');
    
        return redirect()->route('produtos.index')->with('success', 'Pedido finalizado com sucesso!');
    }
}