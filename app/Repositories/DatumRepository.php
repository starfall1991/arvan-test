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
        $userKey = $this->generateUserKey($id);
        $datum = $this->model->get($userKey);
        return $datum === false ? null : $datum;
    }

    private function generateUserKey(string $id): string
    {
        return "datum:$id";
    }

    public function add(string $id): void
    {
        $userKey = $this->generateUserKey($id);
        $this->model->set($userKey, "data saved");
    }
}
