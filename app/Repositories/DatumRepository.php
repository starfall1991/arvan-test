<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class DatumRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = Redis::client();
    }

    public function get(string $id): ?string
    {
        $userKey = "datum:$id";
        $datum = $this->model->get($userKey);
        return $datum === false ? null : $datum;
    }

    public function add(string $id): void
    {
        $userKey = "datum:$id";
        $this->model->set($userKey, "data saved");
    }
}
