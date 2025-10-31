<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>

@if (\Request::is('rtl'))
    <html dir="rtl" lang="ar">
    @else
        <html lang="en" >
        @endif

        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            @if (env('IS_DEMO'))
                <x-demo-metas></x-demo-metas>
            @endif

            <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
            <link rel="icon" type="image/png" href="../assets/img/favicon.png">
            <title>
                Soft UI Dashboard by Creative Tim
            </title>
            <!--     Fonts and icons     -->
            <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
            <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
            <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
            <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
            <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet" />
            <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
            <link id="pagestyle" href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />


        </head>

        <body class="g-sidenav-show  bg-gray-100 {{ (\Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '')) }} ">
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
                <div class="toast align-items-center text-white {{ session('success') ? 'bg-gradient-success' : 'bg-gradient-danger' }} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') ?? session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
`;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 4000);
                });
            </script>
        @endif

        <!--   Core JS Files   -->
        <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
        <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>

        @stack('rtl')
        @stack('dashboard')
        <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                    damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
        </script>

        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->



        @if (session('success') || session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const body = document.querySelector('body');
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed top-2 end-0 p-3';
                    toast.style.zIndex = '9999';
                    toast.innerHTML = `
    <div class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          {{ session('success') ?? session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                </div>
`;
                    body.appendChild(toast);
                    setTimeout(() => toast.remove(), 4000);
                });
            </script>
        @endif
        <script>
            let lastNotificationId = localStorage.getItem('last_notification_id') || 0;
            const sound = new Audio('/sounds/notify.wav');
            sound.volume = 0.7;

            // Unlock audio on first user action
            document.addEventListener('click', () => {
                sound.play().then(() => sound.pause());
                console.log('🔊 Audio unlocked');
            }, { once: true });

             async function checkNotifications() {
                try {
                    const res = await fetch('/notifications/check');
                    const text = await res.text();

                    // نحذف أي أسطر Debug قبل JSON
                    const clean = text.replace(/^\[DEBUG\][\s\S]*?\{/, '{');
                    const data = JSON.parse(clean);

                    // أكمل منطقك هنا
                    // console.log('Notifications:', data);
                } catch (err) {
                    console.warn('Notification check skipped (non-JSON or debug text):', err);
                }
            }
            setInterval(checkNotifications, 15000);


            setInterval(checkNotifications, 5000);
        </script>




        </body>

        </html>
