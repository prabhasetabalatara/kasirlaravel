@props(['title' => ''])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg p-6']) }}>
    @if ($title)
        <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-3 mb-4">
            {{ $title }}
        </h3>
    @endif

    <div class="text-gray-900">
        {{ $slot }}
    </div>
</div>
