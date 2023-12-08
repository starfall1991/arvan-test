<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatumRequest;
use App\Services\DatumService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DatumController extends Controller
{
    public function __construct(
        protected DatumService $datumService,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(DatumRequest $request): JsonResponse
    {
        $datum = $this->datumService->get($request->input('id'));
        if (is_null($datum)) {
            $datum = $this->datumService->store(
                id: $request->input('id'),
                size: $request->input('size'),
            );
        }

        return response()
            ->json(['datum' => $datum])
            ->setStatusCode(Response::HTTP_OK);
    }
}
