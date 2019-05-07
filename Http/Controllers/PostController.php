<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Plugin;
use App\Website;
use Carbon\Carbon;
use Igaster\LaravelTheme\Theme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\FormBuilder;
use Modules\Blog\Entities\Post;
use Modules\Blog\Forms\PostForm;
use Modules\Blog\Http\Requests\PostRequest;
use Illuminate\Http\Response;
use Modules\Blog\Repositories\PostRepositoryInterface;
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
        $this->default_lang = "en";
        $this->middleware('auth', ['except' => ['show', 'datatable']]);
    }

    public function datatable()
    {
        $array = array("fr", "en", "de", "es");
        $default_lang = "en";
        $array = \array_diff($array, [$default_lang]);
        $model = Post::query();
        $rowColumns = array('author', 'status', 'action', $array);
        $datatable = Datatables::eloquent($model);
        foreach ($array as $item) {
            $datatable->addColumn($item, function (Post $post) use ($item) {
                app()->setLocale($item);
                $route = route('blog.admin.post.translation.edit', ['id' => $post->id, 'lang' => $item]);
                if (strlen($post->title) === 0) {
                    return '<a href="'.$route.'"><span class="flaticon2-close-cross" style="font-size: 25px"></span></a>';
                } else {
                    return '<a href="'.$route.'"><span class="flaticon2-checkmark" style="font-size: 25px"></span></a>';
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
                $url_destroy = route('blog.admin.post.destroy', ['id' => $post->id]);

                return '
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-32px, 27px, 0px);">
                                <a class="dropdown-item" href="' . $url_edit . '"><i class="la la - edit"></i> Modifier</a>
                            </div >
                            <a href = "' . $url_edit . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title = "View" >
                                <i class="la la-edit" ></i >
                            </a >
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
        $array = ["fr", "en", "de", "es"];
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
                'data' => $item, 'name' => $item, 'title' => '<span class="flag-icon flag-icon-' . $item . '" ></span >'
            ]);
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
    public function show(int $id)
    {
        $post = $this->post->find($id);
        $this->authorize('show', $post);
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
        Auth::user()->givePermissionTo('blog_post_create');
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
        $this->authorize('create', Post::class);
        $post = $this->post->store(Auth::user(), $request->all());
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
     * Show the form for editing the specified resource.
     *
     * @param FormBuilder $formBuilder
     * @param int $id
     * @param string|null $lang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(FormBuilder $formBuilder, int $id, string $lang = null)
    {
        // Dans un premier temps nous determinons si et seulement si il sagit d'une langue déjà connu
        $route = Route::currentRouteName();
        if ($route === "blog.admin.post.edit") {
            app()->setLocale($this->default_lang);
            $post = $this->post->find($id);
            $this->authorize('update', $post);
            $form = $formBuilder->create(PostForm::class, [
                'method' => 'POST',
                'url' => route('blog.admin.post.update', ['id' => $id]),
                'model' => $post
            ]);
        } elseif ($route === "blog.admin.post.translation.edit") {
            // Recuperation du site
            $website = app(\Hyn\Tenancy\Environment::class)->tenant();
            $plugin = Plugin::query()->where('name', 'translation')->first();
            $website->plugins()->sync($plugin);
            $exists = $website->plugins->contains($plugin->id);
            if ($exists === true) {
                if ($lang !== null) {
                    app()->setLocale($lang);
                    $post = $this->post->find($id);
                    $this->authorize('update', $post);
                    $form = $formBuilder->create(PostForm::class, [
                        'method' => 'POST',
                        'url' => route('blog.admin.post.translation.update', ['id' => $id, 'lang' => $lang]),
                        'model' => $post
                    ]);
                }
            } else {
                $post = $this->post->find($id);
                return redirect()->route("blog.admin.post.edit", ['id' => $post->id]);
            }
        }
        return view('blog::application.posts.post')
            ->with('post', $post)
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
    public function update(PostRequest $request, int $id, string $lang = null)
    {
        $route = Route::currentRouteName();
        if ($route === "blog.admin.post.update") {
            $post = $this->post->find($id);
            $this->authorize('update', $post);
            DB::beginTransaction();
            try {
                app()->setLocale($this->default_lang);
                $this->post->update($id, $request->all());
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['error' => $ex->getMessage()], 500);
            }
            return back()
                ->with('success', "Profile mis à jour");
        } elseif ($route === "blog.admin.post.translation.update") {
            $post = $this->post->find($id);
            $this->authorize('update', $post);
            DB::beginTransaction();
            try {
                app()->setLocale($lang);
                $this->post->update($id, $request->all());
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['error' => $ex->getMessage()], 500);
            }
            return back()
                ->with('success', "Profile mis à jour");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        $post = $this->post->find($id);
        $this->authorize('delete', $post);
        $this->post->delete($id);
    }
}
