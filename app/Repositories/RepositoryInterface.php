<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface RepositoryInterface
{
    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param Model $model
     * @param array $data
     * @return bool
     */
    public function update($id, array $data): bool;

    /**
     * @param Model $model
     * @return bool
     */
    public function delete($id): bool;

    /**
     * @param any $id
     * @return Model|null
     */
    public function find($id): ?Model;

    /**
     * @return Query
     */
    public function all();
}
