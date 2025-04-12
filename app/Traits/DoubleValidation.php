<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait DoubleValidation
{
    protected function checkIfExists($model, $data, $column) : array
    {   
        $data = $model->where($column, $data)->first();

        // checks if the data specified existed
        if ($data->exists()) {   
            $data_name = array_keys($data);
            Log::alert("{$data_name} already exists");
            return [$data_name => $data, 'message' => "{$data_name} already exists"];
        }

        return $data;
    }

    protected function validateUpdateResults(array $updatedData) : void
    {
        foreach ($updatedData as $field => $success) {
            if (!$success) {
                Log::alert("{$field} already exists");
                throw new \RuntimeException("Failed to update {$field}");
            }
        }
    }
}