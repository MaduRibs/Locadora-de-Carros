<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules () {
        $id = $this->id ?? 'NULL';

        return [
            'marca_id' => 'exists:marcas,id',//Se existe na tabela marcas na coluna id
            'nome' => "required|unique:modelos,nome,{$id}|min:3",
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',//Digits: valor entre 1 e 5
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    //Relacionando uma tabela com outra
    public function marca() {
        //Um modelo pertence a uma marca
        return $this->belongsTo('App\Models\Marca');//Pertence a (belongs to)
    }
    
}
