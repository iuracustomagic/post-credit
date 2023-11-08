@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        @if($user->role_id == 3)
        <li>
            <span><b>Ваш персональный менеджер: </b></span>
            <span>{{$user->leaderManager}}</span>
        </li>
        @elseif($user->role_id == 4)
            <li>
                <span><b>Ваш персональный менеджер: </b></span>
                <span>{{$user->salesmanManager}}</span>
            </li>
        @endif
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item mt-1 mr-4">
            <p>{{$user->first_name.' '.$user->last_name}}</p>
        </li>
        <li  class="nav-item dropdown mt-1 mr-4">
            <a href="{{route('logout')}}"> <svg xmlns="http://www.w3.org/2000/svg" height="1.2em" viewBox="0 0 512 512">
                    <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z"/></svg>
            </a>
            </li>


    </ul>
</nav>
<!-- /.navbar -->
