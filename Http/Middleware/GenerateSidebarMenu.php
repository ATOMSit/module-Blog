<?php

namespace Modules\Blog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateSidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        \Menu::get('MyNavBar')
            ->add('Articles', ['id' => 74398247329487])
            ->add('Liste des articles', ['route' => 'blog.admin.post.index', 'parent' => 74398247329487]);
        return $next($request);
    }
}
