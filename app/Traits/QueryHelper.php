<?php

namespace App\Traits;

trait QueryHelper
{
    //

    public function queryAll($selectedCols = ['*'])
    {
        $table = $this->model->getTable();

        return $this->model->select($selectedCols)->from($table)->get();
    }
}
