<?php

namespace Modules\Blog\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Blog\Entities\Post;
use Modules\Blog\Http\Requests\PostRequest;
use phpDocumentor\Reflection\Types\Boolean;

class PostRepository implements PostRepositoryInterface
{
    /**
     * Get a specific post.
     *
     * @param int $post_id
     * @return Model
     */
    public function find(Post $post_id): Collection
    {
        return $post = Post::query()
            ->findOrFail($post_id);
    }

    /**
     * Get all post.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Post::all();
    }

    /**
     * Create a new Post.
     *
     * @param Model $model
     * @param array $post_data
     * @return Post
     */
    public function store(Model $model, PostRequest $post_data): Post
    {
        if ($post_data->get('unpublished_at') === null) {
            $unpublished_at = null;
        } else {
            $unpublished_at = Carbon::createFromFormat('d/m/Y H:i', $post_data->get('unpublished_at'))->toDateTimeString();
        }
        $post = new Post([
            'title' => $post_data->get('title'),
            'slug' => "test",
            'body' => $post_data->get('body'),
            'online' => $post_data->get('online'),
            'indexable' => $post_data->get('indexable'),
            'published_at' => Carbon::createFromFormat('d/m/Y H:i', $post_data->get('published_at'))->toDateTimeString(),
            'unpublished_at' => $unpublished_at
        ]);
        $post->author()->associate($model)->save();
        if ($post_data->get('tags') !== null) {
            $tag_interface = new TagRepository();
            $tag_interface->add_tags($post_data->get('tags'), $post);
        }
        if ($post_data->file('input_cropper') !== null) {
            $post->clearMediaCollection('cover');
            $width = $post_data->get('picture')['width'];
            $height = $post_data->get('picture')['height'];
            $x = $post_data->get('picture')['x'];
            $y = $post_data->get('picture')['y'];
            $post->addMedia($post_data->file('input_cropper'))
                ->withManipulations([
                    'thumb' => ['manualCrop' => "$width, $height, $x, $y"],
                ])
                ->toMediaCollection('cover');
        }
        return $post;
    }

    /**
     * Update a post.
     *
     * @param int $post_id
     * @param array $post_data
     * @return Post
     */
    public function update(Post $post, PostRequest $post_data): Post
    {
        if ($post_data->get('unpublished_at') === null) {
            $unpublished_at = null;
        } else {
            $unpublished_at = Carbon::createFromFormat('d/m/Y H:i', $post_data->get('unpublished_at'))->toDateTimeString();
        }
        $post->update([
            'title' => $post_data->get('title'),
            'slug' => "test",
            'body' => $post_data->get('body'),
            'online' => $post_data->get('online'),
            'indexable' => $post_data->get('indexable'),
            'published_at' => Carbon::createFromFormat('d/m/Y H:i', $post_data->get('published_at'))->toDateTimeString(),
            'unpublished_at' => $unpublished_at
        ]);
        if ($post_data->get('tags') !== null) {
            $tag_interface = new TagRepository();
            $tag_interface->add_tags($post_data->get('tags'), $post);
        }
        if ($post_data->file('input_cropper') !== null) {
            $post->clearMediaCollection('cover');
            $width = $post_data->get('picture')['width'];
            $height = $post_data->get('picture')['height'];
            $x = $post_data->get('picture')['x'];
            $y = $post_data->get('picture')['y'];
            $post->addMedia($post_data->file('input_cropper'))
                ->withManipulations([
                    'thumb' => ['manualCrop' => "$width, $height, $x, $y"],
                ])
                ->toMediaCollection('cover');
        }
        return $post;
    }

    /**
     * Delete a post.
     *
     * @param $post_id
     * @return mixed
     */
    public function delete(Post $post_id)
    {
        return Post::destroy($post_id);
    }

    /**
     * Restore a post.
     *
     * @param int $post_id
     * @return bool
     */
    public function restore($post_id): Boolean
    {
        return Post::withoutTrashed()
            ->find($post_id)
            ->restore();
    }

    /**
     * Force delete a post.
     *
     * @param int $post_id
     * @return Post
     */
    public function forceDelete(int $post_id): Post
    {
        return Post::query()
            ->where('id', $post_id)
            ->first()
            ->forceDelete();
    }
}