<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Job;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $employers = User::query()
            ->where('role', 'muajili')
            ->orWhereHas('roles', fn ($q) => $q->where('name', 'muajili'))
            ->get();

        if ($employers->isEmpty()) {
            // Dedicated seed user (not juma@example.com — that is the mteja in DatabaseSeeder)
            $fallbackEmployer = User::updateOrCreate(
                ['email' => 'seed-muajili@example.com'],
                [
                    'name' => 'Seed Muajili',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'phone' => '+255712345679',
                    'location' => 'Dar es Salaam',
                    'onboarding_completed' => true,
                    'role' => 'mteja',
                ]
            );
            $fallbackEmployer->syncRoles(['muajili']);
            $employers = collect([$fallbackEmployer]);
        }

        $categories = Category::where('is_active', true)->get()->keyBy('slug');
        $skillsBySlug = Skill::all()->keyBy('slug');

        $jobs = [
            [
                'title' => 'Fundi Bomba — Kukarabati Mabomba Nyumbani',
                'description' => 'Nahitaji mfundi bomba mwenye ujuzi wa kukarabati mabomba yaliyovuja katika bafu na jikoni. Kazi ni ndani ya Dar es Salaam, Sinza. Lazima uwe na zana zako.',
                'location' => 'Sinza, Dar es Salaam',
                'budget_min' => 45000,
                'budget_max' => 80000,
                'budget_type' => 'fixed',
                'urgency' => 'urgent',
                'category' => 'ujenzi-ufundi',
                'skills' => ['mabomba', 'umeme'],
            ],
            [
                'title' => 'Kupaka Rangi Nyumba — Mikocheni',
                'description' => 'Nyumba ya vyumba 3 inahitaji kupakwa rangi nyeupe ndani na nje. Eneo: Mikocheni B. Tafadhali toa bei ya kazi yote.',
                'location' => 'Mikocheni, Dar es Salaam',
                'budget_min' => 350000,
                'budget_max' => 500000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'ujenzi-ufundi',
                'skills' => ['rangi'],
            ],
            [
                'title' => 'Kutengeneza Tovuti ya Biashara',
                'description' => 'Nahitaji tovuti rahisi kwa biashara yangu ya bidhaa. Inahitaji ukurasa wa kuonyesha bidhaa, wasiliana nasi, na fomu ya maoni. Prefer Laravel au WordPress.',
                'location' => 'Dar es Salaam (inaweza kufanyika remote)',
                'budget_min' => 800000,
                'budget_max' => 1500000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'teknolojia-it',
                'skills' => ['php', 'laravel', 'wordpress', 'ui-ux-design'],
            ],
            [
                'title' => 'Usafi wa Ofisi — Wiki 2',
                'description' => 'Ofisi yetu inahitaji usafi wa kila siku kwa wiki 2 (siku 10). Eneo: Masaki. Mwisho wa kazi ni saa 6 jioni.',
                'location' => 'Masaki, Dar es Salaam',
                'budget_min' => 25000,
                'budget_max' => 35000,
                'budget_type' => 'hourly',
                'urgency' => 'normal',
                'category' => 'nyumbani-usafi',
                'skills' => [],
            ],
            [
                'title' => 'Kutafsiri Makala 50 — Kiingereza hadi Kiswahili',
                'description' => 'Nina makala za kiingereza za biashara (karibu 50 kurasa) zinazohitaji kutafsiriwa kwa Kiswahili. Lazima uwe mtaalamu wa lugha hizo mbili.',
                'location' => 'Remote',
                'budget_min' => 500000,
                'budget_max' => 750000,
                'budget_type' => 'fixed',
                'urgency' => 'urgent',
                'category' => 'uandishi-tafsiri',
                'skills' => ['kiswahili-english-translation', 'proofreading'],
            ],
            [
                'title' => 'Umeme — Kuweka Taa na Vituo Nyumbani',
                'description' => 'Nyumba mpya inahitaji ufungaji wa umeme: taa, vituo, na paneli. Eneo: Mbezi Beach. Mfundi anayejua kazi ya kawaida na solar ni sawa.',
                'location' => 'Mbezi Beach, Dar es Salaam',
                'budget_min' => 600000,
                'budget_max' => 900000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'ujenzi-ufundi',
                'skills' => ['umeme'],
            ],
            [
                'title' => 'Kubuni Logo na Branding ya Kampuni',
                'description' => 'Kampuni yangu mpya inahitaji logo, rangi za brand, na mwongozo mdogo wa matumizi. Tafadhali onyesha portfolio yako ya logo design.',
                'location' => 'Remote',
                'budget_min' => 200000,
                'budget_max' => 400000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'ubunifu-sanaa',
                'skills' => ['logo-design', 'photoshop', 'illustrator'],
            ],
            [
                'title' => 'Data Entry — Kuandika Data 500 Rows',
                'description' => 'Nina spreadsheet yenye data inayohitaji kuandikwa kwenye mfumo wetu. Karibu rows 500. Inaweza kufanyika kwa vipande. Muda: wiki 1.',
                'location' => 'Remote',
                'budget_min' => 150000,
                'budget_max' => 220000,
                'budget_type' => 'fixed',
                'urgency' => 'very_urgent',
                'category' => 'usimamizi-ofisi',
                'skills' => ['database'],
            ],
            [
                'title' => 'Uashi — Kujenga Ukuta wa Nyumba',
                'description' => 'Nahitaji fundi wa uashi kujenga ukuta wa mita 15. Eneo: Kibaha. Vifaa tayari. Kazi inaanza wiki ijayo.',
                'location' => 'Kibaha, Pwani',
                'budget_min' => 400000,
                'budget_max' => 600000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'ujenzi-ufundi',
                'skills' => ['uashi'],
            ],
            [
                'title' => 'Video Editing — Sherehe ya Ndoa (Saa 3 za footage)',
                'description' => 'Nina video za sherehe ya ndoa (takriban saa 3) zinazohitaji kukatwa, kuongezewa muziki, na kutoa video ya dakika 15–20. Tafadhali onyesha kazi zako zilizopita.',
                'location' => 'Arusha',
                'budget_min' => 300000,
                'budget_max' => 500000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'ubunifu-sanaa',
                'skills' => ['video-editing'],
            ],
            [
                'title' => 'Kulima Shamba — Kupanda na Kuvuna Mboga',
                'description' => 'Shamba dogo (ekari 1) linahitaji kulimwa, kupandwa mboga (mchicha, kabichi, karoti) na kuvunwa. Eneo: Morogoro. Mwezi 1–2.',
                'location' => 'Morogoro',
                'budget_min' => 800000,
                'budget_max' => 1200000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'kilimo-mazingira',
                'skills' => ['kilimo-cha-mboga', 'bustani'],
            ],
            [
                'title' => 'Mobile App — App ya Android ya Kuuza Bidhaa',
                'description' => 'Nahitaji app ya Android rahisi ya duka la mtandao (listing bidhaa, cart, checkout). Backend ipo. Inahitaji developer wa Android/React Native.',
                'location' => 'Remote',
                'budget_min' => 1500000,
                'budget_max' => 2500000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'teknolojia-it',
                'skills' => ['mobile-app', 'react', 'api-development'],
            ],
            [
                'title' => 'Usafi wa Nyumba — Leo',
                'description' => 'Nyumba ya vyumba 2 inahitaji usafi wa kina leo. Eneo: Oyster Bay. Saa 8 asubuhi. Bei ni kwa siku moja.',
                'location' => 'Oyster Bay, Dar es Salaam',
                'budget_min' => 30000,
                'budget_max' => 45000,
                'budget_type' => 'fixed',
                'urgency' => 'very_urgent',
                'category' => 'nyumbani-usafi',
                'skills' => [],
            ],
            [
                'title' => 'Content Writing — Makala 10 za Blog (Kiswahili)',
                'description' => 'Nahitaji makala 10 za blog kwa tovuti yangu (Kiswahili). Kila makala 500–700 maneno. Mada: afya na lishe. Wiki 2.',
                'location' => 'Remote',
                'budget_min' => 200000,
                'budget_max' => 350000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'uandishi-tafsiri',
                'skills' => ['content-writing', 'blog-writing'],
            ],
            [
                'title' => 'Seremala — Kutengeneza Meza na Vitanda',
                'description' => 'Nahitaji seremala kutengeneza meza 2 na vitanda 3 kwa nyumba. Mbao ziko. Eneo: Mwanza.',
                'location' => 'Mwanza',
                'budget_min' => 550000,
                'budget_max' => 850000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'ujenzi-ufundi',
                'skills' => ['seremala'],
            ],
            [
                'title' => 'SEO — Kuongeza Traffic Tovuti',
                'description' => 'Tovuti yangu ya biashara inahitaji kurekebishwa kwa SEO (meta, headings, content kidogo). Target: kuongezeka traffic kwa miezi 3.',
                'location' => 'Remote',
                'budget_min' => 400000,
                'budget_max' => 700000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'masoko-dijitali',
                'skills' => ['database'],
            ],
            [
                'title' => 'Uhasibu — Kufanya Books kwa Mwezi 1',
                'description' => 'Biashara ndogo inahitaji mhasibu kufanya books kwa mwezi uliopita: entries, trial balance, balance sheet. Mwezi 1 uliopita tu.',
                'location' => 'Dar es Salaam',
                'budget_min' => 150000,
                'budget_max' => 250000,
                'budget_type' => 'fixed',
                'urgency' => 'urgent',
                'category' => 'usimamizi-ofisi',
                'skills' => [],
            ],
            [
                'title' => 'Tile Fitting — Bafu na Jikoni',
                'description' => 'Nahitaji mtaalamu wa kuweka tiles bafuni na jikoni. Eneo: Kawe, Dar. Nafasi za tiles zimeandaliwa.',
                'location' => 'Kawe, Dar es Salaam',
                'budget_min' => 700000,
                'budget_max' => 1100000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'ujenzi-ufundi',
                'skills' => ['tile-fitting'],
            ],
            [
                'title' => 'Welding — Kutatua Mlango wa Chuma na Kuingiza Gate',
                'description' => 'Nahitaji fundi wa welding kutatua mlango wa chuma na kuweka gate mpya. Eneo: Tegeta, Dar es Salaam.',
                'location' => 'Tegeta, Dar es Salaam',
                'budget_min' => 250000,
                'budget_max' => 400000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'ujenzi-ufundi',
                'skills' => ['welding'],
            ],
            [
                'title' => 'Mafunzo ya Excel — Wafanyakazi 5',
                'description' => 'Nahitaji mfundishaji wa Excel kwa wafanyakazi 5 (formulas, pivot, charts). Saa 4 za mafunzo. Eneo: ofisi yetu, Dar.',
                'location' => 'Dar es Salaam',
                'budget_min' => 200000,
                'budget_max' => 350000,
                'budget_type' => 'fixed',
                'urgency' => 'normal',
                'category' => 'elimu-mafunzo',
                'skills' => [],
            ],
        ];

        foreach ($jobs as $index => $data) {
            $category = $categories->get($data['category']);
            $employer = $employers->random();

            $job = Job::create([
                'employer_id' => $employer->id,
                'category_id' => $category?->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'requirements' => null,
                'location' => $data['location'],
                'latitude' => null,
                'longitude' => null,
                'budget_min' => $data['budget_min'],
                'budget_max' => $data['budget_max'],
                'budget_type' => $data['budget_type'],
                'duration' => 'mwezi 1',
                'status' => 'open',
                'urgency' => $data['urgency'],
                'remote_allowed' => str_contains(strtolower($data['location'] ?? ''), 'remote'),
                'views_count' => random_int(5, 150),
                'applications_count' => random_int(0, 8),
            ]);

            $skillSlugs = $data['skills'] ?? [];
            foreach ($skillSlugs as $slug) {
                $skill = $skillsBySlug->get($slug);
                if ($skill) {
                    $job->skills()->attach($skill->id);
                }
            }
        }
    }
}
