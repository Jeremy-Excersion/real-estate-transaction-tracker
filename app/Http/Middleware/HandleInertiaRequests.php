<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $permissions = $user ? $user->getAllPermissions()->pluck('name') : null;
        $roles = $user ? $user->roles()->pluck('name') : null;

        return [
            ...parent::share($request),
            'loggedIn' => fn () => $user ? true : false,
            'auth' => [
                'user' => $user ? $user->only('id', 'name', 'email', 'roles') : null,
                'permissions' => $permissions,
                'roles' => $roles,
            ],
        ];
    }
}
