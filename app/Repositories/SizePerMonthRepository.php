<?php

namespace App\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

class SizePerMonthRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = Redis::client();
    }

    public function get(int $userId): int
    {
        $month = now()->format("m");
        $userKey = "user:uploads:$userId:$month";

        return (int)$this->model->get($userKey);
    }

    public function add(mixed $userId, int $size): void
    {
        $month = now()->format("m");
        $userKey = "user:uploads:$userId:$month";
        $this->model->incrby($userKey, $size);

        $ttl = Carbon::now()->diffInSeconds(now()->endOfMonth());
        $this->model->expire($userKey, $ttl);
    }
}
