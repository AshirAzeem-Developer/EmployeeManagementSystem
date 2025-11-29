<div class="flex flex-col w-64 bg-slate-900 text-white shadow-xl min-h-screen">
    {{-- Logo / Brand --}}
    <div class="flex items-center justify-center h-16 bg-slate-950 shadow-md">
        <span class="text-xl font-bold tracking-wider text-primary-400">EMS</span>
        <span class="ml-2 text-lg font-medium text-white">Portal</span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Employee Tools --}}
        <div class="mt-6 mb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Employee
        </div>
        
        {{-- Attendance --}}
        <a href="{{ route('attendance.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('attendance.*') ? 'bg-primary-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('attendance.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            My Attendance
        </a>

        {{-- Admin Section --}}
        @if (auth()->check() && auth()->user()->role === 'admin')
            <div class="mt-6 mb-2 px-3 text-xs font-semibold text-primary-400 uppercase tracking-wider">
                Admin Panel
            </div>

            {{-- Users --}}
            <a href="{{ route('admin.users.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-primary-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Employees
            </a>

            {{-- Shifts --}}
            <a href="{{ route('admin.shifts.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.shifts.*') ? 'bg-primary-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('admin.shifts.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Shifts
            </a>

            {{-- Departments --}}
            <a href="{{ route('admin.departments.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.departments.*') ? 'bg-primary-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('admin.departments.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Departments
            </a>

            {{-- Holidays --}}
            <a href="{{ route('admin.holidays.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.holidays.*') ? 'bg-primary-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('admin.holidays.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Holidays
            </a>
        @endif

    </nav>

    {{-- User Profile (Bottom) --}}
    <div class="border-t border-slate-800 p-4 bg-slate-950">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
    </div>
</div>
