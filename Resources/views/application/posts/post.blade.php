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
    <link href="{{asset('application/app/custom/wizard/wizard-v1.default.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')
    <script src="{{asset('application/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js')}}"
            type="text/javascript"></script>
    <script type="application/javascript">
        var KTWizard1 = function () {
            "use strict";
            var e, r, t;
            return {
                init: function () {
                    var i;
                    KTUtil.get("kt_wizard_v1"), e = $("#kt_form"), (t = new KTWizard("kt_wizard_v1", {startStep: 1})).on("beforeNext", function (e) {
                        !0 !== r.form() && e.stop()
                    }), t.on("change", function (e) {
                        setTimeout(function () {
                            KTUtil.scrollTop()
                        }, 500)
                    }),
                        r = e.validate({
                            ignore: ":hidden",
                            invalidHandler: function (e, r) {
                                KTUtil.scrollTop(), swal.fire({
                                    allowOutsideClick: false,
                                    title: "",
                                    text: "Le formulaire comporte des erreurs, veuillez les corriger avant de continuer.",
                                    type: "error",
                                    confirmButtonClass: "btn btn-principal"
                                })
                            }
                        }),
                        (i = e.find('[data-ktwizard-type="action-submit"]')).on("click", function (t) {
                            t.preventDefault(), r.form() && (KTApp.progress(i), e.ajaxSubmit({
                                success: function () {
                                    KTUtil.scrollTop(), swal.fire({
                                        allowOutsideClick: false,
                                        title: "Félécitation !",
                                        text: "Votre article a bien était publié",
                                        type: "success",
                                        confirmButtonClass: "btn btn-focus--pill--air",
                                        confirmButtonText: "Yes, delete it!"
                                    }).then(function (e) {
                                        window.location.href = '{{route('blog.admin.post.index')}}';
                                    })
                                }
                            }))
                        })
                }
            }
        }();
        var KTBootstrapTimepicker = {
            init: function () {
                $("#published_at_time, #unpublished_at_time").timepicker({
                    minuteStep: 1,
                    defaultTime: "",
                    showSeconds: !0,
                    showMeridian: !1,
                    snapToStep: !0
                })
            }
        };
        var KTBootstrapDatepicker = function () {
            var t;
            t = KTUtil.isRTL() ? {
                leftArrow: '<i class="la la-angle-right"></i>',
                rightArrow: '<i class="la la-angle-left"></i>'
            } : {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            };
            return {
                init: function () {
                    $("#published_at, #unpublished_at").datepicker({
                        rtl: KTUtil.isRTL(),
                        todayHighlight: !0,
                        format: 'dd/mm/yyyy',
                        orientation: "bottom left",
                    })
                }
            }
        }();
        jQuery(document).ready(function () {
            KTWizard1.init();
            KTBootstrapTimepicker.init();
            KTBootstrapDatepicker.init();
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

    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-grid  kt-wizard-v1 kt-wizard-v1--white" id="kt_wizard_v1" data-ktwizard-state="step-first">
                <div class="kt-grid__item">
                    <div class="kt-wizard-v1__nav">
                        <div class="kt-wizard-v1__nav-items">
                            <a class="kt-wizard-v1__nav-item" href="#" data-ktwizard-type="step"
                               data-ktwizard-state="current">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <i class="flaticon2-crisp-icons"></i>
                                    </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        @lang('blog::post_translation.views.tabs.tab1')
                                    </div>
                                </div>
                            </a>
                            <a class="kt-wizard-v1__nav-item" href="#" data-ktwizard-type="step">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <i class="flaticon-settings-1"></i>
                                    </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        @lang('blog::post_translation.views.tabs.tab2')
                                    </div>
                                </div>
                            </a>
                            <a class="kt-wizard-v1__nav-item" href="#" data-ktwizard-type="step">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <i class="flaticon-notes"></i>
                                    </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        @lang('blog::post_translation.views.tabs.tab3')
                                    </div>
                                </div>
                            </a>
                            @include('seobasic::application.posts.components.tab')
                        </div>
                    </div>
                </div>
                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper">
                    {!! form_start($form_post,$options = ['class' => 'kt-form','id'=>'kt_form']) !!}
                    <div class="kt-wizard-v1__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                        <div class="kt-heading kt-heading--md">
                            @lang('blog::post_translation.views.subtitles.content')
                        </div>
                        <div class="kt-form__section kt-form__section--first">
                            <div class="kt-wizard-v1__form">
                                {!! form_row($form_post->title,$options=['label'=>trans("blog::post_translation.fields.title.label"),'description'=>trans("blog::post_translation.fields.title.description")]) !!}
                                {!! form_row($form_post->body,$options=['label'=>trans("blog::post_translation.fields.body.label")]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
                        <div class="kt-heading kt-heading--md">
                            @lang('blog::post_translation.views.subtitles.hour')
                        </div>
                        <div class="kt-form__section kt-form__section--first">
                            <div class="kt-wizard-v1__form">
                                <div class="row">
                                    <div class="col-xl-6">
                                        {!! form_row($form_post->published_at,$options = ['label'=>trans("blog::post_translation.fields.published_at.label"),'description'=>trans("blog::post_translation.fields.published_at.description"),'attr' => ['id' => 'published_at']]) !!}
                                    </div>
                                    <div class="col-xl-6">
                                        {!! form_row($form_post->published_at_time,$options = ['label'=>trans("blog::post_translation.fields.published_at_time.label"),'description'=>trans("blog::post_translation.fields.published_at_time.description"),'attr' => ['id' => 'published_at_time']]) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        {!! form_row($form_post->unpublished_at,$options = ['label'=>trans("blog::post_translation.fields.unpublished_at.label"),'description'=>trans("blog::post_translation.fields.unpublished_at.description"),'attr' => ['id' => 'unpublished_at']]) !!}
                                    </div>
                                    <div class="col-xl-6">
                                        {!! form_row($form_post->unpublished_at_time,$options = ['label'=>trans("blog::post_translation.fields.unpublished_at_time.label"),'description'=>trans("blog::post_translation.fields.unpublished_at_time.description"),'attr' => ['id' => 'unpublished_at_time']]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-heading kt-heading--md">
                            @lang('blog::post_translation.views.subtitles.confidentiality')
                        </div>
                        <div class="kt-form__section kt-form__section--first">
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
                    @includeIf("blog::application.posts.components.media_form")
                    @include('seobasic::application.posts.components.content')
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
                            @lang('blog::post_translation.views.buttons.save')
                        </button>
                        <div class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
                            @lang('blog::post_translation.views.buttons.previous')
                        </div>
                        <div class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
                            @lang('blog::post_translation.views.buttons.next')
                        </div>
                    </div>
                    {!! form_end($form_post,false) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
