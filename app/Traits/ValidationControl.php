<?php

namespace App\Traits;

trait ValidationControl
{   
    protected $objectDto;

    public function setDto($objectDto)
    {
        $this->objectDto = $objectDto;
    }

    private function validatedDataRepository($validated, callable $exception)
    {   
        if (!$validated) {
            return $exception;
        }
        
        $data = $this->objectDto;
        return $data->toArray();
    }
}
