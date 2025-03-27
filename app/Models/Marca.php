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
        return [
            'nome' => 'required|unique:marcas|min:3',
            'imagem' => 'required'
        ];
    }

    //Retorna as mensagens se nao foi informado e se nao é unico
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute e obrigatorio',
            'nome.unique' => 'O nome da marca ja existe',
            'nome.min' => 'O nome deve ter no minino 3 caracteres'
        ];
    }
}
