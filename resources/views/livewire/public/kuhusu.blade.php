<div>
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-winga-50 to-white dark:from-zinc-950 dark:to-zinc-900 py-16 lg:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-3 py-1 rounded-full bg-winga-100 dark:bg-winga-900/50 text-winga-700 dark:text-winga-300 text-sm font-semibold mb-5">{{ __('messages.about.title') }}</span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-zinc-900 dark:text-white leading-tight">
                {!! __('messages.about.hero_title', ['future' => '<span class="text-winga-500">' . __('messages.about.hero_future') . '</span>']) !!}
            </h1>
            <p class="mt-6 text-lg text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                {{ __('messages.about.hero_description') }}
            </p>
        </div>
    </section>

    {{-- Values --}}
    <section class="bg-white dark:bg-zinc-900 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $values = [
                        ['icon' => '🎯', 'title' => __('messages.about.our_mission'), 'desc' => __('messages.about.mission_desc')],
                        ['icon' => '👁️', 'title' => __('messages.about.our_vision'), 'desc' => __('messages.about.vision_desc')],
                        ['icon' => '💚', 'title' => __('messages.about.our_values'), 'desc' => __('messages.about.values_desc')],
                    ];
                @endphp

                @foreach($values as $value)
                    <div class="text-center p-8 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50">
                        <span class="text-4xl block mb-4">{{ $value['icon'] }}</span>
                        <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-3">{{ $value['title'] }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="bg-gradient-to-r from-winga-600 to-winga-700 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                @php
                    $stats = [
                        ['value' => '5,000+', 'label' => 'Wafanyakazi'],
                        ['value' => '1,200+', 'label' => 'Kazi kwa Siku'],
                        ['value' => '26', 'label' => 'Mikoa ya Tanzania'],
                        ['value' => '98%', 'label' => 'Kuridhika'],
                    ];
                @endphp

                @foreach($stats as $stat)
                    <div>
                        <p class="text-3xl font-bold text-white">{{ $stat['value'] }}</p>
                        <p class="text-sm text-winga-200 mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
