<x-layouts::public>
    {{-- Hero --}}
    <div class="relative overflow-hidden bg-gradient-to-b from-zinc-950 via-zinc-900 to-zinc-950 pt-24 pb-16">
        {{-- Background glow --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-[300px] h-[300px] bg-winga-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-3xl px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-sm font-bold mb-6 tracking-wide">
                ⭐ {{ __('messages.pricing.hero_title') }}
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                {{ __('messages.pricing.hero_subtitle') }}
            </h1>
            <p class="mt-5 text-lg text-zinc-400 max-w-xl mx-auto leading-relaxed">
                {{ __('messages.pricing.hero_subtitle') }}
            </p>

            {{-- Trust badges --}}
            <div class="mt-8 flex flex-wrap justify-center gap-4 text-sm text-zinc-400">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"></path></svg>
                    {{ __('messages.pricing.lipa_pochi') }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"></path></svg>
                    {{ __('messages.pricing.inanza_mara_moja') }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"></path></svg>
                    {{ __('messages.pricing.hakuna_ushuru') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Plan Cards --}}
    <div class="bg-zinc-50 dark:bg-zinc-950 py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            @if($plans->isNotEmpty())
            @php
                $colorMap = [
                    'amber' => [
                        'border'   => 'border-amber-200 dark:border-amber-800/50',
                        'badge'    => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                        'icon_bg'  => 'bg-amber-100 dark:bg-amber-900/30',
                        'icon'     => 'text-amber-500',
                        'price'    => 'text-amber-600 dark:text-amber-400',
                        'btn'      => 'bg-amber-500 hover:bg-amber-600 text-white shadow-amber-500/30',
                        'glow'     => '',
                    ],
                    'blue' => [
                        'border'   => 'border-blue-400 dark:border-blue-600',
                        'badge'    => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400',
                        'icon_bg'  => 'bg-blue-100 dark:bg-blue-900/30',
                        'icon'     => 'text-blue-500',
                        'price'    => 'text-blue-600 dark:text-blue-400',
                        'btn'      => 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-600/30',
                        'glow'     => 'shadow-blue-100 dark:shadow-blue-950/50',
                    ],
                    'winga' => [
                        'border'   => 'border-winga-400 dark:border-winga-600',
                        'badge'    => 'bg-winga-100 dark:bg-winga-900/40 text-winga-700 dark:text-winga-400',
                        'icon_bg'  => 'bg-winga-100 dark:bg-winga-900/30',
                        'icon'     => 'text-winga-500',
                        'price'    => 'text-winga-600 dark:text-winga-400',
                        'btn'      => 'bg-winga-600 hover:bg-winga-700 text-white shadow-winga-600/30',
                        'glow'     => '',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @foreach($plans as $plan)
                @php $c = $colorMap[$plan->badge_color] ?? $colorMap['amber']; @endphp

                <div class="relative bg-white dark:bg-zinc-900 rounded-3xl border-2 {{ $c['border'] }} p-8 flex flex-col shadow-xl {{ $c['glow'] }} transition-transform duration-300 hover:-translate-y-1">

                    {{-- Recommended pill --}}
                    @if($plan->is_recommended)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 text-white text-[11px] font-black rounded-full uppercase tracking-widest shadow-lg shadow-blue-600/40 whitespace-nowrap">
                        ⭐ Inayopendekezwa
                    </div>
                    @endif

                    {{-- Icon + badge --}}
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl {{ $c['icon_bg'] }} flex items-center justify-center text-2xl shadow-sm">
                            @if($plan->slug === 'winga-complex') 🔧
                            @elseif($plan->slug === 'winga-karume') ⭐
                            @else 🏆
                            @endif
                        </div>
                        <span class="px-2.5 py-1 {{ $c['badge'] }} text-[10px] font-black rounded-lg uppercase tracking-wider">
                            {{ $plan->durationLabel() }}
                        </span>
                    </div>

                    {{-- Name + price --}}
                    <div class="mb-6">
                        <h3 class="text-xl font-black text-zinc-900 dark:text-white">{{ $plan->name }}</h3>
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest mt-0.5">{{ $plan->name_en }}</p>
                        <div class="mt-4 flex items-end gap-1">
                            <span class="text-4xl font-black {{ $c['price'] }}">TZS {{ number_format($plan->price) }}</span>
                        </div>
                        <p class="text-xs text-zinc-400 mt-1">kwa {{ $plan->durationLabel() }} • inalipwa mara moja</p>
                    </div>

                    {{-- Divider --}}
                    <div class="h-px bg-zinc-100 dark:bg-zinc-800 mb-6"></div>

                    {{-- Features --}}
                    <ul class="space-y-3 mb-8 flex-1">
                        @foreach($plan->features as $feature)
                        <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                            <span class="mt-0.5 w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>

                    {{-- CTA --}}
                    <a href="{{ route('register') }}"
                        class="block w-full py-3.5 rounded-2xl font-black text-sm text-center shadow-lg transition duration-200 hover:-translate-y-0.5 hover:shadow-xl {{ $c['btn'] }}">
                        Chagua {{ $plan->name }} →
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Login hint --}}
            <p class="text-center text-sm text-zinc-500 dark:text-zinc-500 mt-10">
                Tayari una akaunti?
                <a href="{{ route('login') }}" class="text-winga-600 dark:text-winga-400 hover:underline font-bold">
                    Ingia na ujiunga na Winga Bora
                </a>
            </p>

            @else
            <p class="text-center text-zinc-400 py-16">Mipango itaonekana hivi karibuni.</p>
            @endif

        </div>
    </div>

    {{-- Benefits Comparison Table --}}
    <div class="bg-white dark:bg-zinc-900 py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-zinc-900 dark:text-white mb-4">{{ __('messages.pricing.comparison_title') }}</h2>
                <p class="text-lg text-zinc-500 dark:text-zinc-400">{{ __('messages.pricing.comparison_subtitle') }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="text-left py-4 px-4 font-bold text-zinc-900 dark:text-white">Faida</th>
                            @foreach($plans as $plan)
                            <th class="text-center py-4 px-4 font-bold text-zinc-900 dark:text-white">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-lg">{{ $plan->name }}</span>
                                    <span class="text-sm font-normal text-zinc-500">TZS {{ number_format($plan->price) }}</span>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Service Posting --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Huduma za Kupost
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">1 kwa mwezi</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-green-600 dark:text-green-400 font-bold">Zisizo na kikomo</span>
                                @else
                                <span class="text-green-600 dark:text-green-400 font-bold">Zisizo na kikomo</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Daily Bids --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Maombi ya Kazi kwa Siku
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">3</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-green-600 dark:text-green-400 font-bold">10</span>
                                @else
                                <span class="text-green-600 dark:text-green-400 font-bold">Zisizo na kikomo</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Portfolio Images --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Picha za Portfolio
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">5</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-green-600 dark:text-green-400 font-bold">20</span>
                                @else
                                <span class="text-green-600 dark:text-green-400 font-bold">Zisizo na kikomo</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Analytics --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Analytics Dashboard
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">Msingi</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-green-600 dark:text-green-400 font-bold">Kawaida</span>
                                @else
                                <span class="text-green-600 dark:text-green-400 font-bold">Bora (Makato)</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Search Ranking --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Uwekaji Mbele katika Utafutaji
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">Kawaida</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-amber-600 dark:text-amber-400 font-bold">+25 Points</span>
                                @else
                                <span class="text-amber-600 dark:text-amber-400 font-bold">+50 Points</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Profile Highlights --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Viashirio vya Wasifu
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">—</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-green-600 dark:text-green-400 font-bold">✓ Imethibitishwa</span>
                                @else
                                <span class="text-green-600 dark:text-green-400 font-bold">✓ Yote + Top Rated</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Custom URL --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                URL ya Kibinafsi
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">—</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-green-600 dark:text-green-400 font-bold">winga.com/w/jina</span>
                                @else
                                <span class="text-green-600 dark:text-green-400 font-bold">winga.com/w/jina</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Featured Section --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Sehemu ya Waliopendekezwa
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">—</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-zinc-500">—</span>
                                @else
                                <span class="text-amber-600 dark:text-amber-400 font-bold">✓ Katika Kategoria</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Smart Match Priority --}}
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Upaumbele wa Smart Match
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">Saa 1 baada</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-green-600 dark:text-green-400 font-bold">Dakika 15 baada</span>
                                @else
                                <span class="text-amber-600 dark:text-amber-400 font-bold">Mara moja</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- Chat Response Time --}}
                        <tr>
                            <td class="py-4 px-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Ishara ya Muda wa Majibu
                            </td>
                            @foreach($plans as $plan)
                            <td class="py-4 px-4 text-center text-sm">
                                @if($plan->slug === 'msingi')
                                <span class="text-zinc-500">—</span>
                                @elseif($plan->slug === 'kawaida')
                                <span class="text-zinc-500">—</span>
                                @else
                                <span class="text-green-600 dark:text-green-400 font-bold">✓ Muda wastani</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-8 text-center">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    * Faida zote zinapatikana mara moja baada ya malipo kuthibitishwa
                </p>
            </div>
        </div>
    </div>

    {{-- FAQ / Maelezo --}}
    <div class="bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800 py-20">
        <div class="mx-auto max-w-3xl px-6">
            <h2 class="text-2xl font-black text-zinc-900 dark:text-white text-center mb-10">Maswali Yanayoulizwa Mara Kwa Mara</h2>

            <div class="space-y-4">
                @foreach([
                    ['Je, subscription inaanza lini?', 'Ukilipia kwa pochi, inaanza mara moja. Ukilipia kwa mobile money au kadi, inaanza baada ya malipo kuthibitishwa (dakika 1–5).'],
                    ['Je, naweza kubatilisha?', 'Subscription haibatilishwi. Muda uliolipwa utaendelea hadi kumalizika — utanufaika kikamilifu.'],
                    ['Niweze kuhuisha kabla ya kumalizika?', 'Ndiyo! Unaweza kuhuisha wakati wowote. Muda mpya utaanza baada ya muda wa sasa kumalizika.'],
                    ['Naweza kulipa kwa njia gani?', 'Tunakubali M-Pesa, Tigo Pesa, Airtel Money, kadi za benki (Visa/Mastercard), na pochi ya Winga.'],
                    ['Je, beji inaonekana wapi?', 'Beji ya Winga Bora itaonekana kwenye wasifu wako, carousel ya ukurasa wa nyumbani, na matokeo ya utafutaji.'],
                ] as [$q, $a])
                <details class="group bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-4 cursor-pointer font-bold text-zinc-900 dark:text-white select-none list-none">
                        {{ $q }}
                        <svg class="w-5 h-5 text-zinc-400 transition-transform group-open:rotate-180 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed border-t border-zinc-200 dark:border-zinc-700 pt-4">
                        {{ $a }}
                    </div>
                </details>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::public>