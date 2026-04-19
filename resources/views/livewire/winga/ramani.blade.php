<div>
    @once
        @push('styles')
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
        @endpush
        @push('scripts')
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
            <script>
                (function () {
                    function escHtml(s) {
                        return String(s ?? '')
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;');
                    }

                    window.__wingaRamaniApplyMap = function (data) {
                        var el = document.getElementById('winga-ramani-map');
                        if (!el || typeof L === 'undefined') return;

                        var viewJobLabel = el.dataset.viewJob || 'View';
                        var youHereLabel = el.dataset.youHere || 'You';

                        var jobs = data.jobs || [];
                        var userLat = data.userLat;
                        var userLng = data.userLng;

                        if (window.__wingaRamaniMapInstance) {
                            window.__wingaRamaniMapInstance.remove();
                            window.__wingaRamaniMapInstance = null;
                            window.__wingaRamaniMarkersLayer = null;
                        }

                        var map = L.map(el, { scrollWheelZoom: false });
                        window.__wingaRamaniMapInstance = map;

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(map);

                        var markersLayer = L.layerGroup().addTo(map);
                        window.__wingaRamaniMarkersLayer = markersLayer;

                        var bounds = [];

                        jobs.forEach(function (j) {
                            if (j.latitude == null || j.longitude == null) return;
                            var lat = parseFloat(j.latitude);
                            var lng = parseFloat(j.longitude);
                            if (isNaN(lat) || isNaN(lng)) return;
                            var m = L.marker([lat, lng]);
                            var dist = j.distance != null ? '<br><small>' + escHtml(String(j.distance)) + ' km</small>' : '';
                            m.bindPopup(
                                '<strong>' + escHtml(j.title) + '</strong><br>' +
                                escHtml(j.location) + '<br>' +
                                '<span class="text-xs">TZS ' + escHtml(String(j.budget_min || 0)) + '</span>' +
                                dist + '<br><a class="text-winga-600 font-medium" href="' + escHtml(j.url) + '">' + escHtml(viewJobLabel) + '</a>'
                            );
                            markersLayer.addLayer(m);
                            bounds.push([lat, lng]);
                        });

                        if (userLat != null && userLng != null && !isNaN(parseFloat(userLat)) && !isNaN(parseFloat(userLng))) {
                            var ulat = parseFloat(userLat);
                            var ulng = parseFloat(userLng);
                            L.circleMarker([ulat, ulng], { radius: 10, color: '#059669', fillColor: '#34d399', fillOpacity: 0.85, weight: 2 })
                                .addTo(map)
                                .bindPopup(youHereLabel);
                            bounds.push([ulat, ulng]);
                        }

                        if (bounds.length > 0) {
                            map.fitBounds(bounds, { padding: [36, 36], maxZoom: 14 });
                        } else {
                            map.setView([-6.7924, 39.2083], 6);
                        }

                        setTimeout(function () { map.invalidateSize(); }, 100);
                    };
                })();
            </script>
        @endpush
    @endonce

    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.ramani.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.ramani.subtitle') }}</p>
                <p class="text-xs text-zinc-500 mt-2">
                    {{ __('messages.ramani.your_location') }}
                    @if($userLat && $userLng)
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ __('messages.ramani.gps_ok') }}</span>
                    @else
                        {{ $userLocation ?: __('messages.ramani.not_set') }}
                        <span class="block mt-1 text-amber-700 dark:text-amber-300">{{ __('messages.ramani.gps_hint') }}</span>
                    @endif
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative flex-1 sm:flex-initial">
                    <input type="search" wire:model.live.debounce.400ms="search" placeholder="{{ __('messages.ramani.search_placeholder') }}"
                        class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-transparent px-4 py-2.5 pl-10 w-full sm:w-64 dark:text-white" />
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                </div>
                <select wire:model.live="radius" class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-transparent px-4 py-2.5 w-full sm:w-auto dark:text-white">
                    @foreach([10,25,50,100,200] as $km)
                        <option value="{{ $km }}">{{ __('messages.ramani.distance') }} {{ $km }}km</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div id="winga-ramani-map" wire:ignore class="h-[min(520px,70vh)] min-h-[400px] w-full z-0 bg-zinc-100 dark:bg-zinc-800"
                data-view-job="{{ __('messages.ramani.view_job') }}"
                data-you-here="{{ __('messages.ramani.you_are_here') }}"></div>
            <p class="text-[10px] text-zinc-400 px-3 py-1 border-t border-zinc-100 dark:border-zinc-800">{{ __('messages.ramani.map_attribution') }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4 space-y-4 h-[min(520px,70vh)] min-h-[400px] overflow-y-auto">
            @if($ready && count($jobs) > 0)
                @foreach($jobs as $job)
                    <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 hover:border-winga-400 transition">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-zinc-500">{{ $job['category'] ?? '—' }}</p>
                                <h3 class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-white">{{ $job['title'] }}</h3>
                            </div>
                            <span class="text-xs text-zinc-500 flex-shrink-0">{{ $job['posted_at'] }}</span>
                        </div>
                        <div class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 space-y-1">
                            <p><strong>{{ __('messages.ramani.location') }}</strong> {{ $job['location'] }}</p>
                            <p><strong>{{ __('messages.ramani.budget') }}</strong> TZS {{ number_format($job['budget_min']) }} {{ ($job['budget_type'] ?? '') === 'hourly' ? '/ saa' : '' }}</p>
                            @if(!empty($job['distance']))
                                <p><strong>{{ __('messages.ramani.distance_label') }}</strong> {{ $job['distance'] }} km</p>
                            @endif
                        </div>
                        <div class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            <a href="{{ $job['url'] }}" class="rounded-lg bg-winga-600 hover:bg-winga-700 text-white text-sm px-3 py-1.5 text-center" wire:navigate>{{ __('messages.ramani.view_job') }}</a>
                            <a href="{{ $job['url'] }}" class="text-sm text-winga-600 hover:text-winga-500 text-center sm:text-left" wire:navigate>{{ __('messages.ramani.more_details') }}</a>
                        </div>
                    </div>
                @endforeach
            @elseif($ready)
                <div class="text-center text-zinc-500 py-8">
                    <p>{{ __('messages.ramani.no_jobs') }}</p>
                    <p class="text-xs mt-2">{{ __('messages.ramani.no_jobs_map_hint') }}</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach(range(1,4) as $i)
                        <div class="h-24 rounded-xl bg-zinc-100 dark:bg-zinc-800 animate-pulse"></div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
