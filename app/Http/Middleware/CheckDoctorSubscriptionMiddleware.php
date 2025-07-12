<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Constants\ExceptionMessages;
use Symfony\Component\HttpFoundation\Response;

class CheckDoctorSubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $active_subscriptions = Subscription::where('user_id', auth()->id())
                ->where('is_active' , 1)
                ->exists();

        if(! $active_subscriptions)
            return failure(
                ExceptionMessages::MSG_NO_ACTIVE_SUBSCRIPTION,
                403
            );
            
        return $next($request);
    }
}
