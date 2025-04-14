<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasUniqueStringIds;

    protected string $prefix;

    protected function isValidUniqueId($value): bool
    {
        return true;
    }

    public function newUniqueId()
    {
        return _($this->prefix) . Str::ulid();
    }
}
