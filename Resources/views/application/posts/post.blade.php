@extends('application.layouts.app')

@section('title')
    @isset($post)
        Modification d'un article
    @else
        Création d'un article
    @endif
@endsection

@section('breadcrumbs')
    @isset($post)
        {{ Breadcrumbs::render('blog.admin.post.edit',$post) }}
    @else
        {{ Breadcrumbs::render('blog.admin.post.create') }}
    @endif
@endsection

@push('styles')
    <link href="{{asset('application/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')
    <script src="{{asset('application/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('application/vendors/general/select2/dist/js/select2.full.js')}}" type="text/javascript"></script>
    <script src="{{asset('application/js/pages/crud/forms/widgets/select2.js')}}" type="text/javascript"></script>
    <script>
        var KTBootstrapDatetimepicker = function () {
            var demos = function () {
                // minimal setup
                $('#published_at').datetimepicker({
                    todayHighlight: true,
                    autoclose: true,
                    format: 'dd/mm/yyyy hh:ii'
                });
                // minimal setup
                $('#unpublished_at').datetimepicker({
                    todayHighlight: true,
                    autoclose: true,
                    format: 'dd/mm/yyyy hh:ii'
                });
            };
            return {
                // public functions
                init: function () {
                    demos();
                }
            };
        }();
        jQuery(document).ready(function () {
            KTBootstrapDatetimepicker.init();
        });
    </script>
    @isset($post)
        <script>
            var json = {{$post->tags}};
            obj = JSON.parse(json);

            console.log(obj);
        </script>
    @else

    @endisset
    <script>
        $('#tags').select2({
            reselectLastSelection: false,
            closeOnSelect: false,
            tags: true,
            placeholder: "Choose tags...",
            minimumInputLength: 2,
            tokenSeparators: [',', ' '],
            ajax: {
                url: '../../../api/blog/tags/find',
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: 'id' + item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

    </script>
@endpush

@section('content')
    @widget('AdvicesWidget', ['plugin' => 'blog'])
    @isset($lang)
        @if($lang === null)
            lang null
        @else
            <div class="alert alert-light alert-elevate fade show" role="alert">
                <div class="alert-icon"><span class="flag-icon flag-icon-{{$lang}}" style="font-size: 35px"></span></div>
                <div class="alert-text">
                    Vous editer la traduction Française de votre article
                </div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="la la-close"></i></span>
                    </button>
                </div>
            </div>
        @endif
    @endisset
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Rédiger un article
                        </h3>
                    </div>
                </div>
                {!! form_start($form_post,$options = ['class' => 'kt-form']) !!}
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-8">
                            {!! form_row($form_post->title,$options=['label'=>trans("blog::post_translation.fields.title.label"),'description'=>trans("blog::post_translation.fields.title.description")]) !!}
                            {!! form_row($form_post->body,$options=['label'=>trans("blog::post_translation.fields.body.label")]) !!}
                        </div>
                        <div class="col-4">
                            <div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample6">
                                <div class="card">
                                    <div class="card-header" id="headingOne6">
                                        <div class="card-title" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                            <i class="flaticon-pie-chart-1"></i> Options
                                        </div>
                                    </div>
                                    <div id="collapseOne6" class="collapse show" aria-labelledby="headingOne6" data-parent="#accordionExample6">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    {!! form_row($form_post->published_at,$options = ['label'=>trans("blog::post_translation.fields.published_at.label"),'description'=>trans("blog::post_translation.fields.published_at.description"),'attr' => ['id' => 'published_at']]) !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    {!! form_row($form_post->unpublished_at,$options = ['label'=>trans("blog::post_translation.fields.unpublished_at.label"),'description'=>trans("blog::post_translation.fields.unpublished_at.description"),'attr' => ['id' => 'unpublished_at']]) !!}
                                                </div>
                                            </div>
                                            <div class="kt-wizard-v1__form">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        {!! form_row($form_post->online,$options=['label'=>trans("blog::post_translation.fields.online.label"),'description'=>trans("blog::post_translation.fields.online.description")]) !!}
                                                    </div>
                                                    <div class="col-xl-6">
                                                        {!! form_row($form_post->indexable,$options=['label'=>trans("blog::post_translation.fields.indexable.label"),'description'=>trans("blog::post_translation.fields.online.description")]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingOne8">
                                        <div class="card-title" data-toggle="collapse" data-target="#collapseOne8" aria-expanded="false" aria-controls="collapseOne8">
                                            <i class="fas fa-tags"></i> Tags et catégories
                                        </div>
                                    </div>
                                    <div id="collapseOne8" class="collapse show" aria-labelledby="headingOne8" data-parent="#accordionExample6">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xl-12">

                                                    {!! form_row($form_post->tags,$options=['attr' => ['class'=>'form-control kt-select2','multiple' => 'multiple','id'=>'tags']]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingOne7">
                                        <div class="card-title" data-toggle="collapse" data-target="#collapseOne7" aria-expanded="false" aria-controls="collapseOne7">
                                            <i class="flaticon2-image-file"></i> Image de ûne
                                        </div>
                                    </div>
                                    <div id="collapseOne7" class="collapse show" aria-labelledby="headingOne7" data-parent="#accordionExample6">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    @include('blog::application.posts.components.media_form')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        {!! form_row($form_post->submit,$options=['label'=>"Valider",'attr' => ['class' => 'btn btn-primary']]) !!}
                    </div>
                </div>
            </div>
            {!! form_end($form_post,false) !!}
        </div>
    </div>
@endsection
