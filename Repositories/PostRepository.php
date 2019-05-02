<?php


namespace Modules\Blog\Repositories;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Entities\Post;

class PostRepository implements PostRepositoryInterface
{
    /**
     * Get a specific post.
     *
     * @param int $post_id
     * @return mixed
     */
    public function find(int $post_id)
    {
        return Post::query()
            ->findOrFail($post_id);
    }

    /**
     * Get all post.
     *
     * @return mixed
     */
    public function all()
    {
        return Post::all();
    }

    /**
     * Create a new record.
     *
     * @param User $user
     * @param array $post_data
     * @return mixed|void
     */
    public function store(Model $model, array $post_data)
    {
        $post = new Post([
            'title' => $post_data['title'],
            'slug' => $post_data['slug'],
            'body' => $post_data['body'],
            'online' => $post_data['online'],
            'indexable' => $post_data['indexable'],
            'published_at' => $post_data['published_at'],
            'unpublished_at' => $post_data['unpublished_at'],
        ]);
        $model->blog__posts()->save($post);
        return $post;
    }

    /**
     * Update a post.
     *
     * @param int $post_id
     * @param array $post_data
     * @return mixed
     */
    public function update($post_id, array $post_data)
    {
        $post = $this->find($post_id);
        $post->update([
            'title' => $post_data['title'],
            'slug' => $post_data['slug'],
            'body' => $post_data['body'],
            'online' => $post_data['online'],
            'indexable' => $post_data['indexable'],
            'published_at' => $post_data['published_at'],
            'unpublished_at' => $post_data['unpublished_at']
        ]);
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

    public function restore($post_id)
    {
        return Post::withoutTrashed()
            ->find($post_id)
            ->restore();
    }

    public function forceDelete(int $post_id)
    {
        return Post::query()
            ->where('id', $post_id)
            ->first()
            ->forceDelete();
    }
}