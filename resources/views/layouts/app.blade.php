<!DOCTYPE html>

@if (\Request::is('rtl'))
    <html dir="rtl" lang="ar">
    @else
        <html lang="en">
        @endif

        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            @if (env('IS_DEMO'))
                <x-demo-metas></x-demo-metas>
            @endif

            <title>Soft UI Dashboard by Creative Tim</title>

            <!-- ✅ Core Icons & Fonts -->
            <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
            <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
            <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
            <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
            <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet" />

            <!-- ✅ Global jQuery + DataTables (load first!) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

            <!-- ✅ Toastr + SweetAlert2 -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
            <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <!-- ✅ Soft-UI CSS -->
            <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
            <link id="pagestyle" href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
        </head>

        <body class="g-sidenav-show bg-gray-100 {{ (\Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '')) }}">

        @auth
            @yield('auth')
        @endauth
        @guest
            @yield('guest')
        @endguest

        @if (session('success') || session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed bottom-0 end-0 p-3';
                    toast.style.zIndex = '9999';
                    toast.innerHTML = `
                <div class="toast align-items-center text-white {{ session('success') ? 'bg-gradient-success' : 'bg-gradient-danger' }} border-0 show" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') ?? session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 4000);
                });
            </script>
        @endif

        <!-- ✅ Core Soft-UI JS -->
        <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
        <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>

        <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
            }
        </script>

        <!-- ✅ GitHub Buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <!-- ✅ Notification Sound + Check -->
        <script>
            let lastNotificationId = localStorage.getItem('last_notification_id') || 0;
            const sound = new Audio('/sounds/notify.wav');
            sound.volume = 0.7;
            document.addEventListener('click', () => {
                sound.play().then(() => sound.pause());
                console.log('🔊 Audio unlocked');
            }, { once: true });

            async function checkNotifications() {
                try {
                    const res = await fetch('/notifications/check');
                    const text = await res.text();
                    const clean = text.replace(/^\[DEBUG\][\s\S]*?\{/, '{');
                    const data = JSON.parse(clean);
                } catch (err) {
                    console.warn('Notification check skipped (non-JSON or debug text):', err);
                }
            }
            setInterval(checkNotifications, 15000);
        </script>

        {{-- ✅ Global check to verify jQuery is loaded --}}
        <script>
            window.addEventListener('load', () => {
                console.log("✅ [Soft-UI Global] jQuery version:", $.fn.jquery);
            });
        </script>

        {{-- ✅ Page-level scripts --}}
        @stack('scripts')

        </body>
        </html>
