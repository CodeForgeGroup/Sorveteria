<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Contato;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FuncionarioController extends Controller
{

    public $funcionario;


    public function __construct(Funcionario $funcionario)
    {
        $this->funcionario = $funcionario;

        // Configura o locale para português do Brasil em todo o controlador
        Carbon::setLocale('pt_BR');

        // Timezone de Brasília
        date_default_timezone_set('America/Sao_Paulo');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //recuperando o id do funcionario da sessão
        $id = session('id');

        // recuperando os dados do funcionário autenticado
        $funcionarioAutenticado = Funcionario::find($id);

        // Quantidade de Funcionários
        $totalFuncionarios = Funcionario::count();

        // Quantidade Salário
        $totalSalario = Funcionario::sum('salarioFuncionario');

        // Salário Médio dos Funcionários
        $salarioMedio = Funcionario::avg('salarioFuncionario');

        $naoLidas = Contato::where('lidoContato', 0)->count();

        // retornando os funcionários, juntando a tabela funcionários e usuários, obtendo todos os campos da tabela funcionario e o campo email da tabela usuários
        $funcionarios = Funcionario::join('usuarios', 'funcionarios.id', '=', 'usuarios.tipo_usuario_id')
        ->select('funcionarios.*', 'usuarios.email')
        ->get();

        //dd($funcionarios);

        // $funcionario = Funcionario::find(2);  ID do funcionário desejado

        return view('dashboard.administrador.funcionario', compact(
            'funcionarioAutenticado',
            'funcionarios',
            'totalFuncionarios',
            'totalSalario',
            'salarioMedio',
            'naoLidas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //recuperando o id do funcionario da sessão
        $id = session('id');

        // recuperando os dados do funcionário autenticado
        $funcionarioAutenticado = Funcionario::find($id);

        // Quantidade de Funcionários
        $totalFuncionarios = Funcionario::count();

        // Quantidade Salário
        $totalSalario = Funcionario::sum('salarioFuncionario');

        // Salário Médio dos Funcionários
        $salarioMedio = Funcionario::avg('salarioFuncionario');

        $naoLidas = Contato::where('lidoContato', 0)->count();

        // retornando os funcionários, juntando a tabela funcionários e usuários, obtendo todos os campos da tabela funcionario e o campo email da tabela usuários
        $funcionarios = Funcionario::join('usuarios', 'funcionarios.id', '=', 'usuarios.tipo_usuario_id')
                            ->select('funcionarios.*', 'usuarios.email')
                            ->get();

        //dd($funcionarios);

        // $funcionario = Funcionario::find(2);  ID do funcionário desejado

        return view('dashboard.administrador.funcionario.create', compact(
            'funcionarioAutenticado',
            'funcionarios',
            'totalFuncionarios',
            'totalSalario',
            'salarioMedio',
            'naoLidas'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'criado_em' => now(),
            'atualizado_em' => now()
        ]);

        $request->validate([
            'nomeFuncionario'           => 'required|string|max:255',
            'sobrenomeFuncionario'      => 'required|string|max:255',
            'email'                     => 'required|email|max:255',
            'foneFuncionario'           => 'required|string|max:20',
            'dataNascimentoFuncionario' => 'required|date',
            'enderecoFuncionario'       => 'required|string|max:255',
            'cidadeFuncionario'         => 'required|string|max:100',
            'estadoFuncionario'         => 'required|string|max:50',
            'cepFuncionario'            => 'required|string|max:10',
            'dataContratacaoFuncionario'=> 'required|date',
            'cargoFuncionario'          => 'required|string|max:100',
            'salarioFuncionario'        => 'required|numeric',
            'tipo_funcionario'          => 'required|in:administrador,assistente',
            'statusFuncionario'         => 'required|in:ativo,inativo',
            'fotoFuncionario'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Criando um novo funcionário
        $funcionario = new Funcionario();

        $funcionario->nomeFuncionario               = $request->input('nomeFuncionario');
        $funcionario->sobrenomeFuncionario          = $request->input('sobrenomeFuncionario');
        $funcionario->foneFuncionario               = $request->input('foneFuncionario');
        $funcionario->dataNascFuncionario           = $request->input('dataNascimentoFuncionario');
        $funcionario->enderecoFuncionario           = $request->input('enderecoFuncionario');
        $funcionario->cidadeFuncionario             = $request->input('cidadeFuncionario');
        $funcionario->estadoFuncionario             = $request->input('estadoFuncionario');
        $funcionario->cepFuncionario                = $request->input('cepFuncionario');
        $funcionario->dataContratacaoFuncionario    = $request->input('dataContratacaoFuncionario');
        $funcionario->cargoFuncionario              = $request->input('cargoFuncionario');
        $funcionario->salarioFuncionario            = $request->input('salarioFuncionario');
        $funcionario->tipo_funcionario              = $request->input('tipo_funcionario');
        $funcionario->statusFuncionario             = $request->input('statusFuncionario');

        if ($request->hasFile('fotoFuncionario')) {

            $imagem = $request->file('fotoFuncionario');

            $nomeArquivo = time() . '.' . $imagem->getClientOriginalExtension();

            $caminhoDiretorio = 'img/funcionarios/';
            $imagem->storeAs($caminhoDiretorio, $nomeArquivo, 'public');


            $funcionario->fotoFuncionario = $nomeArquivo;
        }


        $funcionario->save();



        // Criando um novo usuário
        $usuario = new Usuario();

        $usuario->nome              = $request->input('nomeFuncionario');
        $usuario->email             = $request->input('email');
        $usuario->senha             = $request->input('senha');
        $usuario->tipo_usuario_type = $request->input('tipo_funcionario');
        $usuario->tipo_usuario_id   = $funcionario->id;
        $usuario->token_lembrete = Str::random(100);

        $usuario->save();


        Alert::success('Funcionário Cadastrado!', 'O funcionário foi cadastrado com sucesso.');

        return redirect()->route('funcionario.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Funcionario  $funcionario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //recuperando o id do funcionario da sessão
        $session_id = session('id');

        // recuperando os dados do funcionário autenticado
        $funcionarioAutenticado = Funcionario::find($session_id);

        // Quantidade de Funcionários
        $totalFuncionarios = Funcionario::count();

        // Despesa com Salários
        $totalSalario = Funcionario::sum('salarioFuncionario');

        // Salário Médio dos Funcionários
        $salarioMedio = Funcionario::avg('salarioFuncionario');

        $naoLidas = Contato::where('lidoContato', 0)->count();

        // retornando os funcionários, juntando a tabela funcionários e usuários, obtendo todos os campos da tabela funcionario e o campo email da tabela usuários
        $funcionarios = Funcionario::join('usuarios', 'funcionarios.id', '=', 'usuarios.tipo_usuario_id')
                            ->select('funcionarios.*', 'usuarios.email')
                            ->get();
       // dd($id);
        $funcionario = Funcionario::findOrfail($id);
        $usuario = Usuario::findOrfail($id);
       //dd($usuario, $funcionario);

       return view('dashboard.administrador.funcionario.show', compact(
        'funcionario',
        'usuario',
        'funcionarioAutenticado',
        'totalFuncionarios',
        'totalSalario',
        'salarioMedio',
        'naoLidas'

));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Funcionario  $funcionario
     * @return \Illuminate\Http\Response
     */
    public function edit(Funcionario $funcionario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Funcionario  $funcionario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->merge([
            'atualizado_em' => now()
        ]);



        $funcionario = Funcionario::findOrFail($id);
        $usuario = Usuario::where('tipo_usuario_id', $id)->firstOrFail(); // ajustar o relacionamento entre Funcionario e Usuario


        $funcionario->nomeFuncionario               = $request->input('nomeFuncionario');
        $funcionario->sobrenomeFuncionario          = $request->input('sobrenomeFuncionario');
        $funcionario->foneFuncionario               = $request->input('foneFuncionario');
        $funcionario->dataNascFuncionario           = $request->input('dataNascimentoFuncionario');
        $funcionario->enderecoFuncionario           = $request->input('enderecoFuncionario');
        $funcionario->cidadeFuncionario             = $request->input('cidadeFuncionario');
        $funcionario->estadoFuncionario             = $request->input('estadoFuncionario');
        $funcionario->cepFuncionario                = $request->input('cepFuncionario');
        $funcionario->dataContratacaoFuncionario    = $request->input('dataContratacaoFuncionario');
        $funcionario->cargoFuncionario              = $request->input('cargoFuncionario');
        $funcionario->salarioFuncionario            = $request->input('salarioFuncionario');
        $funcionario->tipo_funcionario              = $request->input('tipo_funcionario');
        $funcionario->statusFuncionario             = $request->input('statusFuncionario');

        if ($request->hasFile('fotoFuncionario')) {

            $imagem = $request->file('fotoFuncionario');

            $nomeArquivo = time() . '.' . $imagem->getClientOriginalExtension();

            $caminhoDiretorio = 'img/funcionarios/';
            $imagem->storeAs($caminhoDiretorio, $nomeArquivo, 'public');

            if ($funcionario->fotoFuncionario) {

                $caminhoFotoAnterior = 'img/funcionarios/' . $funcionario->fotoFuncionario;

                Storage::disk('public')->delete($caminhoFotoAnterior);
            }


            $funcionario->fotoFuncionario = $nomeArquivo;
        }

        $funcionario->save();


        $usuario->nome              = $request->input('nomeFuncionario');
        $usuario->email             = $request->input('email');
        $usuario->senha             = $request->input('senha');
        $usuario->tipo_usuario_type = $request->input('tipo_funcionario');
        $usuario->tipo_usuario_id   = $funcionario->id;


        $usuario->save();


        Alert::success('Funcionario Atualizado!', 'Foi atualizado com sucesso.');

        return redirect()->route('funcionario.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Funcionario  $funcionario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Funcionario $funcionario)
    {
        //
    }
}
