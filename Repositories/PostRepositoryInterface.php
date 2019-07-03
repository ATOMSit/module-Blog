<?php

namespace Modules\Blog\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Blog\Entities\Post;
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
    public function find(int $post_id): Model;

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
    public function store(Model $model, array $post_data): Post;

    /**
     * Update a post.
     *
     * @param int $post_id
     * @param array $post_data
     * @return Post
     */
    public function update(int $post_id, array $post_data): Post;

    /**
     * Delete a post
     *
     * @param int $post_id
     * @return Post
     */
    public function delete(int $post_id): Integer;

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