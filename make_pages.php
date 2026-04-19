<?php

// We will create the routes and blades for the missing pages

// 1. BEI (Pricing)
$beiBlade = <<<'BLADE'
<x-layouts::public>
    <div class="bg-white dark:bg-zinc-950 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-base/7 font-semibold text-winga-600">Bei Zetu</h2>
                <p class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-zinc-900 dark:text-white sm:text-5xl lg:text-balance">
                    Kila kitu wazi, hakuna malipo yaliyofichwa.
                </p>
                <p class="mt-6 text-lg/8 text-zinc-600 dark:text-zinc-400">
                    Kwa wafanyakazi, kufungua akaunti na kuanza kufanya kazi ni <strong>BURE</strong>. Muajiri analipia tu pale ada ndogo ya ushirikiano.
                </p>
            </div>
            
            <div class="mx-auto mt-16 grid max-w-lg grid-cols-1 items-center gap-y-6 sm:mt-20 sm:gap-y-0 lg:max-w-4xl lg:grid-cols-2">
                {{-- Wafanyakazi --}}
                <div class="rounded-3xl rounded-t-3xl bg-white/60 dark:bg-zinc-900/60 p-8 ring-1 ring-zinc-900/10 dark:ring-white/10 sm:mx-8 sm:rounded-b-none sm:p-10 lg:mx-0 lg:rounded-bl-3xl lg:rounded-tr-none">
                    <h3 class="text-base/7 font-semibold text-winga-600">Mfanyakazi</h3>
                    <p class="mt-4 flex items-baseline gap-x-2">
                        <span class="text-5xl font-semibold tracking-tight text-zinc-900 dark:text-white">BURE</span>
                        <span class="text-base text-zinc-500">/ milele</span>
                    </p>
                    <p class="mt-6 text-base/7 text-zinc-600 dark:text-zinc-400">Sajili akaunti na upate kazi bila gharama ya kujiunga.</p>
                    <ul role="list" class="mt-8 space-y-3 text-sm/6 text-zinc-600 sm:mt-10">
                        <li class="flex gap-x-3 text-zinc-700 dark:text-zinc-300">
                            <svg class="h-6 w-5 flex-none text-winga-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Tengeneza Wasifu Bure
                        </li>
                        <li class="flex gap-x-3 text-zinc-700 dark:text-zinc-300">
                            <svg class="h-6 w-5 flex-none text-winga-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Pokea asilimia kubwa ya malipo yako
                        </li>
                        <li class="flex gap-x-3 text-zinc-700 dark:text-zinc-300">
                            <svg class="h-6 w-5 flex-none text-winga-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Tuma maombi ya kazi bila kikomo
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="mt-8 block rounded-md px-3.5 py-2.5 text-center text-sm font-semibold text-winga-600 ring-1 ring-inset ring-winga-200 hover:ring-winga-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-winga-600 sm:mt-10">Jisajili Sasa</a>
                </div>

                {{-- Muajiri --}}
                <div class="relative rounded-3xl bg-zinc-900 p-8 shadow-2xl ring-1 ring-zinc-900/10 sm:p-10 lg:z-10 bg-gradient-to-br from-winga-900 to-zinc-900">
                    <h3 class="text-base/7 font-semibold text-winga-400">Muajiri</h3>
                    <p class="mt-4 flex items-baseline gap-x-2">
                        <span class="text-5xl font-semibold tracking-tight text-white">Ada 12% tu</span>
                    </p>
                    <p class="mt-6 text-base/7 text-zinc-300">Kutuma kazi ni BURE. Unalipa asilimia ndogo kwenye jukwaa pale tu unapofanikiwa.</p>
                    <ul role="list" class="mt-8 space-y-3 text-sm/6 text-zinc-300 sm:mt-10">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-winga-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Chapisha kazi BURE bila kikomo
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-winga-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Pesa salama kwenye Escrow yetu
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-winga-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Angalia wasifu na kuchagua kwa usahihi
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-winga-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Msaada kwa wateja (Support) wako
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="mt-8 block rounded-md bg-winga-500 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-winga-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-winga-500 sm:mt-10">Chapisha Kazi Sasa</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts::public>
BLADE;
file_put_contents('resources/views/pages/bei.blade.php', $beiBlade);

// 2. WASIFU WA KITAALAMU (Professional Profile - Info Page)
$wasifuBlade = <<<'BLADE'
<x-layouts::public>
    <div class="bg-white dark:bg-zinc-950 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-4xl font-semibold tracking-tight text-pretty text-zinc-900 dark:text-white sm:text-5xl">Kujenga Wasifu Wako wa Kitaalamu</h2>
                <p class="mt-6 text-lg/8 text-zinc-600 dark:text-zinc-400">Jinsi ya kuongeza uaminifu na kupata kazi nyingi zaidi kwenye mtandao wa Winga. Wasifu wako unakutambulisha na kujenga imani kwa waajiri.</p>
            </div>
            
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-zinc-900 dark:text-white">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-winga-500">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </span>
                            Kamilisha Taarifa Zako
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base/7 text-zinc-600 dark:text-zinc-400">
                            <p class="flex-auto">Waajiri huamini akaunti ambazo zimekamilisha asilimia 100% ya taarifa zao, picha halisi inayoonekana vizuri, na maelezo (bio) yanayojieleza vyema yaliyoshiba ustadi unaouweza.</p>
                            <p class="mt-6">
                                <a href="{{ route('register') }}" class="text-sm/6 font-semibold text-winga-600">Boresha Wasifu <span aria-hidden="true">→</span></a>
                            </p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-zinc-900 dark:text-white">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-winga-500">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                            </span>
                            Tengeneza Uhakiki (Reviews)
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base/7 text-zinc-600 dark:text-zinc-400">
                            <p class="flex-auto">Silaha yako kubwa kwenye jukwaa hili ni sifa unayopata. Ukifanya kazi kwa uaminifu na umakini mkubwa utapata Nyota 5. Waajiri vijavyo watafanya uamuzi kwa kuangalia nyota ulizokusanya.</p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-zinc-900 dark:text-white">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-winga-500">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" /></svg>
                            </span>
                            Ongeza Kazi Zako Halisi (Portfolio)
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base/7 text-zinc-600 dark:text-zinc-400">
                            <p class="flex-auto">Tanzania ya sasa inaenda kwa vitendo. Picha au viungo vya kazi zilizopita huongeza ushawishi kwa zaidi ya 70%. Mpe mwajiri sababu ya kukuajiri kwa kumuonyesha ulichowahi kufanya.</p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts::public>
BLADE;
file_put_contents('resources/views/pages/wasifu.blade.php', $wasifuBlade);

// 3. Modifying views to use actual routes instead of `#bei`
$navContent = file_get_contents('resources/views/partials/public-nav.blade.php');
$navContent = str_replace('href="#bei"', 'href="{{ route(\'bei\') }}"', $navContent);
$navContent = str_replace('href="#wasifu"', 'href="{{ route(\'wasifu\') }}"', $navContent);
file_put_contents('resources/views/partials/public-nav.blade.php', $navContent);

$footerContent = file_get_contents('resources/views/partials/footer.blade.php');
// Add proper naming links where possible using inline str_replace
$footerContent = str_replace('<a href="#" class="text-sm hover:text-winga-400 transition-colors">Jinsi Inavyofanya Kazi</a>', '<a href="{{ route(\'home\') }}#inavyofanya-kazi" class="text-sm hover:text-winga-400 transition-colors">Jinsi Inavyofanya Kazi</a>', $footerContent);
$footerContent = str_replace('<a href="#" class="text-sm hover:text-winga-400 transition-colors">Tafuta Wafanyakazi</a>', '<a href="{{ route(\'tafuta-wafanyakazi\') }}" class="text-sm hover:text-winga-400 transition-colors">Tafuta Wafanyakazi</a>', $footerContent);
$footerContent = str_replace('<a href="#" class="text-sm hover:text-winga-400 transition-colors">Tafuta Kazi</a>', '<a href="{{ route(\'tafuta-kazi\') }}" class="text-sm hover:text-winga-400 transition-colors">Tafuta Kazi</a>', $footerContent);
$footerContent = str_replace('<a href="#" class="text-sm hover:text-winga-400 transition-colors">Bei na Vifurushi</a>', '<a href="{{ route(\'bei\') }}" class="text-sm hover:text-winga-400 transition-colors">Bei na Vifurushi</a>', $footerContent);

file_put_contents('resources/views/partials/footer.blade.php', $footerContent);

// 4. Modifying routes/web.php to include the two new generic pages
$routesFile = file_get_contents('routes/web.php');
$newRoutes = "
Route::get('/kuhusu', function () {
    return view('pages.kuhusu');
})->name('kuhusu');

Route::get('/bei', function () {
    return view('pages.bei');
})->name('bei');

Route::get('/wasifu', function () {
    return view('pages.wasifu');
})->name('wasifu');

";
$routesFile = str_replace("Route::get('/kuhusu', function () {\n    return view('pages.kuhusu');\n})->name('kuhusu');", $newRoutes, $routesFile);
file_put_contents('routes/web.php', $routesFile);
