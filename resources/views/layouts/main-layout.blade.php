@extends('layouts.app')

@section('content')
<div class="flex">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Page Content -->
    <main class="flex-1 p-6">
        @yield('main-content')
    </main>
</div>
@endsection
