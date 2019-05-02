@extends('application.layouts.app')

@section('title')
    Index blog
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
    @widget('AdvicesWidget', ['module' => "blog"])
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-portlet__body">
                {!! $html->table(['class' => 'table table-striped- table-bordered table-hover table-checkable'], false) !!}
            </div>
        </div>
    </div>
@endsection