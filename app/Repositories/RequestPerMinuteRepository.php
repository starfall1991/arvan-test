<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class RequestPerMinuteRepository
{
    protected $model;
    protected int $timeFrame = 60;

    public function __construct()
    {
        $this->model = Redis::client();
    }

    public function get(int $userId): int
    {
        $userKey = 'user:rps:'.$userId;
        $this->model->multi();

        $this->model->zremrangebyscore($userKey, 0, time() - $this->timeFrame);
        $this->model->zcard($userKey);
        $response = $this->model->exec();

        return end($response);
    }

    public function add(mixed $userId): void
    {
        $userKey = 'user:rps:'.$userId;
        $currentTime = time();

        $this->model->multi();
        $this->model->zadd($userKey, $currentTime, $currentTime);
        $this->model->expire($userKey, $this->timeFrame + 1);
        $this->model->exec();
    }
}
