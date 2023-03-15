<?php

namespace App\Repositories\Abstraction;

class ModelRepository
{
    public function model()
    {
        return get_class();
    }
}
