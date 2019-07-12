<?php

namespace Modules\Blog\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Blog\Entities\Post;
use Modules\Blog\Http\Requests\PostRequest;
use phpDocumentor\Reflection\File;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;

interface PostRepositoryInterface
{
    /**
     * Get a specific post.
     *
     * @param int $post_id
     * @return Model
     */
    public function find(Post $post_id): Collection;

    /**
     * Get all post.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Created a new Post.
     *
     * @param Model $model
     * @param array $post_data
     * @return Post
     */
    public function store(Model $model, PostRequest $post_data): Post;

    /**
     * Update a post.
     *
     * @param int $post_id
     * @param array $post_data
     * @return Post
     */
    public function update(Post $post_id, PostRequest $post_data): Post;

    /**
     * Delete a post
     *
     * @param int $post_id
     * @return Post
     */
    public function delete(Post $post_id);

    /**
     * Restore a post.
     *
     * @param int $post_id
     * @return bool
     */
    public function restore(int $post_id): Boolean;

    /**
     * Force delete a post.
     *
     * @param int $post_id
     * @return Post
     */
    public function forceDelete(int $post_id): Post;
}