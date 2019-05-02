<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\FormBuilder;
use Modules\Blog\Entities\Post;
use Modules\Blog\Forms\PostForm;
use Modules\Blog\Http\Requests\PostRequest;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class PostController extends Controller
{
    public function __construct()
    {
    }

    public function datatable()
    {
        $array = array("fr", "en");
        $default_lang = "en";
        $array = \array_diff($array, [$default_lang]);
        $model = Post::query();
        $rowColumns = array('author', 'status', 'action', $array);
        $datatable = Datatables::eloquent($model);
        foreach ($array as $item) {
            $datatable->addColumn($item, function (Post $post) use ($item) {
                app()->setLocale($item);
                if (strlen($post->title) === 0) {
                    return '<span class="flaticon2-close-cross"></span>';
                }
                return $post->title;
            });
            $rowColumns[] = $item;
        }
        return $datatable
            ->addColumn("author", function (Post $post) {
                return '<div class="kt-user-card-v2"><div class="kt-user-card-v2__pic"><img src="' . $post->author->avatar() . '"></div><div class="kt-user-card-v2__details"><span class="kt-user-card-v2__name">' . $post->author->first_name . ' ' . $post->author->last_name . '</span><a href="#" class="kt-user-card-v2__email kt-link">' . $post->author->role() . '</a></div></div>';
            })
            ->addColumn('title', function (Post $post) use ($default_lang) {
                app()->setLocale($default_lang);
                return $post->title;
            })
            ->addColumn("status", function (Post $post) {
                if ($post->online == 1) {
                    return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">En ligne</span>';
                } elseif ($post->online == 0 and $post->indexable == 0) {
                    return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Hors ligne</span>';
                }
            })
            ->addColumn('action', function (Post $post) {
                return '
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-32px, 27px, 0px);">
                                <a class="dropdown-item" href="/admin/blog/edit/' . $post->id . '"><i class="la la-edit"></i> Edit Details</a>
                                <a class="dropdown-item" href="#"><i class="la la-leaf"></i> Update Status</a>
                                <a class="dropdown-item" href="#"><i class="la la-print"></i> Generate Report</a>
                            </div>
                        ';
            })
            ->rawColumns($rowColumns)
            ->addIndexColumn()
            ->toJson();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Builder $builder)
    {
        $array = ["fr", "en"];
        $default_lang = "en";
        $array = \array_diff($array, [$default_lang]);
        if (request()->ajax()) {
            return DataTables::of(Post::query())->toJson();
        }
        $default_columns = ['author', 'status', 'action'];
        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'titre'],
        ]);
        foreach ($array as $item) {
            $html->addColumn([
                'data' => $item, 'name' => $item, 'title' => $item
            ]);
        }
        foreach ($default_columns as $default_column) {
            $html->addColumn([
                'data' => $default_column, 'name' => $default_column, 'title' => $default_column
            ]);
        }
        $builder->ajax(route('blog.admin.post.datatable'));
        return view('blog::application.posts.index')
            ->with('html', $html)
            ->with('locales', $array);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $this->authorize('create', Post::class);
        $form = $formBuilder->create(PostForm::class, [
            'method' => 'POST',
            'url' => route('blog.admin.post.store')
        ]);
        $form->modify('online', 'select', [
            'selected' => [1],
        ]);
        $form->modify('indexable', 'select', [
            'selected' => [1],
        ]);
        return view('blog::application.posts.post')
            ->with('form_post', $form);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     * @return Response
     */
    public function store(PostRequest $request)
    {
        if ($request->get('unpublished_at') !== null) {
            $unpublished_at = Carbon::now();
        } else {
            $unpublished_at = null;
        }
        $post = new Post([
            'title' => $request->get('title'),
            'slug' => 'ok',
            'body' => $request->get('body'),
            'online' => $request->get('online'),
            'indexable' => $request->get('indexable'),
            'published_at' => Carbon::now(),
            'unpublished_at' => null
        ]);
        $user = Auth::user();
        $user->blog__posts()->save($post);
        if ($request->file('file') !== null) {
            $width = $request->get('picture')['width'];
            $height = $request->get('picture')['height'];
            $x = $request->get('picture')['x'];
            $y = $request->get('picture')['y'];
            $post->addMedia($request->file('file'))
                ->toMediaCollection('cover');
        }
        return back()
            ->with('success', "L'article a correctement était publié sur votre site internet");
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('blog::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(FormBuilder $formBuilder, $id)
    {
        $post = Post::findOrFail($id);
        $form = $formBuilder->create(PostForm::class, [
            'method' => 'POST',
            'url' => route('blog.admin.post.update', ['id' => $id]),
            'model' => $post
        ]);
        return view('blog::application.posts.post')
            ->with('form_post', $form);
    }

    /**
     * Update the specified resource in storage.
     * @param PostRequest $request
     * @param int $id
     * @return Response
     */
    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        DB::beginTransaction();
        try {
            $post->update([
                'title' => $request->get('title'),
                'slug' => 'ok',
                'body' => $request->get('body'),
                'online' => $request->get('online'),
                'indexable' => $request->get('indexable'),
                'published_at' => Carbon::now(),
                'unpublished_at' => null
            ]);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
        return back()
            ->with('success', "Profile mis à jour");
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}