<x-app-layout>
    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        @include('layouts.topbar')

        <!-- Page Content -->
        <main class="flex-1 p-6">
            @yield('main-content')
        </main>
    </div>
</x-app-layout>
