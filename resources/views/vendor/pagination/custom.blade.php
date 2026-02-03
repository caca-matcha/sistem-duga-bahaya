@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between mt-6">
        {{-- Mobile View --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default leading-5 rounded-full shadow-sm">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 leading-5 rounded-full shadow-sm hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-300 transition-all duration-200">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-600 bg-white border border-gray-200 leading-5 rounded-full shadow-sm hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-300 transition-all duration-200">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default leading-5 rounded-full shadow-sm">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    {!! __('Showing') !!}
                    <span class="font-bold text-gray-800">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-bold text-gray-800">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-bold text-gray-800">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex items-center gap-1 shadow-none rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-300 bg-transparent cursor-default rounded-full leading-5 transition-colors"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white hover:bg-indigo-50 hover:text-indigo-600 rounded-full leading-5 focus:z-10 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200 transform hover:-translate-x-0.5"
                            aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-transparent cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            <div class="flex items-center bg-white rounded-full p-1 shadow-sm border border-gray-100 mx-1">
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page">
                                            <span
                                                class="relative inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white bg-indigo-600 rounded-full shadow-md transform scale-105 cursor-default leading-5 transition-all duration-200">
                                                {{ $page }}
                                            </span>
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="relative inline-flex items-center justify-center w-8 h-8 text-sm font-medium text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full leading-5 focus:z-10 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200"
                                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white hover:bg-indigo-50 hover:text-indigo-600 rounded-full leading-5 focus:z-10 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200 transform hover:translate-x-0.5"
                            aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-300 bg-transparent cursor-default rounded-full leading-5 transition-colors"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif