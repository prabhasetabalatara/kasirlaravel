@props(['type' => 'success', 'message' => ''])

@php
$colors = [
    'success' => 'bg-green-100 border-green-400 text-green-700',
    'error' => 'bg-red-100 border-red-400 text-red-700',
    'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    'info' => 'bg-blue-100 border-blue-400 text-blue-700',
];

$colorClasses = $colors[$type] ?? $colors['info'];
@endphp

@if ($message)
    <div x-data="{ show: true }" x-show="show" x-transition class="{{ $colorClasses }} border-l-4 p-4 mb-4" role="alert">
        <div class="flex">
            <div class="py-1">
                {{-- Bisa ditambahkan ikon di sini --}}
            </div>
            <div class="ml-3 flex-grow">
                <p class="text-sm font-bold">{{ ucfirst($type) }}</p>
                <p class="text-sm">{{ $message }}</p>
            </div>
            <button @click="show = false" type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-opacity-25 hover:bg-current focus:outline-none">
                <span class="sr-only">Dismiss</span>
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
    </div>
@endif
