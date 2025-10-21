<?php

namespace App\Repositories;

use App\Models\Products\{Product, Type, Subtype, Supplier, ProductCategories};
use Illuminate\Database\Eloquent\Model;
use App\Traits\DoubleValidation;
use App\Traits\ProductTraits;

abstract class ProductRepository implements RepositoryInterface
{
    use DoubleValidation, ProductTraits;
    // main model instance
    protected Model $model;

    /**
     * @param Model $model
     */
    public function __construct(
        Model $model,
        protected Type $type,
        protected Subtype $subtype,
        protected Supplier $supplier,
        protected ProductCategories $productCategory
    )
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     */
    public function create(array $data): Model
    {
        return $this->model->firstOrCreate($data);
    }

    /**
     * @param $id
     * @param array $data
     */
    public function update($id, array $data): bool
    {
        $query = $this->find($id);
        return $query ? $this->model->update($data) : false;
    }

    /**
     * @param $id
     */
    public function delete($id): bool
    {
        $query = $this->find($id);
        return $query ? $query->delete() : false;
    }

    /**
     * @param $id
     */
    public function find($id): ?Model
    {
        return $this->model
            ->with(['subtypes', 'types', 'supplier'])
            ->find($id);
    }

    /**
     * returns a query
     */
    public function all()
    {
        return $this->model->all();
    }
}
