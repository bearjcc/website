@props([
    'href' => '#',
    'title' => '',
    'blurb' => '',
    'variant' => 'portal',
])

@php
    $label = 'Visit ' . $title;
@endphp

<a href="{{ $href }}"
   class="um-sister-site-card group relative block rounded-2xl border border-[hsl(var(--border)/.10)] bg-[hsl(var(--surface)/.04)] overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-constellation transition-all duration-150 ease-out interactive-glow smooth-transition mobile-enhanced"
   aria-label="{{ $label }}"
   data-um-sister-site>
    <div class="absolute inset-0 flex flex-col">
        <div class="um-sister-site-motif flex flex-1 items-center justify-center p-4 min-h-0
                    md:p-5">
            @switch($variant)
                @case('f1')
                    {{-- Checkered flag, open-wheel silhouette, Ursa brand star (F1 Predictor) --}}
                    <svg class="w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20 opacity-92" viewBox="0 0 100 100" fill="none" aria-hidden="true" shape-rendering="geometricPrecision">
                        <line x1="12" y1="20" x2="12" y2="80" stroke="hsl(220 14% 50% / 0.5)" stroke-width="2" stroke-linecap="round" />
                        <g transform="translate(10,6) scale(1.4)">
                            <rect x="0" y="0" width="3.2" height="3.2" fill="hsl(0 0% 100% / 0.95)" />
                            <rect x="3.2" y="0" width="3.2" height="3.2" fill="hsl(220 18% 12%)" />
                            <rect x="0" y="3.2" width="3.2" height="3.2" fill="hsl(220 18% 12%)" />
                            <rect x="3.2" y="3.2" width="3.2" height="3.2" fill="hsl(0 0% 100% / 0.95)" />
                        </g>
                        <g fill="hsl(0 72% 50%)" stroke="hsl(0 0% 10% / 0.12)" transform="translate(32, 44)">
                            <rect x="0" y="8" width="46" height="7" rx="1" />
                            <rect x="8" y="0" width="20" height="8" rx="0.4" fill="hsl(220 15% 14%)" />
                            <ellipse cx="5" cy="19" rx="1.2" ry="0.5" fill="hsl(220 12% 20%)" stroke="none" />
                            <ellipse cx="41" cy="19" rx="1.2" ry="0.5" fill="hsl(220 12% 20%)" stroke="none" />
                        </g>
                        <g transform="translate(70, 18) scale(1.6)" stroke="hsl(var(--star))" fill="none" stroke-width="0.45" stroke-linecap="round">
                            @for($a = 0; $a < 5; $a++)
                            <g transform="rotate({{ -90 + $a * 72 }})">
                                <line x1="0" y1="0" x2="0" y2="-1.2" />
                            </g>
                            @endfor
                        </g>
                    </svg>
                    @break
                @case('taverns')
                    {{-- Tankard + small chest, warm wood (Taverns) --}}
                    <svg class="w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" viewBox="0 0 100 100" aria-hidden="true" shape-rendering="geometricPrecision">
                        <g>
                            <rect x="4" y="20" width="2.2" height="20" rx="0.2" fill="hsl(28 24% 32%)" />
                            <rect x="6" y="22" width="20" height="10" rx="0.2" fill="hsl(32 30% 40%)" />
                            <rect x="6" y="22" width="20" height="2" fill="hsl(32 32% 46% / 0.35)" />
                            <path d="M6 22 Q16 20 28 22" fill="none" stroke="hsl(28 22% 32% / 0.5)" stroke-width="0.6" />
                        </g>
                        <g>
                            <rect x="52" y="46" width="32" height="20" rx="0.2" fill="hsl(28 35% 32%)" stroke="hsl(32 20% 18%)" stroke-width="0.25" />
                            <path d="M 52,46 84,46 80,40 58,40 z" fill="hsl(32 32% 38%)" />
                        </g>
                    </svg>
                    @break
                @default
                    {{-- Little Dipper / constellation, Ursa Minor home --}}
                    <svg class="w-full h-full max-w-16 max-h-16 md:max-w-20 md:max-h-20" viewBox="0 0 100 100" aria-hidden="true" shape-rendering="geometricPrecision">
                        <g transform="scale(0.7) translate(0,2)" stroke="hsl(var(--constellation) / 0.55)" fill="none" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M 24 64 L 38 52 L 50 32 L 68 40 L 82 20" />
                        </g>
                        <g fill="hsl(var(--star))" transform="scale(0.7) translate(0,2)">
                            <circle cx="24" cy="64" r="2" />
                            <circle cx="38" cy="52" r="2" />
                            <circle cx="50" cy="32" r="2" />
                            <circle cx="68" cy="40" r="1.8" />
                            <circle cx="82" cy="20" r="1.6" />
                        </g>
                    </svg>
                    @break
            @endswitch
        </div>
        <div
            class="um-sister-site-copy border-t border-[hsl(var(--border)/.08)]
                   px-2.5 py-2.5 md:px-3 text-center bg-[hsl(var(--surface)/.12)]">
            <p class="text-xs font-medium text-ink leading-tight line-clamp-1">{{ $title }}</p>
            <p class="text-[0.7rem] md:text-xs text-ink/55 leading-snug line-clamp-2 mt-1">{{ $blurb }}</p>
        </div>
    </div>
    <div class="pt-[100%]" aria-hidden="true"></div>
</a>

@once
    @push('styles')
        <style>
            .um-sister-site-card {
                transition: border-color .15s ease, transform .2s ease, box-shadow .2s ease;
            }
            .um-sister-site-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px hsl(var(--space-900) / 0.15);
            }
            @media (max-width: 768px) {
                .um-sister-site-card:active {
                    transform: scale(0.98);
                    transition: transform 0.1s ease;
                }
            }
            .um-sister-site-card:focus-visible {
                outline: 2px solid hsl(var(--star));
                outline-offset: 2px;
                box-shadow: 0 0 0 4px hsl(var(--star) / 0.2);
            }
            @media (prefers-reduced-motion: reduce) {
                .um-sister-site-card {
                    transition: none;
                }
                .um-sister-site-card:hover {
                    transform: none;
                    box-shadow: none;
                }
            }
        </style>
    @endpush
@endonce
