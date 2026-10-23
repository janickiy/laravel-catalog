@props(['name' => 'globe'])

@php
    // Trusted, local SVG paths keep category icons consistent across the directory.
    $icons = [
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21v-2a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v2"/>',
        'lock' => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V6a4 4 0 0 1 8 0v4m-4 5v2"/>',
        'sign-in' => '<path d="M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5M3 12h13m-5-5 5 5-5 5"/>',
        'alert' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v6m0 4h.01"/>',
        'compass' => '<circle cx="12" cy="12" r="9"/><path d="m16 8-2.5 5.5L8 16l2.5-5.5L16 8Z"/><path d="M12 3v1m9 8h-1m-8 9v-1M3 12h1"/>',
        'search' => '<circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/>',
        'arrow-right' => '<path d="M4 12h16m-6-6 6 6-6 6"/>',
        'arrow-up-right' => '<path d="M6 18 18 6M6 6h12v12"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'grid' => '<rect x="3.5" y="3.5" width="6.5" height="6.5" rx="1.5"/><rect x="14" y="3.5" width="6.5" height="6.5" rx="1.5"/><rect x="3.5" y="14" width="6.5" height="6.5" rx="1.5"/><rect x="14" y="14" width="6.5" height="6.5" rx="1.5"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.8 5.5 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.5-3.8-9S9.5 5.5 12 3Z"/>',
        'close' => '<path d="m6 6 12 12M6 18 18 6"/>',
        'bookmark' => '<path d="M6 5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16l-6-4-6 4V5Z"/>',
        'folder' => '<path d="M3 7V5.5A1.5 1.5 0 0 1 4.5 4H9l2 3h8.5A1.5 1.5 0 0 1 21 8.5v10a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18.5V7Zm0 3h18"/>',
        'sparkles' => '<path d="m12 3 2.6 6.4L21 12l-6.4 2.6L12 21l-2.6-6.4L3 12l6.4-2.6L12 3ZM4 3v4M2 5h4m14 13v4m-2-2h4"/>',
        'layers' => '<path d="m12 3 10 5-10 5L2 8l10-5ZM3 12l9 4.5 9-4.5M3 16l9 4.5 9-4.5"/>',
        'technology' => '<rect x="6" y="6" width="12" height="12" rx="2"/><rect x="9" y="9" width="6" height="6" rx="1"/><path d="M9 3v3m6-3v3M9 18v3m6-3v3M3 9h3m-3 6h3m12-6h3m-3 6h3"/>',
        'business' => '<rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12l9 3 9-3m-9 1v4"/>',
        'education' => '<path d="m2 9 10-5 10 5-10 5L2 9Zm4 2v6c3.7 2.7 8.3 2.7 12 0v-6m4-2v7"/>',
        'sports' => '<rect x="5" y="5" width="4" height="14" rx="1"/><rect x="15" y="5" width="4" height="14" rx="1"/><path d="M9 12h6M5 8H3v8h2m14-8h2v8h-2"/>',
        'home' => '<path d="m3 10 9-7 9 7M5 8.5V20a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5M9 21v-8h6v8"/>',
        'shopping' => '<path d="M5 7h14l1.2 13a1 1 0 0 1-1 1H4.8a1 1 0 0 1-1-1L5 7Z"/><path d="M8 9V6a4 4 0 0 1 8 0v3"/>',
        'food' => '<path d="M4 3v5a3 3 0 0 0 6 0V3M7 3v18m10 0v-7h3V3c-3 1.2-5 4.3-5 7v4h2"/>',
        'services' => '<path d="M14.5 3.5a6 6 0 0 0-7 7L3.8 15a3.2 3.2 0 0 0 4.5 4.5l4.5-3.7a6 6 0 0 0 7-7l-4 4-4-4 3.2-5.3Z"/>',
        'health' => '<rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-4 4v6m-3-3h6"/>',
        'travel' => '<path d="M10.5 9V4a1.5 1.5 0 0 1 3 0v5l7 4.5v2l-7-2V18l2 1.5V21L12 20l-3.5 1v-1.5l2-1.5v-4.5l-7 2v-2l7-4.5Z"/>',
        'news' => '<path d="M5 3h13a1 1 0 0 1 1 1v14a3 3 0 0 0 3 3H6a3 3 0 0 1-3-3V5a2 2 0 0 1 2-2Zm14 6h3v9a3 3 0 0 1-3 3"/><path d="M7 7h8M7 11h3v4H7v-4Zm6 0h2m-2 4h2M7 18h8"/>',
        'entertainment' => '<circle cx="12" cy="12" r="9"/><path d="m10 8 6 4-6 4V8Z"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'directory-icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    {!! $icons[$name] ?? $icons['globe'] !!}
</svg>
