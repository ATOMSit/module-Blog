<?php


namespace Modules\Blog\Repositories;


use App\User;

interface PostRepositoryInterface
{
    /**
     * Get a specific post.
     *
     * @param int $post_id
     * @return mixed
     */
    public function find(int $post_id);

    /**
     * Get all post.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create a new record.
     *
     * @param User $user
     * @param array $post_data
     * @return mixed
     */
    public function store(User $user, array $post_data);

    /**
     * Update a post.
     *
     * @param int $post_id
     * @param array $post_data
     * @return mixed
     */
    public function update(int $post_id, array $post_data);

    /**
     * Delete a post.
     *
     * @param int $post_id
     * @return mixed
     */
    public function delete(int $post_id);

    /**
     * Restore a post.
     *
     * @param int $post_id
     * @return mixed
     */
    public function restore(int $post_id);

    /**
     * Force delete a post.
     *
     * @param int $post_id
     * @return mixed
     */
    public function forceDelete(int $post_id);
}