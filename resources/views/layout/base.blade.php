<div class="d-flex flex-column min-vh-100">
    @include('components.header')

    <main class="flex-grow-1">
        @yield('main_content')
    </main>

    @include('components.footer')
</div>