<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    //Cria as regras para que os parametros enviados sejam validos
    //Required: precisa ser informado, unique na tabela marcas: nao pode ta igual
    //Min:3 -> minimo de caracteres

    public function rules()
    {
        $id = $this->id ?? 'NULL';

        return [
            'nome' => "required|unique:marcas,nome,{$id}|min:3",
            'imagem' => 'required|file|mimes:png'
        ];
    }

    //Retorna as mensagens se nao foi informado e se nao é unico
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute e obrigatorio',
            'nome.unique' => 'O nome da marca ja existe',
            'nome.min' => 'O nome deve ter no minino 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo png'
        ];
    }

    public function modelos(){
        //Uma marca possui muitos modelos
        return $this->hasMany('App\Models\Modelo');
    }
}
