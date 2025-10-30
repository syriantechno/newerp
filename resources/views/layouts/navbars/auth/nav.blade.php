<!-- Navbar -->
@php echo '<!-- bell test -->'; @endphp


<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                </li>
                <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">
                    {{ str_replace('-', ' ', Request::path()) }}
                </li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-capitalize">{{ str_replace('-', ' ', Request::path()) }}</h6>
        </nav>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">

            <!-- Search -->
            <div class="ms-md-3 pe-md-3 d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text text-body"><i class="fa fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Type here...">
                </div>
            </div>

            <ul class="navbar-nav justify-content-end">

                <!-- Notifications -->
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    @php
                        $notifs = \DB::table('notifications')
                            ->where('user_id', auth()->id())
                            ->orderByDesc('created_at')
                            ->limit(5)
                            ->get();
                        $unread = $notifs->where('is_read', false)->count();
                    @endphp

                    <a href="javascript:;" class="nav-link text-body p-0" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell cursor-pointer" style="font-size:20px; color:#566273;"></i>


                        @if($unread > 0)
                            <span class="badge bg-danger badge-sm position-absolute top-0 end-0 translate-middle">{{ $unread }}</span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="notifDropdown" style="min-width: 300px;">
                        @forelse($notifs as $n)
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="{{ $n->url ?? '#' }}">
                                    <div class="d-flex py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">{{ $n->title }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li><p class="text-center text-secondary m-2">No notifications</p></li>
                        @endforelse
                            <li><hr class="dropdown-divider my-2"></li>
                            <li class="text-center">
                                <form action="{{ route('notifications.read') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-success text-xs p-0 m-0">Mark all as read</button>
                                </form>
                                <span class="mx-2">|</span>
                                <form action="{{ route('notifications.delete') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger text-xs p-0 m-0">Delete all</button>
                                </form>
                                <span class="mx-2">|</span>
                                <a href="{{ route('notifications.index') }}" class="text-primary text-xs">View all</a>
                            </li>

                    </ul>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown ps-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/img/team-2.jpg') }}" class="avatar avatar-sm me-2" alt="user-avatar">
                        <span class="d-sm-inline d-none">{{ auth()->user()->name ?? 'User' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <i class="fa fa-user me-2 text-primary"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="{{ url('/logout') }}">
                                <i class="fa fa-sign-out-alt me-2 text-danger"></i> Sign Out
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Mobile Toggle -->
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
