<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserDetails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Skip check for unauthenticated users
        if (!$user) {
            return $next($request);
        }

        // Skip check for admin users
        if ($user->userDetail && $user->userDetail->role === 'admin') {
            return $next($request);
        }

        // Check if user details are complete (cached in session)
        if (!$this->areUserDetailsComplete($user)) {
            // Redirect to profile edit page if not on allowed pages
            if (!$this->isAllowedPage($request)) {
                return redirect()->route('profile.edit')->with('completion_required', true);
            }
        }

        return $next($request);
    }

    /**
     * Check if user details are complete.
     */
    private function areUserDetailsComplete($user): bool
    {
        // Check session cache first
        $cacheKey = 'user_details_complete_' . $user->id;
        if (session()->has($cacheKey)) {
            return session($cacheKey);
        }

        // Check required fields
        $userDetail = $user->userDetail;
        $isComplete = $userDetail ? $userDetail->isProfileComplete() : false;

        session([$cacheKey => $isComplete]);
        return $isComplete;
    }

    /**
     * Check if current page is allowed without complete user details.
     */
    private function isAllowedPage(Request $request): bool
    {
        $currentRoute = $request->route();

        // Allow home page
        if ($currentRoute && in_array($currentRoute->getName(), ['home'])) {
            return true;
        }

        // Allow profile related pages
        if ($currentRoute && str_starts_with($currentRoute->getName(), 'profile.')) {
            return true;
        }

        // Allow dashboard (will be handled by CheckRole middleware)
        if ($currentRoute && $currentRoute->getName() === 'dashboard') {
            return true;
        }

        // Allow logout
        if ($currentRoute && $currentRoute->getName() === 'logout') {
            return true;
        }

        return false;
    }
}
