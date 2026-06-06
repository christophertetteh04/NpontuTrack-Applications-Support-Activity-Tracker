<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — NpontuTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['IBM Plex Sans', 'sans-serif'], mono: ['IBM Plex Mono', 'monospace'] } } }
        }
    </script>
</head>
<body class="h-full bg-brand-900 font-sans antialiased flex" style="background: #111f47;">
    {{-- Left panel: branding --}}
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-14" style="background: linear-gradient(135deg, #1a306e 0%, #111f47 100%);">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded bg-blue-500 flex items-center justify-center text-white font-bold">NT</div>
                <span class="text-white font-semibold text-lg">NpontuTrack</span>
            </div>
        </div>
        <div>
            <blockquote class="text-white/70 text-2xl font-light leading-relaxed mb-6">
                "Visibility into every activity,<br>every shift, every handover."
            </blockquote>
            <div class="space-y-3">
                @foreach(['Daily activity tracking', 'Shift handover visibility', 'Historical reporting', 'Team accountability'] as $feat)
                <div class="flex items-center gap-3 text-white/60 text-sm">
                    <svg class="w-4 h-4 text-blue-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    {{ $feat }}
                </div>
                @endforeach
            </div>
        </div>
        <p class="text-white/20 font-mono text-xs">© {{ date('Y') }} Npontu Technologies. Applications Support Platform.</p>
    </div>

    {{-- Right panel: form --}}
    <div class="flex-1 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">
            <div class="lg:hidden mb-8 flex items-center gap-3">
                <div class="w-9 h-9 rounded bg-blue-600 flex items-center justify-center text-white font-bold">NT</div>
                <span class="font-semibold text-gray-900 text-lg">NpontuTrack</span>
            </div>

            <h2 class="text-2xl font-semibold text-gray-900 mb-1">Welcome back</h2>
            <p class="text-gray-500 text-sm mb-8">Sign in to the Applications Support Tracker</p>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 border @error('email') border-red-400 @else border-gray-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <label for="remember" class="text-sm text-gray-600">Remember me</label>
                </div>
                <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white py-2.5 rounded-lg text-sm font-medium transition">
                    Sign in
                </button>
            </form>

            <p class="mt-8 text-center text-xs text-gray-400 font-mono">Contact your administrator for account access</p>
        </div>
    </div>
</body>
</html>
