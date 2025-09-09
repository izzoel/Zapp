<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    @include('auth.layout.header')
</head>

<body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('auth.layout.sidebar', ['semuaMenu' => \App\Models\Menu::orderBy('urutan')->get()])
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('auth.layout.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    {{-- @yield(Route::currentRouteName() ? Str::replace('.', '-', Route::currentRouteName()) : 'content') --}}
                    {{-- @livewire('backend.' . request()->segment(1)) --}}
                </div>

                <!-- / Content -->

                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">developed by <a href="https://izzoel.github.io/" class="footer-link fw-bolder">zetware.id</a> @2025</div>
                    </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
    </div>
    <div id="spinner" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(84, 84, 84, 0.3); z-index: 9999; display: none;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="text-dark" style="animation: none; font-size: 1rem; display: inline-block; vertical-align: middle;">
                <span style="animation: dot 1.5s infinite; display: inline-block;">Lagi</span>
                <span style="animation: dot 1.5s infinite .2s; display: inline-block;">ngimport </span>
                <span style="animation: dot 1.5s infinite .4s; display: inline-block;">.</span>
                <span style="animation: dot 1.5s infinite .6s; display: inline-block;">.</span>
                <span style="animation: dot 1.5s infinite .8s; display: inline-block;">.</span>


                <style>
                    @keyframes dot {
                        0% {
                            transform: translateX(0);
                        }

                        50% {
                            transform: translateX(5px);
                        }

                        100% {
                            transform: translateX(0);
                        }
                    }
                </style>
            </p>
        </div>
    </div>


    <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    @include('auth.layout.footer')

</body>

</html>
