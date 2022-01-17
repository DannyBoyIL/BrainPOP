<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Entity;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $isTeacher = !($request->role === 'student');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role' => ['required', 'regex:/^(teacher|student)$/i'],
            'grade' => 'required_if:role,student'
        ]);

        if (!$validator->fails()) {

            $input = $request->except('username', 'role', 'grade');
            $role = $request->except('c_password', 'role', 'grade', 'email');
            $additional = !$isTeacher
                ? $request->only('grade')
                : $request->only('email');
            $role = array_merge($role, $additional);
            $role['password'] = $input['password'] = bcrypt($input['password']);

            $user = User::create($input);

            if ($user->id) {

                $input = !$isTeacher
                    ? Student::create($role)
                    : Teacher::create($role);

                if ($input->id) {

                    $entity = new Entity;
                    $entity->user_id = $user->id;
                    $entity->model_type = ($isTeacher ? Teacher::class : Student::class);
                    $entity->model_id = $input->id;

                    if ($entity->save()) {

                        $success['token'] = $user->createToken('BrainPOP Password Grant Client')->accessToken;
                        $success['name'] = $user->name;

                        return $this->sendResponse($success, 'User register successfully.', 201);
                    }
                }
            }
        }
        return $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Login api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('BrainPOP Password Grant Client')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Logout api
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
        }
        return $this->sendResponse([], 'User logout successfully.' );
    }
}
