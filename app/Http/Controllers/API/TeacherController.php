<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Entity;
use App\Models\Teacher;
use App\Http\Resources\Period as PeriodResource;
use App\Http\Resources\Teacher as TeacherResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TeacherController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $students = Teacher::all();

        return $this->sendResponse(TeacherResource::collection($students), 'Teachers retrieved successfully.');
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if (!$validator->fails()) {

            $userInput = $request->except('username');
            $input['password'] = $userInput['password'] = bcrypt($userInput['password']);

            $user = User::create($userInput);

            if ($user->id) {

                $teacher = Teacher::create($input);

                if ($teacher->id) {

                    $entity = new Entity;
                    $entity->user_id = $user->id;
                    $entity->model_type = Teacher::class;
                    $entity->model_id = $teacher->id;

                    if ($entity->save()) {
                        return $this->sendResponse(new TeacherResource($teacher), 'Teacher created successfully.', 201);
                    }
                }
            }
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function show(Teacher $teacher): JsonResponse
    {
        if (is_null($teacher)) {
            return $this->sendError('Teacher not found.');
        }

        return $this->sendResponse(new TeacherResource($teacher), 'Teacher retrieved successfully.');
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
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if (!$validator->fails()) {

            $userInput = $request->except('username');
            $input['password'] = $userInput['password'] = bcrypt($userInput['password']);

            if ($teacher->entity->user->update($userInput)) {

                if ($teacher->update($input)) {
                    return $this->sendResponse(new TeacherResource($teacher), 'Teacher updated successfully.');
                }
            }
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Teacher $teacher
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Teacher $teacher): JsonResponse
    {
        $teacher->entity->user->delete();
        $teacher->delete();

        return $this->sendResponse(new TeacherResource($teacher), 'Teacher deleted successfully.', 204);
    }

    /**
     * @param integer $id
     * @return JsonResponse
     */
    public function periods(int $id): JsonResponse
    {
        $teacher = Teacher::find($id);

        if (is_null($teacher)) {
            return $this->sendError('Period not found.');
        }
        return $this->sendResponse(PeriodResource::collection($teacher->periods), 'Periods retrieved successfully.');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function students(int $id): JsonResponse
    {
        $teacher = Teacher::find($id);

        if (is_null($teacher)) {
            return $this->sendError('Students not found.');
        }

        $students = $teacher->periods->map(function ($period) {
            return $period->students;
        });
        return $this->sendResponse($students, 'Students retrieved successfully.');
    }
}
