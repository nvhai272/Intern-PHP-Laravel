<?php

namespace App\Services;

use App\Repositories\EmployeeRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class AuthService
{
    protected EmployeeRepository $employeeRepository;
    public const TABLE_LOGIN = 'm_employees';

    public function __construct(EmployeeRepository $authRepository)
    {
        $this->employeeRepository = $authRepository;
    }

    public function login(array $credentials): array
    {
        try {
            $user = $this->employeeRepository->findActiveEmployeeByEmail($credentials['email']);

            if (!$user || !Auth::attempt($credentials)) {
                Log::error("ERROR_AUTH_FAILED: Invalid credentials for email: {$credentials['email']}");
                return ['success' => false, 'message' => 'Invalid email or password.'];
            }

            return [
                'success' => true,
                'user' => Auth::user()
            ];
        } catch (QueryException $e) {
            Log::error("ERROR_DATABASE: Login failed: " . $e->getMessage());
            throw new RuntimeException("Database error occurred.");
        } catch (Throwable $e) {
            Log::error("ERROR_SYSTEM: Login system error: " . $e->getMessage());
            throw new RuntimeException("System error occurred.");
        }
    }

    public function logout(): void
    {
        Session::forget('user');
        Auth::logout();
    }

}
