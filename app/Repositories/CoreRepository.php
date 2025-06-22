<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class CoreRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Метод повертає клас моделі
     */
    abstract protected function getModelClass();

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * Початкові умови для запитів
     *
     * @return Model
     */
    protected function startConditions()
    {
        return clone $this->model;
    }
}
