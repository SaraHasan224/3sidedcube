@extends('layouts.master')

@section('page_title',env('APP_NAME').' - Post Management')
@section('parent_module_breadcrumb_title','Post')

@section('parent_module_icon','lnr-license')
@section('parent_module_title','Post Management')

@section('has_child_breadcrumb_section', true)
{{--@section('has_child_breadcrumb_actions', true)--}}

@section('child_module_icon','icon-breadcrumb')
@section('child_module_breadcrumb_title','Post')
{{--@section('sub_child_module_icon','icon-breadcrumb')--}}
{{--@section('sub_child_module_breadcrumb_title','Users')--}}

@section('has_child_breadcrumb_actions')
    <div class="page-title-actions">
        <div class="d-inline-block pr-3">
            <button class="btn btn-primary fright listing-btns-wrap clear-pagination-state" type="button"
                    onclick="location.href='{{ URL::to('/post-create') }}'">
                <i class="icon-add"></i>
                <span>Create Post</span>
            </button>
        </div>
        <div class="d-inline-block pr-3 actionBtnWrap">
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Actions
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item"
                       data-backdrop="static"
                       data-keyboard="false"
                       onClick="App.Post.initializeBulkDelete();"
                       href="#">
                        <i class="fas fa-tags"></i>
                        <span>Delete Selected</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('content')
    {{--<!-- FILTERS VIEW STARTS HERE -->--}}
    @include('post.filters')
    {{--<!-- FILTERS VIEW ENDS HERE -->--}}
    <div class="main-card mb-3 card">
        <div class="card-body">
            <table style="width: 100%;" id="posts_table" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>S#</th>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('post.script')
@endsection


