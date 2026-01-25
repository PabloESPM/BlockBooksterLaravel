@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black uppercase font-display">Users</h1>
        <div class="flex gap-2">
            <input type="text" placeholder="Search user..." class="neo-input w-64 bg-white text-sm">
            <button class="neo-btn-secondary px-4 py-2 text-sm">Search</button>
        </div>
    </div>

    <div class="bg-white border-2 border-black overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black text-white text-xs font-bold uppercase tracking-wider">
                    <th class="p-4 border-b border-gray-800">User</th>
                    <th class="p-4 border-b border-gray-800">Email</th>
                    <th class="p-4 border-b border-gray-800">Joined</th>
                    <th class="p-4 border-b border-gray-800">Status</th>
                    <th class="p-4 border-b border-gray-800 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/10">
                @for($i = 0; $i < 6; $i++)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-200 rounded-full border border-black overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name=User+{{$i}}&background=random"
                                        class="w-full h-full object-cover">
                                </div>
                                <span class="font-bold">Username_{{$i}}</span>
                            </div>
                        </td>
                        <td class="p-4 font-mono text-xs">user{{$i}}@example.com</td>
                        <td class="p-4 text-sm font-bold text-gray-500">Jan 24, 2026</td>
                        <td class="p-4">
                            @if($i == 2)
                                <span
                                    class="text-xs font-bold uppercase bg-red-100 text-red-800 px-2 py-1 border border-red-200">Banned</span>
                            @else
                                <span
                                    class="text-xs font-bold uppercase bg-green-100 text-green-800 px-2 py-1 border border-green-200">Active</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            @if($i == 2)
                                <button class="text-xs font-black uppercase text-gray-500 hover:underline">Unban User</button>
                            @else
                                <button class="text-xs font-black uppercase text-red-600 hover:underline">Ban User</button>
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection