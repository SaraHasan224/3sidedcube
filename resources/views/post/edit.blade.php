@extends('layouts.master')
@section('page_title',env('APP_NAME').' - Manage Applicaton')
@section('parent_module_breadcrumb_title','Manage Applicaton')

@section('parent_module_icon','lnr-users')
@section('parent_module_title','Manage Applicaton')

@section('has_child_breadcrumb_section', true)
{{--@section('has_child_breadcrumb_actions', true)--}}

@section('child_module_icon','icon-breadcrumb')
@section('child_module_breadcrumb_title','Post')
@section('sub_child_module_icon','icon-breadcrumb')
@section('sub_child_module_breadcrumb_title','Edit')

@section('has_child_breadcrumb_actions')
@endsection

@section('content')
    <section class="content">
        <div class="box box-default">
            <!-- ALERTS STARTS HERE -->
            <section>
                <div class="row">
                    {{--@include('common.alerts')--}}
                </div>
            </section>
            <!-- ALERTS ENDS HERE -->
            <div class="box-body">
                <!-- /.row -->
                <section id="section1">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <form id="customer_edit_form" class="newFormContainer" method="post" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 formFieldsWrap">

                                        <div class="form-group">
                                            <label for="title">Title *</label>
                                            <input
                                                    type="text"
                                                    id="title"
                                                    name="title"
                                                    maxlength="255"
                                                    placeholder="Title"
                                                    class="form-control @error('title') is-invalid @enderror"
                                                    value="{{ old('title', $post->title ?? '') }}"
                                                    required
                                            >
                                            @error('title')
                                            <em id="title-error" class="is-invalid invalid-feedback error">{{ $message }}</em>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 formFieldsWrap">
                                        <div class="form-group">
                                            <label for="author">Author *</label>
                                            <input
                                                    type="text"
                                                    name="author"
                                                    maxlength="255"
                                                    placeholder="Author"
                                                    class="form-control @error('author') is-invalid @enderror"
                                                    value="{{ !empty(old('author')) ? old('author') : (!empty($post->author) ? $post->author : '') }}"
                                                    required
                                            >
                                            @error('author')
                                            <em id="author-error" class="error invalid-feedback">{{ $message }}</em>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 formFieldsWrap">
                                        <div class="form-group switchFromGrp">
                                            <span class="defaultLabel">Status</span>
                                            <div class="custom-control custom-switch product-purchase-checkbox">
                                                <input value="{{ !empty(old('is_active')) ? old('is_active') :  (!empty($post->status) ? $post->status : 1) }}"
                                                       type="checkbox"
                                                       @php
                                                           $param = !empty(old('is_active')) ? old('is_active') :  (!empty($post->status) ? $post->status : 0);
                                                           if($param == 1 ) {
                                                                echo 'checked="checked"';
                                                           }
                                                       @endphp
                                                       name="is_active"
                                                       class="custom-control-input"
                                                       id="chbox_is_active"
                                                />

                                                <label class="custom-control-label"
                                                       for="chbox_is_active"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 formFieldsWrap">
                                        <div class="form-group">
                                            <label for="content">Content *</label>
                                            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" required>
                                                {{ !empty(old('content')) ? old('content') : (!empty($post->content) ? $post->content : '') }}
                                            </textarea>
                                            @error('content')
                                            <em id="content-error" class="error invalid-feedback">{{ $message }}</em>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <div class="insideButtons">
                                            <button id="create-post" type="button" class="btn btn-primary text-right"><i class="icon-check-thin newMargin"></i>Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- /.box-body -->
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            App.Post.initializePostValidations();
            var postId = "{{ $post->id }}";
            App.Post.editFormBinding(postId);
            App.Helpers.getPhoneInput('create_phone', 'create_country_code', true, countryCode, phoneNumber)
        })
    </script>
@endsection
