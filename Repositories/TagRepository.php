<?php

namespace Modules\Blog\Repositories;

use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Entities\Tag;

class TagRepository implements TagRepositoryInterface
{
    public function add_tags(array $tags, Model $model)
    {
        $array_tags = array();
        foreach ($tags as $tag) {
            if (strpos($tag, 'id') !== false) {
                $id = str_replace("id", "", $tag);
                $new_tag = Tag::query()->findOrFail($id);
                array_push($array_tags, $new_tag->id);
            } else {
                $new_tag = $this->store($tag);
                array_push($array_tags, $new_tag->id);
            }
        }
        $model->tags()->sync($array_tags);
    }

    public function store(string $name)
    {
        $tag = new Tag([
            'name' => $name,
            'slug' => $name
        ]);
        $tag->save();
        return $tag;
    }

    public function destroy()
    {

    }
}