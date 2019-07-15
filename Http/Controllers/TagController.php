<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Option;
use App\Plugin;
use App\Website;
use Carbon\Carbon;
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

class TagController extends Controller
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
     * PostController constructor.
     *
     * @param PostRepositoryInterface $post
     */
    public function __construct()
    {
        $this->default_lang = Option::query()
            ->where('name', 'default_language')
            ->first()->value;
        $this->languages = explode(',', Option::query()
            ->where('name', 'languages')
            ->first()
            ->value);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        app()->setLocale($this->default_lang);
        $term = trim($request->q);
        if (empty($term)) {
            return abort(404);
        }
        $tags = Tag::query()->where('name', 'LIKE', '%' . strtolower($term) . '%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'name' => $tag->name];
        }
        return response()->json($formatted_tags);
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
        return $request->get('tags');
        $this->authorize('create', Post::class);
        $post = $this->post->store(Auth::user(), $request->all());
        if ($request->file('input_cropper') !== null) {
            $width = $request->get('picture')['width'];
            $height = $request->get('picture')['height'];
            $x = $request->get('picture')['x'];
            $y = $request->get('picture')['y'];
            $post->addMedia($request->file('input_cropper'))
                ->withManipulations([
                    'thumb' => ['manualCrop' => "$width, $height, $x, $y"],
                ])
                ->toMediaCollection('cover');
        }
        return back()
            ->with('success', "L'article a correctement était publié sur votre site internet");
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
