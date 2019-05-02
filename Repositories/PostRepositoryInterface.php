<?php


namespace Modules\Blog\Repositories;


interface PostRepositoryInterface
{
    /**
     * Get's a post by it's ID
     *
     * @param int
     */
    public function get($post_id);

    /**
     * Get's all posts.
     *
     * @return mixed
     */
    public function all();

    public function store();

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update($post_id, array $post_data);

    /**
     * Deletes a post.
     *
     * @param int
     */
    public function delete($post_id);

    public function restore($post_id);

    public function forceDelete($post_id);
}