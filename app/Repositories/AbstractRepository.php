<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos)
    {
        $this->model = $this->model->with($atributos); //Guarda a query no atributo
        //A query está sendo montada
    }

    //Faz o filtro por produto
    public function filtro($filtros)
    {
        $filtros = explode(';', $filtros);

        foreach ($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
            //A query está sendo montada
        }
    }

    //Pega os atributos do request
    public function selectAtributos($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado()
    {
        return $this->model->get();
    }
}