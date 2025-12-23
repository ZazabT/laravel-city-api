<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Services\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CityController extends Controller
{
    protected CityService $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cities = $this->cityService->getAll();
        
        return CityResource::collection(array_values($cities))->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request): JsonResponse
    {
        $city = $this->cityService->create($request->validated());

        return (new CityResource($city))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $city = $this->cityService->find($id);

        if (!$city) {
            return response()->json(['message' => 'City not found'], Response::HTTP_NOT_FOUND);
        }

        return (new CityResource($city))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $city = $this->cityService->update($id, $data);

        if (!$city) {
            return response()->json(['message' => 'City not found'], Response::HTTP_NOT_FOUND);
        }

        return (new CityResource($city))->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->cityService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'City not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
