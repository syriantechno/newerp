{{--@extends('layouts.app')--}}

{{--@section('auth')--}}
{{--    @include('layouts.alerts')--}}

{{--    @if(\Request::is('static-sign-up'))--}}
{{--        @include('layouts.navbars.guest.nav')--}}
{{--        @yield('content')--}}
{{--        @include('layouts.footers.guest.footer')--}}

{{--    @elseif (\Request::is('static-sign-in'))--}}
{{--        @include('layouts.navbars.guest.nav')--}}
{{--        @yield('content')--}}
{{--        @include('layouts.footers.guest.footer')--}}

{{--    @else--}}
{{--        @include('layouts.navbars.auth.sidebar')--}}
{{--        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">--}}
{{--            @include('layouts.navbars.auth.nav')--}}
{{--            <div class="container-fluid py-4">--}}
{{--                @yield('content')--}}
{{--                @include('layouts.footers.auth.footer')--}}
{{--            </div>--}}
{{--        </main>--}}
{{--        @include('components.fixed-plugin')--}}
{{--    @endif--}}
{{--    @stack('scripts')--}}
{{--@endsection--}}

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
                {{-- PJAX Content Container --}}
                <div id="main-content">
                    @yield('content')
                </div>

                @include('layouts.footers.auth.footer')
            </div>
        </main>

        @include('components.fixed-plugin')
    @endif
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- ====== Stack for page-level scripts ====== --}}
    @stack('scripts')

    {{-- ========================================= --}}
    {{-- PJAX Loader to keep sidebar static        --}}
    {{-- ========================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-pjax@2.0.1/jquery.pjax.min.js"></script>

    <script>
        $(document).ready(function() {
            // Activate PJAX for all internal sidebar links
            $(document).pjax('.nav-link[href^="/"], .nav-link[href^="{{ url('') }}"]', '#main-content', {
                timeout: 4000,
                fragment: '#main-content'
            });

            // Scroll to top after PJAX load
            $(document).on('pjax:end', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // Prevent PJAX for external links or ones with no-pjax class
            $(document).on('click', '.nav-link.no-pjax', function(e) {
                e.preventDefault();
                window.location.href = $(this).attr('href');
            });
        });
    </script>

@endsection







