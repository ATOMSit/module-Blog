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
    <script src="{{asset('application/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
    {!! $html->scripts() !!}
    <script type="application/javascript">
        function delete_post(id) {
            var url = "/admin/blog/posts/destroy/" + id;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
            })

            swalWithBootstrapButtons.fire({
                title: 'Êtes-vous sûr ?',
                text: "Attention ! Toute suppression est irréversible.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    var frm = document.getElementById('atomsit_delete_form') || null;
                    if (frm) {
                        frm.action = url;
                        frm.submit();
                    }
                }
            })
        }
    </script>
@endpush

@section('content')
    <form method="POST" id="atomsit_delete_form" action="" style="display: none">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="checkbox" name="validation" checked>
    </form>
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
