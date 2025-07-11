@props(['title', 'value', 'color' => 'gray'])

<div class="bg-white rounded shadow p-4 border-l-4 border-{{ $color }}-500">
    <div class="text-sm text-gray-500">{{ $title }}</div>
    <div class="text-lg font-semibold text-{{ $color }}-700">{{ $value }}</div>
</div>
