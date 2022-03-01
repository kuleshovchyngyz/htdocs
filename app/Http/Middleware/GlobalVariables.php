<?php

namespace App\Http\Middleware;

use Closure;
use App\Project;

class GlobalVariables
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // \View::share('all_projects', Project::all());
        // if (session('selected_project_id') !== null) {
        //     \View::share('selected_project', Project::find(session('selected_project_id')));
        // }
        return $next($request);
    }
}
