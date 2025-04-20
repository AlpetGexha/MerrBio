@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'value' => false,
    'disabled' => false,
])

@php
    $isChecked = is_bool($value) ? $value : (bool) $value;
@endphp

<div class="flex items-center">
    <button
        type="button"
        role="switch"
        aria-checked="{{ $isChecked ? 'true' : 'false' }}"
        {{ $attributes->merge(['class' => 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2' . ($isChecked ? ' bg-blue-600' : ' bg-gray-200') . ($disabled ? ' opacity-50 cursor-not-allowed' : '')]) }}
        @disabled($disabled)
    >
        <span
            aria-hidden="true"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
            :class="{ 'translate-x-5': value, 'translate-x-0': !value }"
        ></span>
    </button>

    @if($label)
        <span class="ml-3">
            <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
        </span>
    @endif
</div>
