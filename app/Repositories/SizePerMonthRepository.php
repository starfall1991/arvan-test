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
        $userKey = $this->createUserKey($userId);
        return (int)$this->model->get($userKey);
    }

    private function createUserKey(int $userId): string
    {
        $month = now()->format("m");
        return "user:uploads:$userId:$month";
    }

    public function add(mixed $userId, int $size): void
    {
        $userKey = $this->createUserKey($userId);
        $this->model->incrby($userKey, $size);

        $ttl = Carbon::now()->diffInSeconds(now()->endOfMonth());
        $this->model->expire($userKey, $ttl);
    }
}
