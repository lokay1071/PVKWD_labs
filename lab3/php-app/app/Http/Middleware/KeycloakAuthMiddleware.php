<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Illuminate\Support\Facades\Log;

class KeycloakAuthMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = substr($authorizationHeader, 7);

        try {
            $decoded = JWT::decode($token, new Key(env('KEYCLOAK_PUBLIC_KEY'), 'RS256'));

            $userRoles = $decoded->realm_access->roles ?? [];

            if (!empty($roles)) {
                if (!$this->hasRequiredRole($userRoles, $roles)) {
                    return response()->json(['error' => 'Forbidden'], 403);
                }
            }

            $request->attributes->add(['user' => $decoded]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }

    private function hasRequiredRole(array $userRoles, array $requiredRoles): bool
    {
        foreach ($requiredRoles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }
        return false;
    }
}
