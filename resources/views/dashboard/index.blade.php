@extends('layouts.master')

@section('page_title',env('APP_NAME').' - Users')

@section('parent_module_icon','lnr-apartment')
@section('parent_module_title','Dashboard')

@section('content')
    <div class="tabs-animation">
        <div class="card no-shadow bg-transparent no-border rm-borders mb-3">
            <div class="card">
                <div class="no-gutters row">
                    <div class="col-md-12 col-lg-4">
                        <ul class="list-group list-group-flush">
                            <li class="bg-transparent list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Total Users</div>
                                                <div class="widget-subheading">Admin User Count</div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers text-success">{{$stats['user_count']}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="bg-transparent list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Post</div>
                                                <div class="widget-subheading">Total Post Count</div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers text-primary">{{ $stats['post_count'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection