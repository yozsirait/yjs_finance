@props(['title' => 'Form Judul', 'submit' => 'Simpan'])

<div class="bg-white rounded-xl shadow-md p-6 max-w-4xl mx-auto">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h2>

    <form {{ $attributes->merge(['method' => 'POST']) }} class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{ $slot }}

        <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                {{ $submit }}
            </button>
        </div>
    </form>
</div>
