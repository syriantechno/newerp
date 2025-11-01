@extends('layouts.app')

@section('auth')
    @include('layouts.alerts')

    @if(\Request::is('static-sign-up'))
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')

    @elseif (\Request::is('static-sign-in'))
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')

    @else
        {{-- ====== Authenticated Layout ====== --}}
        @include('layouts.navbars.auth.sidebar')

        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
            @include('layouts.navbars.auth.nav')

            <div class="container-fluid py-4">
                <div id="main-content">
                    @yield('content')
                </div>
                @include('layouts.footers.auth.footer')
            </div>
        </main>

        @include('components.fixed-plugin')
    @endif



    {{-- 🧩 Disable PJAX completely (to prevent console errors) --}}
    <script>
        if (typeof $.fn.pjax === "undefined") {
            $.fn.pjax = function() { return this; };
        }
    </script>

    {{-- ==========================================================
 | Global JS/CSS for all Soft-UI Tables (unified setup)
 |========================================================== --}}
    <!-- ✅ jQuery must load first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ DataTables Core + Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- ✅ SweetAlert2 + Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <!-- ✅ Optional: Small delay to ensure jQuery is ready -->
    <script>
        window.addEventListener('load', () => {
            console.log("✅ [Soft-UI Global] jQuery loaded version:", $.fn.jquery);
        });
    </script>


    {{-- ====== Stack for page-level scripts ====== --}}


    {{-- Optional: small debug print to confirm --}}
    <script>console.log("✅ [Soft-UI Auth Layout] Ready — jQuery version:", $.fn.jquery);</script>
    @stack('scripts')
@endsection
