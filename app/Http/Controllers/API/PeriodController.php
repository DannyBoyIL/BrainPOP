<?php

namespace App\Http\Controllers\API;

use App\Models\Period;
use App\Http\Resources\Period as PeriodResource;
use App\Http\Resources\Student as StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PeriodController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $period = Period::all();

        return $this->sendResponse(PeriodResource::collection($period), 'Periods retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        dd(__CLASS__ . ' ' . __FUNCTION__);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if (!$validator->fails()) {

            $period = Period::create($request->all());

            if ($period->id) {
                return $this->sendResponse(new PeriodResource($period), 'Period created successfully.', 201);
            }
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param Period $period
     * @return JsonResponse
     */
    public function show(Period $period): JsonResponse
    {
        if (is_null($period)) {
            return $this->sendError('Period not found.');
        }

        return $this->sendResponse(new PeriodResource($period), 'Period retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id)
    {
        dd(__CLASS__ . ' ' . __FUNCTION__);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Period $period
     * @return JsonResponse
     */
    public function update(Request $request, Period $period): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if (!$validator->fails()) {

            if ($period->update($request->all())) {

                return $this->sendResponse(new PeriodResource($period), 'Period updated successfully.');
            }
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Period $period
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Period $period): JsonResponse
    {
        $period->delete();

        return $this->sendResponse(new PeriodResource($period), 'Period deleted successfully.', 204);
    }

    /**
     * @param integer $id
     * @return JsonResponse
     */
    public function students(int $id): JsonResponse
    {
        $period = Period::find($id);

        if (is_null($period)) {
            return $this->sendError('Period not found.');
        }
        return $this->sendResponse(StudentResource::collection($period->students), 'Students retrieved successfully.');
    }
}
