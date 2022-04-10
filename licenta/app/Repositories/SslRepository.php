<?php

namespace App\Repositories;
use App\Models\SslCertificate;

class SslRepository
{

    public function __construct(SslCertificate $model)
    {
        $this->model = $model;
    }

    public function create($attributes)
    {   
        return $this->model->create($attributes);
    }
}