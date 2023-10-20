@php
    use Illuminate\Support\Facades\Auth;
    $current_route = request()->route()->getName();
    $user = Auth::user();
@endphp

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('statistic')}}" class="brand-link ml-3 mb-4">

        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{route('statistic')}}" class="nav-link {{$current_route == 'statistic' ? 'active':''}}">
                        <i class="nav-icon fas fa-tachometer-alt "></i>
                        <p>Статистика</p>
                    </a>
                </li>

                @if($user->role_id == 1 || $user->role_id == 2)
                <li class="nav-item menu-close">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Партнеры
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item bg-secondary">
                            <a href="{{route('company.index')}}" class="nav-link {{$current_route == 'company.index' ? 'bg-primary':''}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16">
                                    <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022ZM6 8.694 1 10.36V15h5V8.694ZM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15Z"/>
                                    <path d="M2 11h1v1H2v-1Zm2 0h1v1H4v-1Zm-2 2h1v1H2v-1Zm2 0h1v1H4v-1Zm4-4h1v1H8V9Zm2 0h1v1h-1V9Zm-2 2h1v1H8v-1Zm2 0h1v1h-1v-1Zm2-2h1v1h-1V9Zm0 2h1v1h-1v-1ZM8 7h1v1H8V7Zm2 0h1v1h-1V7Zm2 0h1v1h-1V7ZM8 5h1v1H8V5Zm2 0h1v1h-1V5Zm2 0h1v1h-1V5Zm0-2h1v1h-1V3Z"/>
                                </svg>
                                <p class="ml-1">Юридические лица</p>
                            </a>
                        </li>
                        <li class="nav-item bg-secondary">
                            <a href="{{route('division.index')}}" class="nav-link {{$current_route == 'division.index' ? 'bg-primary':''}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                                    <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z"/>
                                </svg>
                                <p class="ml-1">Торговые точки</p>
                            </a>
                        </li>
                        <li class="nav-item bg-secondary">
                            <a href="{{route('salesman.index')}}" class="nav-link {{$current_route == 'salesman.index' ? 'bg-primary':''}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                </svg>
                                <p class="ml-1">Продавцы</p>
                            </a>
                        </li>
                        @if($user->role_id == 1 )
                        <li class="nav-item bg-secondary">
                            <a href="{{route('manager.index')}}" class="nav-link {{$current_route == 'manager.index' ? 'bg-primary':''}}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Менеджеры</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    @if($user->role_id == 2)
                    <a href="{{route('profile.manager')}}" class="nav-link {{$current_route == 'profile.manager' ? 'active':''}}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Личный кабинет</p>
                    </a>
                        @elseif($user->role_id == 3)
                        <a href="{{route('profile.leader')}}" class="nav-link {{$current_route == 'profile.leader' ? 'active':''}}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Личный кабинет</p>
                        </a>
                        @elseif($user->role_id == 4)
                            <a href="{{route('order.index')}}" class="nav-link {{$current_route == 'order.index' ? 'active':''}}">
                                <i class="nav-icon far fa-plus-square"></i>
                                <p>Отправить заявку</p>
                            </a>
                        @endif
                </li>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
