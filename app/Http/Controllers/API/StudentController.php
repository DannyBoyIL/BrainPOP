<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Entity;
use App\Models\Student;
use App\Models\StudentsPeriod;
use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\StudentPeriod as StudentPeriodResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class StudentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $students = Student::all();

        return $this->sendResponse(StudentResource::collection($students), 'Students retrieved successfully.');
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
            'grade' => 'required'
        ]);

        if (!$validator->fails()) {

            $userInput = $request->except('username', 'grade');
            $studentInput = $request->except('email');
            $studentInput['password'] = $userInput['password'] = bcrypt($userInput['password']);

            $user = User::create($userInput);

            if ($user->id) {

                $student = Student::create($studentInput);

                if ($student->id) {

                    $entity = new Entity;
                    $entity->user_id = $user->id;
                    $entity->model_type = Student::class;
                    $entity->model_id = $student->id;

                    if ($entity->save()) {
                        return $this->sendResponse(new StudentResource($student), 'Student created successfully.');
                    }
                }
            }
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return JsonResponse
     */
    public function show(Student $student): JsonResponse
    {
        if (is_null($student)) {
            return $this->sendError('Student not found.');
        }
        return $this->sendResponse(new StudentResource($student), 'Student retrieved successfully.', 201);
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
     * @param Student $student
     * @return JsonResponse
     */
    public function update(Request $request, Student $student): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'grade' => 'required'
        ]);

        if (!$validator->fails()) {

            $userInput = $request->except('username', 'grade');
            $studentInput = $request->except('email');
            $studentInput['password'] = $userInput['password'] = bcrypt($userInput['password']);

            if ($student->entity->user->update($userInput)) {

                if ($student->update($studentInput)) {
                    return $this->sendResponse(new StudentResource($student), 'Student updated successfully.');
                }
            }
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Student $student): JsonResponse
    {
        $student->entity->user->delete();
        $student->delete();

        return $this->sendResponse([new StudentResource($student)], 'Student deleted successfully.', 204);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateOrCreatePeriod(Request $request, int $id): JsonResponse
    {
        $student = Student::find($id);

        $input = $request->all();

        $validator = Validator::make($input, [
            'period_id' => 'required|array',
        ]);

        if (!$validator->fails()) {

            foreach ($input['period_id'] as $period) {
                StudentsPeriod::updateOrCreate([
                    'student_id' => $id,
                    'period_id' => $period,
                ]);
            }
            return $this->sendResponse(StudentPeriodResource::collection($student->periods), 'Student periods updated successfully.');
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deletePeriod(Request $request, int $id): JsonResponse
    {
        $student = Student::find($id);

        $input = $request->all();

        $validator = Validator::make($input, [
            'period_id' => 'required|array',
        ]);

        if (!$validator->fails()) {

            foreach ($input['period_id'] as $period) {
                StudentsPeriod::where('period_id', $period)->where('student_id', $id)->delete();
            }
            return $this->sendResponse(StudentPeriodResource::collection($student->periods), 'Student periods deleted successfully.', 204);
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }
}
