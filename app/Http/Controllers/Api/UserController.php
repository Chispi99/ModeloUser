<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Return a paginated list of the first ten users.
     */
    public function index(): AnonymousResourceCollection
    {
        $users = User::orderBy('id')->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Create a new user in the database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        return response()->json(new UserResource($user), 201);
    }

    /**
     * Return user data for the given email and password (login).
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * Update the name (username) of the authenticated user.
     */
    public function updateName(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $this->findUserByCredentials($validated['email'], $validated['password']);

        $user->update(['name' => $validated['name']]);

        return response()->json(new UserResource($user));
    }

    /**
     * Update the email of a user identified by email and password.
     */
    public function updateEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'new_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ]);

        $user = $this->findUserByCredentials($validated['email'], $validated['password']);

        $user->update(['email' => $validated['new_email']]);

        return response()->json(new UserResource($user));
    }

    /**
     * Update the password of a user identified by email and password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8'],
        ]);

        $user = $this->findUserByCredentials($validated['email'], $validated['password']);

        $user->update(['password' => $validated['new_password']]);

        return response()->json(new UserResource($user));
    }

    /**
     * Delete a user identified by email and password.
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->findUserByCredentials($validated['email'], $validated['password']);

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

    /**
     * Find a user by email and verify the password, throwing a validation exception on failure.
     */
    private function findUserByCredentials(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user;
    }
}
