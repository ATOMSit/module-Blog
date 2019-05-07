@extends('application.layouts.app')

@section('title')
    Index blog
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('blog.admin.post.index') }}
@endsection

@push('styles')
    <link href="{{asset('application/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet"
          type="text/css"/>
@endpush

@push('scripts')
    <script src="{{asset('application/vendors/custom/datatables/datatables.bundle.js')}}"
            type="text/javascript"></script>
    {!! $html->scripts() !!}
@endpush

@section('content')
    @widget('AdvicesWidget', ['plugin' => 'blog'])
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
			        <span class="kt-portlet__head-icon">
				        <i class="kt-font-brand flaticon2-list-2"></i>
			        </span>
                    <h3 class="kt-portlet__head-title">
                        Liste des articles
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{route('blog.admin.post.create')}}" class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-plus"></i>
                                Ajouter un article
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                {!! $html->table(['class' => 'table table-striped- table-bordered table-hover table-checkable'], false) !!}
            </div>
        </div>
    </div>
@endsection
