<?php

namespace App\Http\Controllers;

//Para deletar utilizando o storage
use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    protected $marca;

    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = $this->marca->with('modelos')->get(); //Pega todas as informações; Sem instanciar o objeto; Apenas usando um metódo estático
        return response()->json($marcas,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Valida regras e retorna feedback
        $request->validate($this->marca->rules(), $this->marca->feedback());
        
        //Pegamos a imagem do request e armazenamos no filesystem em imagens
        $imagem = $request->file('imagem');

        //primeiro parametro que recebe e o diretorio onde fica, segundo se é local
        $imagem_urn = $imagem->store('imagens', 'public');
        
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id) // O proprio laravel instancia o marca
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === NULL) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        }

        return response()->json($marca, 200); //O framework já dá um by
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if($marca === NULL) {
            return response()->json(['erro' => 'Impossivel realizar a atualizacao. O recurso solicitado nao existe'], 404);
        }

        if($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            //Percorrendo todas as regras definidas no Model
            foreach($marca->rules() as $input => $regra) {

                //Coletar apenas as regras aplicáveis aos parâmetros parciais da requisição
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            
            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        //remove o arquivo antigo caso um novo arquivo esteja no request
        if($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem'); //Pega a imagem do request
        $imagem_urn = $imagem->store('imagens', 'public'); //Retorna o link da imagem
        
        
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if($marca === NULL) {
            return response()->json(['erro' => 'Impossivel realizar a exclusao. O recurso solicitado nao existe.'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);
        
        $marca->delete(); //Deleta a informação no banco
        return response()->json(['msg' => "A marca foi removida com sucesso!"], 200);
    }
}
