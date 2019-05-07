<?php


namespace Modules\Blog\Repositories;


use App\User;
use Carbon\Carbon;
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
        return $post = Post::query()
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
        if ($post_data['unpublished_at'] == null) {
            $unpublished_at = Carbon::now();
        }
        $post = new Post([
            'title' => $post_data['title'],
            'slug' => "test",
            'body' => $post_data['body'],
            'online' => $post_data['online'],
            'indexable' => $post_data['indexable'],
            'published_at' => Carbon::now(),
            'unpublished_at' => null
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
            'slug' => "test",
            'body' => $post_data['body'],
            'online' => $post_data['online'],
            'indexable' => $post_data['indexable'],
            'published_at' => Carbon::now(),
            'unpublished_at' => null
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
