<?php

$content = file_get_contents('resources/views/welcome.blade.php');

$content = str_replace(
    '<div class="w-10 h-10 rounded-full bg-winga-100 dark:bg-winga-900 flex items-center justify-center text-lg">💻</div>',
    '<div class="w-10 h-10 rounded-full bg-winga-100 dark:bg-winga-900 flex items-center justify-center p-2"><img src="{{ asset(\'icon.png\') }}" class="w-full h-full object-contain" alt="Icon" /></div>',
    $content
);

$content = str_replace(
    '<div class="w-10 h-10 rounded-full bg-accent-orange-100 dark:bg-accent-orange-900 flex items-center justify-center text-lg">🎨</div>',
    '<div class="w-10 h-10 rounded-full bg-accent-orange-100 dark:bg-accent-orange-900 flex items-center justify-center p-2"><img src="{{ asset(\'icon.png\') }}" class="w-full h-full object-contain" alt="Icon" /></div>',
    $content
);

$content = str_replace(
    '<div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-lg">🔨</div>',
    '<div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center p-2"><img src="{{ asset(\'icon.png\') }}" class="w-full h-full object-contain" alt="Icon" /></div>',
    $content
);

// How it Works Array
$howItWorksOld = "                    \$steps = [
                        ['num' => '01', 'icon' => '📝', 'title' => 'Tuma Kazi', 'desc' => 'Andika kazi yako, weka bajeti na mahitaji. Itaonekana kwa wafanyakazi wote.', 'color' => 'winga'],
                        ['num' => '02', 'icon' => '🎯', 'title' => 'Pokea Maombi', 'desc' => 'Wafanyakazi wenye ujuzi wataomba kazi yako. Chagua aliyebora.', 'color' => 'accent-orange'],
                        ['num' => '03', 'icon' => '⚡', 'title' => 'Kazi Inafanywa', 'desc' => 'Mfanyakazi anafanya kazi. Pesa iko salama kwenye escrow.', 'color' => 'winga'],
                        ['num' => '04', 'icon' => '🔑', 'title' => 'Toa Code → Malipo', 'desc' => 'Ukiridhika, toa code ya siri. Mfanyakazi aweke code → pesa inatoka moja kwa moja!', 'color' => 'accent-orange'],
                    ];";

$howItWorksNew = "                    \$steps = [
                        ['num' => '01', 'title' => 'Tuma Kazi', 'desc' => 'Andika kazi yako, weka bajeti na mahitaji. Itaonekana kwa wafanyakazi wote.', 'color' => 'winga'],
                        ['num' => '02', 'title' => 'Pokea Maombi', 'desc' => 'Wafanyakazi wenye ujuzi wataomba kazi yako. Chagua aliyebora.', 'color' => 'accent-orange'],
                        ['num' => '03', 'title' => 'Kazi Inafanywa', 'desc' => 'Mfanyakazi anafanya kazi. Pesa iko salama kwenye escrow.', 'color' => 'winga'],
                        ['num' => '04', 'title' => 'Toa Code → Malipo', 'desc' => 'Ukiridhika, toa code ya siri. Mfanyakazi aweke code → pesa inatoka moja kwa moja!', 'color' => 'accent-orange'],
                    ];";

$content = str_replace($howItWorksOld, $howItWorksNew, $content);

// How it Works Blade
$howItWorksBladeOld = "                            <div class=\"w-14 h-14 rounded-xl bg-{{ \$step['color'] }}-100 dark:bg-{{ \$step['color'] }}-900/30 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform duration-500\">
                                {{ \$step['icon'] }}
                            </div>";
$howItWorksBladeNew = "                            <div class=\"w-14 h-14 rounded-xl bg-{{ \$step['color'] }}-100 dark:bg-{{ \$step['color'] }}-900/30 flex items-center justify-center p-3 mb-4 group-hover:scale-110 transition-transform duration-500\">
                                <img src=\"{{ asset('icon.png') }}\" class=\"w-full h-full object-contain\" alt=\"Icon\" />
                            </div>";
$content = str_replace($howItWorksBladeOld, $howItWorksBladeNew, $content);

// Categories Array
$categoriesOld = "                    \$categories = [
                        ['icon' => '💻', 'name' => 'Teknolojia & IT', 'count' => '340+'],
                        ['icon' => '🎨', 'name' => 'Ubunifu & Sanaa', 'count' => '220+'],
                        ['icon' => '✍️', 'name' => 'Uandishi & Tafsiri', 'count' => '180+'],
                        ['icon' => '📱', 'name' => 'Masoko Dijitali', 'count' => '150+'],
                        ['icon' => '🔨', 'name' => 'Ujenzi & Ufundi', 'count' => '290+'],
                        ['icon' => '🚚', 'name' => 'Usafiri', 'count' => '120+'],
                        ['icon' => '📚', 'name' => 'Elimu', 'count' => '95+'],
                        ['icon' => '🏥', 'name' => 'Afya', 'count' => '75+'],
                        ['icon' => '🌱', 'name' => 'Kilimo', 'count' => '110+'],
                        ['icon' => '🏠', 'name' => 'Nyumbani', 'count' => '200+'],
                        ['icon' => '🎭', 'name' => 'Burudani', 'count' => '85+'],
                        ['icon' => '📋', 'name' => 'Ofisi', 'count' => '160+'],
                    ];";

$categoriesNew = "                    \$categories = [
                        ['name' => 'Teknolojia & IT', 'count' => '340+'],
                        ['name' => 'Ubunifu & Sanaa', 'count' => '220+'],
                        ['name' => 'Uandishi & Tafsiri', 'count' => '180+'],
                        ['name' => 'Masoko Dijitali', 'count' => '150+'],
                        ['name' => 'Ujenzi & Ufundi', 'count' => '290+'],
                        ['name' => 'Usafiri', 'count' => '120+'],
                        ['name' => 'Elimu', 'count' => '95+'],
                        ['name' => 'Afya', 'count' => '75+'],
                        ['name' => 'Kilimo', 'count' => '110+'],
                        ['name' => 'Nyumbani', 'count' => '200+'],
                        ['name' => 'Burudani', 'count' => '85+'],
                        ['name' => 'Ofisi', 'count' => '160+'],
                    ];";

$content = str_replace($categoriesOld, $categoriesNew, $content);

// Categories Blade
$categoriesBladeOld = "<span class=\"text-3xl group-hover:scale-110 transition-transform duration-300\">{{ \$cat['icon'] }}</span>";
$categoriesBladeNew = "<img src=\"{{ asset('icon.png') }}\" class=\"w-10 h-10 object-contain drop-shadow-sm group-hover:scale-110 transition-transform duration-300\" alt=\"{{ \$cat['name'] }}\" />";
$content = str_replace($categoriesBladeOld, $categoriesBladeNew, $content);

// Features Array
$featuresOld = "                    \$features = [
                        ['icon' => '🛡️', 'title' => 'Malipo Salama', 'desc' => 'Pesa yako iko salama kwenye escrow. Hakuna hatari — lipa tu ukiridhika.'],
                        ['icon' => '⚡', 'title' => 'Haraka Sana', 'desc' => 'Tuma kazi leo, pata maombi ndani ya saa moja. Kazi za haraka kwa kila mtu.'],
                        ['icon' => '📍', 'title' => 'Kazi Karibu Nawe', 'desc' => 'Tafuta wafanyakazi au kazi karibu na eneo lako kwa GPS. Rahisi na sahihi.'],
                        ['icon' => '📱', 'title' => 'Inafanya Kazi kwa Simu Yoyote', 'desc' => 'Imejengwa kwa simu za bei nafuu. Haina haja ya simu ya gharama kubwa.'],
                        ['icon' => '💰', 'title' => 'Lipa kwa M-Pesa', 'desc' => 'Lipa na pokea malipo kwa M-Pesa, TigoPesa, au AirtelMoney. Rahisi!'],
                        ['icon' => '⭐', 'title' => 'Rating na Mapitio', 'desc' => 'Angalia ukadiriaji wa wafanyakazi kabla ya kuwaajiri. Ubora unadhihirika.'],
                    ];";

$featuresNew = "                    \$features = [
                        ['title' => 'Malipo Salama', 'desc' => 'Pesa yako iko salama kwenye escrow. Hakuna hatari — lipa tu ukiridhika.'],
                        ['title' => 'Haraka Sana', 'desc' => 'Tuma kazi leo, pata maombi ndani ya saa moja. Kazi za haraka kwa kila mtu.'],
                        ['title' => 'Kazi Karibu Nawe', 'desc' => 'Tafuta wafanyakazi au kazi karibu na eneo lako kwa GPS. Rahisi na sahihi.'],
                        ['title' => 'Inafanya Kazi kwa Simu Yoyote', 'desc' => 'Imejengwa kwa simu za bei nafuu. Haina haja ya simu ya gharama kubwa.'],
                        ['title' => 'Lipa kwa M-Pesa', 'desc' => 'Lipa na pokea malipo kwa M-Pesa, TigoPesa, au AirtelMoney. Rahisi!'],
                        ['title' => 'Rating na Mapitio', 'desc' => 'Angalia ukadiriaji wa wafanyakazi kabla ya kuwaajiri. Ubora unadhihirika.'],
                    ];";
$content = str_replace($featuresOld, $featuresNew, $content);

// Features Blade
$featuresBladeOld = "<span class=\"text-3xl mb-4 block group-hover:scale-110 transition-transform duration-300\">{{ \$feature['icon'] }}</span>";
$featuresBladeNew = "<div class=\"w-12 h-12 mb-4 bg-winga-100 dark:bg-winga-900/30 p-2.5 rounded-xl group-hover:scale-110 transition-transform duration-300\"><img src=\"{{ asset('icon.png') }}\" class=\"w-full h-full object-contain\" alt=\"Icon\" /></div>";
$content = str_replace($featuresBladeOld, $featuresBladeNew, $content);

file_put_contents('resources/views/welcome.blade.php', $content);
