<?php


namespace Modules\Blog\Repositories;


class PostRepository implements PostRepositoryInterface
{
    /**
     * Get's a post by it's ID
     *
     * @param int
     * @return collection
     */
    public function get($post_id)
    {
        return Post::find($post_id);
    }

    /**
     * Get's all posts.
     *
     * @return mixed
     */
    public function all()
    {
        return Post::all();
    }

    public function store()
    {
        // TODO: Implement store() method.
    }

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update($post_id, array $post_data)
    {
        Post::find($post_id)->update($post_data);
    }

    /**
     * Deletes a post.
     *
     * @param int
     */
    public function delete($post_id)
    {
        Post::destroy($post_id);
    }

    public function restore($post_id)
    {
        // TODO: Implement restore() method.
    }

    public function forceDelete($post_id)
    {
        // TODO: Implement forceDelete() method.
    }
}