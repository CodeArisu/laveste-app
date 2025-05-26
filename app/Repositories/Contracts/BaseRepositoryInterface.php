<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{   
    // sets model to the interface
    public function all();

    public function find($id);

    public function create(array $data);

    public function push(array $data);

    public function update($id, array $data);

    public function delete($id);
}
