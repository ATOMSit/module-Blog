<?php


namespace Modules\Blog\Repositories;


use Illuminate\Database\Eloquent\Model;

interface TagRepositoryInterface
{
    public function add_tags(array $tags, Model $model);

    public function store(string $name);

    public function destroy();
}