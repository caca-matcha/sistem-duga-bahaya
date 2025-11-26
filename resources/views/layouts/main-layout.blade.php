<x-app-layout>
    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Page Content -->
        <main class="flex-1 p-6">
            @yield('main-content')
        </main>
    </div>
</x-app-layout>
