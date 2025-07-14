@props(['label', 'name'])

<div class="space-y-1">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
    </label>
    {{ $slot }}
    @error($name)
        <div class="text-sm text-red-500">{{ $message }}</div>
    @enderror
</div>
