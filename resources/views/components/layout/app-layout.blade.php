<x-layout.head />

<input type="checkbox" id="app-sidebar-toggle" class="peer sr-only" aria-hidden="true" />

<label
    for="app-sidebar-toggle"
    class="fixed inset-0 z-30 bg-slate-900/50 opacity-0 pointer-events-none backdrop-blur-[2px] transition-opacity duration-200 ease-out peer-checked:opacity-100 peer-checked:pointer-events-auto dark:bg-slate-950/70 lg:hidden"
>
    <span class="sr-only">{{ __('Close menu') }}</span>
</label>

<x-layout.app-sidebar />

<div class="flex min-h-screen w-full flex-col lg:pl-64">
    <x-layout.app-topbar />
    <main id="content" class="flex-1 p-4 sm:p-6 lg:p-8" tabindex="-1">
        {{ $slot }}
    </main>
</div>

<x-layout.app-close />
