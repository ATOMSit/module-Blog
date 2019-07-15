<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Option;
use App\Plugin;
use App\Website;
use Carbon\Carbon;
use http\Client\Curl\User;
use Igaster\LaravelTheme\Theme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Modules\Blog\Entities\Post;
use Modules\Blog\Entities\Tag;
use Modules\Blog\Forms\PostForm;
use Modules\Blog\Http\Requests\PostRequest;
use Illuminate\Http\Response;
use Modules\Blog\Repositories\PostRepositoryInterface;
use Modules\SEOBasic\Forms\BasicForm;
use Modules\SEOBasic\Http\Controllers\SEOBasicController;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Route;

class PostController extends Controller
{
    /**
     * @var string
     */
    protected $default_lang;

    /**
     * @var array
     */
    protected $languages;

    /**
     * @var PostRepositoryInterface
     */
    protected $post;

    /**
     * PostController constructor.
     *
     * @param PostRepositoryInterface $post
     */
    public function __construct(PostRepositoryInterface $post)
    {
        $this->post = $post;
        $this->default_lang = Option::query()
            ->where('name', 'default_language')
            ->first()->value;
        $this->languages = explode(',', Option::query()
            ->where('name', 'languages')
            ->first()
            ->value);
        $this->middleware('auth', ['except' => ['show', 'datatable']]);
    }

    public function datatable()
    {
        $array = \array_diff($this->languages, [$this->default_lang]);
        $default_lang = $this->default_lang;
        $model = Post::query();
        $rowColumns = array('author', 'status', 'action', $array);
        $datatable = Datatables::eloquent($model);
        foreach ($array as $item) {
            $datatable->addColumn($item, function (Post $post) use ($item) {
                app()->setLocale($item);
                $route = route('blog.admin.post.translation.edit', ['id' => $post->id, 'lang' => $item]);
                if (strlen($post->title) === 0) {
                    return '<a href="' . $route . '"><span class="flaticon2-close-cross" style="font-size: 25px"></span></a>';
                } else {
                    return '<a href="' . $route . '"><span class="flaticon2-checkmark" style="font-size: 25px"></span></a>';
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
                $url_edit = route('blog.admin.post.edit', ['id' => $post->id]);
                return '
                        <span style="overflow: visible; position: relative; width: 110px;">						
       					    <a href="' . $url_edit . '" title="Modifier" class="btn btn-sm btn-clean btn-icon btn-icon-md">							
       					        <i class="la la-edit"></i>						
       					    </a>						
       					    <a href="#" onclick="delete_post(' . $post->id . ')" title="Supprimer" class="btn btn-sm btn-clean btn-icon btn-icon-md">							
       					        <i class="la la-trash"></i>						
       					    </a>					
       					</span>
                ';
            })
            ->rawColumns($rowColumns)
            ->addIndexColumn()
            ->toJson();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {

        $array = \array_diff($this->languages, [$this->default_lang]);
        if (request()->ajax()) {
            return DataTables::of(Post::query())->toJson();
        }
        $default_columns = ['author', 'status', 'action'];
        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'titre'],
        ]);
        foreach ($array as $item) {
            if ($item === 'en') {
                $html->addColumn([
                    'data' => $item, 'name' => $item, 'title' => '<span class="flag-icon flag-icon-gb" ></span >'
                ]);
            } else {
                $html->addColumn([
                    'data' => $item, 'name' => $item, 'title' => '<span class="flag-icon flag-icon-' . $item . '" ></span >'
                ]);
            }

        }
        foreach ($default_columns as $default_column) {
            $html->addColumn([
                'data' => $default_column, 'name' => $default_column,
            ]);
        }
        $builder->ajax(route('blog.admin.post.datatable'));
        return view('blog::application.posts.index')
            ->with('html', $html)
            ->with('locales', $array);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Post $post)
    {
        $posts = Post::query()->with('tags')->findOrFail($post->id);
        return ($posts['tags']['0']);
        $this->authorize('view', $post);
        return var_dump($post->tags);
        return view('blog::show')
            ->with('post', $post);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param FormBuilder $formBuilder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PostRequest $request)
    {
        $user = Auth::id();
        $user = \App\User::query()->findOrFail($user);
        $this->authorize('create', Post::class);
        $this->post->store($user, $request);
        return redirect()->route('blog.admin.post.index', 200)
            ->with('success', "L'article a correctement était publié sur votre site internet");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FormBuilder $formBuilder
     * @param int $id
     * @param string|null $lang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(FormBuilder $formBuilder, Post $post, string $lang = null)
    {
        // Dans un premier temps nous determinons si et seulement si il sagit d'une langue déjà connu
        $route = Route::currentRouteName();
        if ($route === "blog.admin.post.edit") {
            app()->setLocale($this->default_lang);
            $posts = Post::query()->with('tags')->findOrFail($post->id);

            $formOptions = [
                'method' => 'POST',
                'url' => route('blog.admin.post.update', ['id' => $post->id]),
                'model' => $posts
            ];
        } elseif ($route === "blog.admin.post.translation.edit") {
            // Recuperation du site
            $exists = $website = app(\Hyn\Tenancy\Environment::class)->tenant()->plugin('translation');
            if ($exists === true) {
                if ($lang !== null) {
                    app()->setLocale($lang);
                    $posts = Post::query()->with('tags')->findOrFail($post->id);

                    $this->authorize('update', $post);
                    $formOptions = [
                        'method' => 'POST',
                        'url' => route('blog.admin.post.translation.update', ['id' => $post->id, 'lang' => $lang]),
                        'model' => $posts
                    ];

                }
            } else {
                return redirect()->route("blog.admin.post.edit", ['id' => $post->id]);
            }
        }
        $this->authorize('update', $post);

        $form = $formBuilder->create(PostForm::class, $formOptions);
        $form->add('input_media_delete', 'checkbox', [
            'value' => 1,
            'checked' => false
        ]);
        app()->setLocale($this->default_lang);
        return view('blog::application.posts.post')
            ->with('post', $posts)
            ->with('lang', $lang)
            ->with('form_post', $form);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PostRequest $request, Post $post, string $lang = null)
    {
        if ($lang === null) {
            app()->setLocale($this->default_lang);
        } elseif ($lang !== null) {
            app()->setLocale($lang);
        }
        $this->authorize('update', $post);
        DB::beginTransaction();
        try {
            app()->setLocale($lang);
            $this->post->update($post, $request);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
        return back()
            ->with('success', "Votre article a bien était mis à jour.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $post = $this->post->find($id);
            $this->authorize('delete', $post);
            $this->post->delete($id);
            DB::commit();
            return redirect()->back();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
