<?php

namespace App\Services;

use App\Repositories\DatumRepository;
use App\Repositories\RequestPerMinuteRepository;
use App\Repositories\SizePerMonthRepository;

class DatumService
{
    public function __construct(
        protected RequestPerMinuteRepository $requestPerMinuteRepository,
        protected SizePerMonthRepository $sizePerMonthRepository,
        protected DatumRepository $datumRepository,
    ) {
    }

    public function cannotStore(int $size): bool
    {
        return !$this->notExceededRequestPerMinute() or !$this->notExceededSizePerMonth($size);
    }

    private function notExceededRequestPerMinute(): bool
    {
        $user = request()->attributes->get('user');

        return $this->requestPerMinuteRepository->get($user['id']) <= $user['request_per_minute'];
    }

    public function get(string $id): ?string
    {
        return $this->datumRepository->get($id);
    }

    private function notExceededSizePerMonth(int $size): bool
    {
        $user = request()->attributes->get('user');
        $userId = $user['id'];
        $maxSize = $user['size_per_month'];

        return ($this->sizePerMonthRepository->get($userId) + $size) <= $maxSize;
    }

    public function store(string $id, int $size): string
    {
        // saving data ...

        $user = request()->attributes->get('user');
        $this->requestPerMinuteRepository->add($user['id']);
        $this->sizePerMonthRepository->add($user['id'], $size);
        $this->datumRepository->add($id);

        return "data saved";
    }
}
