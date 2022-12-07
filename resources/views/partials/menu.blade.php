<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    {{-- Brand Logo --}}
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    {{-- Sidebar --}}
    <div class="sidebar">
        {{-- Sidebar user (optional) --}}
        {{-- Sidebar Menu --}}
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{-- <li>
                    <select class="searchable-field form-control">
                    </select>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is("admin/dashboard") || request()->is("admin/dashboard/*") ? "active" : "" }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-fw fa-user nav-icon">
                        </i>
                        <p>
                            {{ trans('crud.users.title') }}
                        </p>
                    </a>
                </li>
                {{-- @can('user_management_access') --}}
                    <li class="nav-item has-treeview {{ request()->is("admin/cities*") ? "menu-open" : "" }} {{ request()->is("admin/countries*") ? "menu-open" : "" }} {{ request()->is("admin/languages*") ? "menu-open" : "" }} {{ request()->is("admin/tags*") ? "menu-open" : "" }} {{ request()->is("admin/zipcodes*") ? "menu-open" : "" }} {{ request()->is("admin/pricing-plans*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-check"></i>
                            <p>
                                {{ trans('global.masters') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('read-language')
                                <li class="nav-item">
                                    <a href="{{ route('admin.languages.index') }}" class="nav-link {{ request()->is("admin/languages") || request()->is("admin/languages/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-check"></i>
                                        <p>
                                            {{ trans('crud.languages.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('read-country')
                                <li class="nav-item">
                                    <a href="{{ route('admin.countries.index') }}" class="nav-link {{ request()->is("admin/countries") || request()->is("admin/countries/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-check"></i>
                                        <p>
                                            {{ trans('crud.countries.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('read-city')
                                <li class="nav-item">
                                    <a href="{{ route('admin.cities.index') }}" class="nav-link {{ request()->is("admin/cities") || request()->is("admin/cities/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-check"></i>
                                        <p>
                                            {{ trans('crud.cities.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('read-tag')
                                <li class="nav-item">
                                    <a href="{{ route('admin.tags.index') }}" class="nav-link {{ request()->is("admin/tags") || request()->is("admin/tags/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-check"></i>
                                        <p>
                                            {{ trans('crud.tags.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('read-zipcode')
                                <li class="nav-item">
                                    <a href="{{ route('admin.zipcodes.index') }}" class="nav-link {{ request()->is("admin/zipcodes") || request()->is("admin/zipcodes/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-check"></i>
                                        <p>
                                            {{ trans('crud.zipcodes.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('read-pricing-plan')
                                <li class="nav-item">
                                    <a href="{{ route('admin.pricing-plans.index') }}" class="nav-link {{ request()->is("admin/pricing-plans") || request()->is("admin/pricing-plans/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-check"></i>
                                        <p>
                                            {{ trans('crud.pricing_plans.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                {{-- @endcan --}}

                {{-- @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif --}}
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        {{-- /.sidebar-menu --}}
    </div>
    {{-- /.sidebar --}}
</aside>
