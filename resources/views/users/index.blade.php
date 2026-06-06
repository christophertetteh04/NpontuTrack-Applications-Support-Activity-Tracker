@extends('layouts.app')
@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('page-actions')
    <a href="{{ route('users.create') }}"
       class="text-sm bg-brand-600 hover:bg-brand-700 text-white px-4 py-1.5 rounded-lg transition">+ Add User</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 text-xs font-mono text-gray-400 uppercase tracking-wider">
                <th class="px-5 py-3 text-left">Name</th>
                <th class="px-5 py-3 text-left">Employee ID</th>
                <th class="px-5 py-3 text-left">Email</th>
                <th class="px-5 py-3 text-left">Department</th>
                <th class="px-5 py-3 text-left">Role</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 text-xs font-bold uppercase">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <span class="font-medium text-gray-900">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-5 py-3 font-mono text-gray-500 text-xs">{{ $user->employee_id }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $user->department }}</td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full
                        @if($user->role === 'admin') bg-red-100 text-red-700
                        @elseif($user->role === 'team_lead') bg-amber-100 text-amber-700
                        @else bg-sky-100 text-sky-700 @endif">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <a href="{{ route('users.edit', $user) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
