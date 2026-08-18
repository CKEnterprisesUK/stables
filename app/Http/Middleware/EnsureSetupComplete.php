<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Http\Controllers\Admin\SetupController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSetupComplete
{
    /**
     * Redirect super_admin users to the setup checklist if there are incomplete steps.
     *
     * This middleware should be applied to admin routes (excluding the setup page itself
     * and settings pages that are part of the setup flow).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only applies to super_admin users
        if (!$user || $user->role !== UserRole::SuperAdmin) {
            return $next($request);
        }

        // Don't redirect if already on the setup page or a settings page
        // (they need access to settings to complete the setup)
        if ($request->routeIs('admin.setup')
            || $request->routeIs('admin.settings.*')
            || $request->routeIs('admin.branding.*')
            || $request->routeIs('admin.sponsorship-info.*')
        ) {
            return $next($request);
        }

        if (SetupController::hasIncompleteSteps()) {
            return redirect()->route('admin.setup');
        }

        return $next($request);
    }
}
