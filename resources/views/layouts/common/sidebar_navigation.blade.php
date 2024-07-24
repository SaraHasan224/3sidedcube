<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                        data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button"
                    class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Account Management</li>
                <li class="{{ (Request::is('dashboard') ||  Request::is('/')) ? 'mm-active' : '' }}">
                    <a href="{{ URL::to('/') }}">
                        <i class="metismenu-icon pe-7s-display2">
                        </i>Dashboard
                    </a>
                </li>
                <li class="{{ (Request::is('profile.edit') ||  Request::is('/profile')) ? 'mm-active' : '' }}">
                    <a href="{{ URL::to('/profile') }}">
                        <i class="metismenu-icon pe-7s-display2">
                        </i>Profile
                    </a>
                </li>
                <li class="{{ (Request::is('users')) ? 'mm-active' : '' }}">
                    <a href="{{ URL::to('/users') }}">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Users
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ Request::is('users') ? 'mm-active' : '' }}">
                        <li>
                            <a href="{{ URL::to('/users') }}">
                                <i class="metismenu-icon">
                                </i>View All
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ (Request::is('roles') ||  Request::is('/roles')) ? 'mm-active d-none' : ' d-none' }}">
                    <a href="{{ URL::to('/roles') }}">
                        <i class="metismenu-icon pe-7s-safe"></i>
                        Roles
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{
                     (Request::is('roles') ||  Request::is('/roles')) ||
                     (Request::is('roles.add') ||  Request::is('/roles/add'))
                     ? 'mm-active' : '' }}">
                        <li>
                            <a href="{{ URL::to('/roles') }}">
                                <i class="metismenu-icon">
                                </i>View All
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="app-sidebar__heading">Manage Application</li>


                <li class="{{ (Request::is('post') ||  Request::is('/post')) ? 'mm-active' : '' }}">
                    <a href="{{ URL::to('/post') }}">
                        <i class="metismenu-icon pe-7s-safe"></i>
                        Post
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{
                     (Request::is('post') ||  Request::is('/post')) ||
                     (Request::is('post.add') ||  Request::is('/post/add'))
                     ? 'mm-active' : '' }}">
                        <li>
                            <a href="{{ URL::to('/post') }}">
                                <i class="metismenu-icon">
                                </i>View All
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>