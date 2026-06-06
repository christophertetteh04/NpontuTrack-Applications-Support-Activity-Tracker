<nav class="bg-white border-b border-gray-200 px-6 py-3">
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="font-semibold text-gray-900">NpontuTrack</a>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
            <a href="{{ route('reports.daily') }}" class="text-gray-600 hover:text-gray-900">Daily Summary</a>
            <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900">Reports</a>
        </div>
    </div>
</nav>
