<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\AuthTraits;

use App\Models\Auth\User;

abstract class UserRepository implements RepositoryInterface
{
    // global trait
    use AuthTraits;

    // model instance
    protected User $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update($id, array $data): bool
    {
        $query = $this->find($id);
        return $query ? $query->update($data) : false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        $query = $this->find($id);
        return $query ? $query->delete() : false;
    }

    /**
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }
}
