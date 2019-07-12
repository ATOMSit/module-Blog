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
        $menu = \Menu::get('sidebar');

        $menu->add('Articles', ['icon' => 'fas fa-newspaper', 'id' => 'blog1']);
        $menu->add('Liste des articles', ['route' => 'blog.admin.post.index', 'parent' => 'blog1', 'id' => 'blog11']);
        $menu->add('Ajouter un article', ['route' => 'blog.admin.post.create', 'parent' => 'blog1', 'id' => 'blog12']);

        return $next($request);
    }
}
