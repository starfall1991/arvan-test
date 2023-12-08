<?php

namespace App\Services;

use App\Repositories\DatumRepository;
use App\Repositories\RequestPerMinuteRepository;
use App\Repositories\SizePerMonthRepository;

class DatumService
{
    private array $user;

    public function __construct(
        protected RequestPerMinuteRepository $requestPerMinuteRepository,
        protected SizePerMonthRepository $sizePerMonthRepository,
        protected DatumRepository $datumRepository,
    ) {
        $this->user = request()->attributes->get('user');
    }

    public function get(string $id): ?string
    {
        return $this->datumRepository->get($id);
    }

    public function cannotStore(int $size): bool
    {
        return !$this->notExceededRequestPerMinute() or !$this->notExceededSizePerMonth($size);
    }

    private function notExceededRequestPerMinute(): bool
    {
        $userId = $this->user['id'];
        $requestPerMinute = $this->user['size_per_month'];

        return $this->requestPerMinuteRepository->get($userId) <= $requestPerMinute;
    }

    private function notExceededSizePerMonth(int $size): bool
    {
        $userId = $this->user['id'];
        $maxSize = $this->user['size_per_month'];

        return ($this->sizePerMonthRepository->get($userId) + $size) <= $maxSize;
    }

    public function store(string $id, int $size): string
    {
        // saving data ...

        $this->requestPerMinuteRepository->add($this->user['id']);
        $this->sizePerMonthRepository->add($this->user['id'], $size);
        $this->datumRepository->add($id);

        return "data saved";
    }
}
