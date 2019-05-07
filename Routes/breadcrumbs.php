<?php

// Home > Blog
Breadcrumbs::for('blog.admin.post.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Gestion du blog', route('blog.admin.post.index'));
});

// Home > Blog > Create post
Breadcrumbs::for('blog.admin.post.create', function ($trail) {
    $trail->parent('blog.admin.post.index');
    $trail->push("Création d'un article", route('blog.admin.post.create'));
});