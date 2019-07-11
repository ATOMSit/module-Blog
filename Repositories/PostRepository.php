<?php

namespace Modules\Blog\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Blog\Entities\Post;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;

class PostRepository implements PostRepositoryInterface
{
    /**
     * Get a specific post.
     *
     * @param int $post_id
     * @return Model
     */
    public function find(int $post_id): Model
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
    public function store(Model $model, array $post_data): Post
    {
        $date_published = $post_data['published_at'] . ' ' . $post_data['published_at_time'];
        $date_unpublished = $post_data['unpublished_at'] . ' ' . $post_data['unpublished_at_time'];
        $post = new Post([
            'title' => $post_data['title'],
            'slug' => "test",
            'body' => $post_data['body'],
            'online' => $post_data['online'],
            'indexable' => $post_data['indexable'],
            'published_at' => Carbon::parse($date_published),
            'unpublished_at' => Carbon::parse($date_published)
        ]);
        $post->author()->associate($model)->save();
        return $post;
    }

    /**
     * Update a post.
     *
     * @param int $post_id
     * @param array $post_data
     * @return Post
     */
    public function update($post_id, array $post_data): Post
    {
        $date_published = $post_data['published_at'];
        $date_unpublished = $post_data['unpublished_at'];
        $post = $this->find($post_id);
        $post->update([
            'title' => $post_data['title'],
            'slug' => "test",
            'body' => $post_data['body'],
            'online' => $post_data['online'],
            'indexable' => $post_data['indexable'],
            'published_at' => Carbon::createFromFormat('d/m/Y H:i', $post_data['published_at'])->toDateTimeString(),
            'unpublished_at' =>Carbon::createFromFormat('d/m/Y H:i', $post_data['unpublished_at'])->toDateTimeString()
        ]);
        $post->save();
        return $post;
    }

    /**
     * Delete a post.
     *
     * @param $post_id
     * @return mixed
     */
    public function delete(int $post_id)
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