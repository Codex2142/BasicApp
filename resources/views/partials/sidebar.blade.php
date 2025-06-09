<div>
    <!-- Mobile Sidebar -->
    <div
        class="position-fixed top-0 start-0 z-50 h-100 bg-stone-200 shadow-lg p-3 w-75 d-md-none"
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        @click.outside="sidebarOpen = false"
    >
        @include('partials.sidebar-content')
    </div>

    <!-- Desktop Sidebar -->
    <div class="position-fixed col-md-2 bg-stone-200 shadow-sm d-none d-md-block p-3 h-100 bottom-0">
        @include('partials.sidebar-content')
    </div>
</div>
