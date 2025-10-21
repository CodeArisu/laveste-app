<?php

namespace App\Traits;

trait QueryHelper
{
    //

    public function queryAll()
    {
        

        $this->model->select();
    }
}
