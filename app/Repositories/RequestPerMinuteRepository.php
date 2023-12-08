<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class RequestPerMinuteRepository
{
    protected $model;
    protected int $timeFrameInSeconds = 60;

    public function __construct()
    {
        $this->model = Redis::client();
    }

    public function get(int $userId): int
    {
        $userKey = $this->getUserKey($userId);

        $this->model->multi();
        $this->model->zremrangebyscore($userKey, 0, time() - $this->timeFrameInSeconds);
        $this->model->zcard($userKey);
        $response = $this->model->exec();

        return end($response);
    }

    private function getUserKey($userId): string
    {
        return 'user:rps:'.$userId;
    }

    public function add(mixed $userId): void
    {
        $userKey = $this->getUserKey($userId);
        $currentTime = time();

        $this->model->multi();
        $this->model->zadd($userKey, $currentTime, $currentTime);
        $this->model->expire($userKey, $this->timeFrameInSeconds + 1);
        $this->model->exec();
    }
}
