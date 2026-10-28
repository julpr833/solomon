@props([
    'id',
    'name' => 'emoji',
    'value' => '🏆',
])

@php
    $panelId = $id . '-panel';
    $dataSource = 'https://cdn.jsdelivr.net/npm/emoji-picker-element-data@1.8.0/es/cldr/data.json';
@endphp

<div class="relative" data-emoji-field>
    <input type="hidden" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" data-emoji-value>

    <button type="button" data-emoji-trigger aria-controls="{{ $panelId }}" aria-expanded="false"
        onclick="toggleEmojiPicker(this)"
        class="flex w-full items-center gap-3 rounded-lg border-[1.5px] border-line bg-white px-3.5 py-2.5 text-left outline-none transition-colors duration-200 hover:border-brand-50 focus:border-brand-50 cursor-pointer">
        <span data-emoji-preview class="text-2xl leading-none">{{ $value }}</span>
        <span class="flex-1 text-[13px] font-semibold text-brand-95">Elegir emoji</span>
        <i class="ti ti-chevron-down text-ink-light"></i>
    </button>

    <div id="{{ $panelId }}" data-emoji-panel
        class="mt-2 hidden overflow-hidden rounded-lg border-[1.5px] border-line bg-white shadow-card">
        <emoji-picker locale="es" data-source="{{ $dataSource }}"
            style="width: 100%; height: 320px; --background: #ffffff; --border-color: #d0d8e2; --num-columns: 8; --emoji-size: 1.4rem;">
        </emoji-picker>
    </div>
</div>
