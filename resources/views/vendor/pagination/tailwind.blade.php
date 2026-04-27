@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <div class="flex items-center justify-between gap-2 sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="inline-flex cursor-not-allowed items-center rounded-xl border border-slate-200/80 bg-slate-50 px-4 py-2 text-sm font-medium leading-5 text-slate-400 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-500">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    class="inline-flex items-center rounded-xl border border-slate-200/80 bg-white px-4 py-2 text-sm font-medium leading-5 text-slate-700 transition-colors hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:border-white/10 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-white/15 dark:hover:bg-slate-700/80 dark:focus-visible:ring-offset-slate-950"
                >
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    class="inline-flex items-center rounded-xl border border-slate-200/80 bg-white px-4 py-2 text-sm font-medium leading-5 text-slate-700 transition-colors hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:border-white/10 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-white/15 dark:hover:bg-slate-700/80 dark:focus-visible:ring-offset-slate-950"
                >
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex cursor-not-allowed items-center rounded-xl border border-slate-200/80 bg-slate-50 px-4 py-2 text-sm font-medium leading-5 text-slate-400 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-500">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between sm:gap-4">

            <div>
                <p class="text-sm leading-5 text-slate-600 dark:text-slate-400">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium text-slate-900 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium text-slate-900 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium text-slate-900 dark:text-slate-200">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex overflow-hidden rounded-xl shadow-sm shadow-slate-900/5 ring-1 ring-slate-200/80 dark:shadow-none dark:ring-white/10 rtl:flex-row-reverse">

                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="inline-flex cursor-not-allowed items-center bg-slate-50 px-2 py-2 text-sm font-medium leading-5 text-slate-400 dark:bg-slate-800/80 dark:text-slate-500"
                                aria-hidden="true"
                            >
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a
                            href="{{ $paginator->previousPageUrl() }}"
                            rel="prev"
                            class="inline-flex items-center bg-white px-2 py-2 text-sm font-medium leading-5 text-slate-500 transition-colors hover:bg-slate-50 hover:text-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                            aria-label="{{ __('pagination.previous') }}"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex cursor-default items-center border-l border-slate-200/80 bg-white px-4 py-2 text-sm font-medium leading-5 text-slate-500 dark:border-white/10 dark:bg-slate-900 dark:text-slate-400">{{ $element }}</span>
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex cursor-default items-center border-l border-slate-200/80 bg-brand-muted px-4 py-2 text-sm font-semibold leading-5 text-brand dark:border-white/10 dark:bg-brand/20 dark:text-indigo-200">{{ $page }}</span>
                                    </span>
                                @else
                                    <a
                                        href="{{ $url }}"
                                        class="inline-flex items-center border-l border-slate-200/80 bg-white px-4 py-2 text-sm font-medium leading-5 text-slate-700 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand dark:border-white/10 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                    >
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a
                            href="{{ $paginator->nextPageUrl() }}"
                            rel="next"
                            class="inline-flex items-center border-l border-slate-200/80 bg-white px-2 py-2 text-sm font-medium leading-5 text-slate-500 transition-colors hover:bg-slate-50 hover:text-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand dark:border-white/10 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                            aria-label="{{ __('pagination.next') }}"
                        >
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="inline-flex cursor-not-allowed items-center border-l border-slate-200/80 bg-slate-50 px-2 py-2 text-sm font-medium leading-5 text-slate-400 dark:border-white/10 dark:bg-slate-800/80 dark:text-slate-500"
                                aria-hidden="true"
                            >
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
