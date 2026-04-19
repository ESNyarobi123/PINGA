<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Job;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Demo Winga (Worker) ──────────────────────────────────────────────
        $winga = User::firstOrCreate(
            ['email' => 'winga@gmail.com'],
            [
                'name'                 => 'Ali Juma',
                'email_verified_at'    => now(),
                'password'             => Hash::make('ESNyarobi@1234'),
                'phone'                => '+255744000001',
                'role'                 => 'winga',
                'bio'                  => 'Mtaalamu wa teknolojia, programu ya kompyuta, na IT. Nina uzoefu wa miaka 5 katika Laravel, React Native, na Python. Nimewahi kushirikiana na makampuni kadhaa ya Tanzania na Afrika Mashariki. Napenda kazi za haraka, ubora wa juu, na mawasiliano mazuri na wateja wangu.',
                'location'             => 'Kinondoni, Dar es Salaam',
                'wallet_balance'       => 15000,
                'onboarding_completed' => true,
                'two_factor_secret'    => null,
                'two_factor_recovery_codes' => null,
            ]
        );

        // ── Demo Mteja (Employer) ────────────────────────────────────────────
        $mteja = User::firstOrCreate(
            ['email' => 'mteja@gmail.com'],
            [
                'name'                 => 'Fatuma Ngozi',
                'email_verified_at'    => now(),
                'password'             => Hash::make('ESNyarobi@1234'),
                'phone'                => '+255755000002',
                'role'                 => 'mteja',
                'bio'                  => 'Mfanyabiashara mwenye uzoefu wa miaka 8 katika biashara ya rejareja na huduma. Ninamiliki biashara kadhaa Dar es Salaam na Arusha. Napenda kufanya kazi na wataalamu wa ndani ya Tanzania wanaojua kazi yao vizuri.',
                'location'             => 'Masaki, Dar es Salaam',
                'wallet_balance'       => 500000,
                'onboarding_completed' => true,
                'two_factor_secret'    => null,
                'two_factor_recovery_codes' => null,
            ]
        );

        // ── Load categories & skills ─────────────────────────────────────────
        $cats   = Category::all()->keyBy('slug');
        $skills = Skill::all()->keyBy('slug');

        // ── 15 Jobs posted by mteja ──────────────────────────────────────────
        $jobs = [
            // 1 — Teknolojia & IT
            [
                'title'       => 'Kutengeneza Tovuti ya Duka la Mtandao',
                'description' => 'Nahitaji mtaalamu wa kutengeneza tovuti ya duka la mtandao kwa biashara yangu. Tovuti iwe na ukurasa wa bidhaa, cart ya ununuzi, na mfumo wa malipo. Itumie Laravel na Vue.js. Ninapenda design ya kisasa na inayofanya kazi vizuri kwenye simu. Tafadhali onyesha kazi zako za awali.',
                'location'    => 'Remote',
                'budget_min'  => 900000,
                'budget_max'  => 1800000,
                'budget_type' => 'fixed',
                'duration'    => 'miezi 2',
                'urgency'     => 'normal',
                'category'    => 'teknolojia-it',
                'skills'      => ['laravel', 'php', 'ui-ux-design'],
            ],
            // 2 — Ubunifu & Sanaa
            [
                'title'       => 'Kubuni Logo na Brand Identity ya Kampuni',
                'description' => 'Kampuni yangu mpya ya ushauri wa biashara (MH Consulting) inahitaji logo nzuri, rangi za brand, na mwongozo wa matumizi. Ninapenda logo ya kisasa na ya kitaalamu. Tafadhali toa mifano 3 na bei ya jumla.',
                'location'    => 'Remote',
                'budget_min'  => 250000,
                'budget_max'  => 500000,
                'budget_type' => 'fixed',
                'duration'    => 'wiki 1',
                'urgency'     => 'normal',
                'category'    => 'ubunifu-sanaa',
                'skills'      => ['logo-design', 'illustrator', 'photoshop'],
            ],
            // 3 — Uandishi & Tafsiri
            [
                'title'       => 'Kutafsiri Mkataba wa Kibiashara (Kiingereza → Kiswahili)',
                'description' => 'Nina mkataba wa biashara wa kurasa 12 kwa Kiingereza unaohitaji kutafsiriwa kwa Kiswahili rasmi. Tafsiri lazima iwe sahihi na inayofaa kisheria. Haraka inahitajika — siku 3.',
                'location'    => 'Remote',
                'budget_min'  => 120000,
                'budget_max'  => 200000,
                'budget_type' => 'fixed',
                'duration'    => 'siku 3',
                'urgency'     => 'urgent',
                'category'    => 'uandishi-tafsiri',
                'skills'      => ['kiswahili-english-translation', 'proofreading'],
            ],
            // 4 — Masoko Dijitali
            [
                'title'       => 'Kusimamia Mitandao ya Kijamii — Miezi 3',
                'description' => 'Biashara yangu ya vyakula inahitaji mtu wa kusimamia Instagram, Facebook, na TikTok. Angalau machapisho 5 kwa wiki, kujibu macomment, na kutoa ripoti ya mwezi. Una uzoefu wa kufanya biashara za vyakula kukua mtandaoni?',
                'location'    => 'Remote / Dar es Salaam',
                'budget_min'  => 200000,
                'budget_max'  => 350000,
                'budget_type' => 'hourly',
                'duration'    => 'miezi 3',
                'urgency'     => 'normal',
                'category'    => 'masoko-dijitali',
                'skills'      => [],
            ],
            // 5 — Usimamizi & Ofisi
            [
                'title'       => 'Uhasibu wa Mwaka — Kuandaa Hesabu za Kampuni',
                'description' => 'Kampuni yangu ndogo inahitaji mhasibu kuandaa hesabu za mwaka mzima: P&L, balance sheet, na ripoti ya kodi. Biashara ni ndogo — transactions karibu 300 kwa mwaka. Nitatoa access ya QuickBooks.',
                'location'    => 'Dar es Salaam / Remote',
                'budget_min'  => 300000,
                'budget_max'  => 500000,
                'budget_type' => 'fixed',
                'duration'    => 'wiki 2',
                'urgency'     => 'urgent',
                'category'    => 'usimamizi-ofisi',
                'skills'      => [],
            ],
            // 6 — Ujenzi & Ufundi
            [
                'title'       => 'Ufungaji wa Umeme — Nyumba Mpya Mbezi Beach',
                'description' => 'Nyumba mpya ya ghorofa 2 inahitaji ufungaji kamili wa umeme: taa, vituo vya umeme, paneli kuu, na wiring. Karibu chumba 8. Eneo: Mbezi Beach. Vifaa ni vya mwenye kazi, fundi aje na ujuzi na zana tu.',
                'location'    => 'Mbezi Beach, Dar es Salaam',
                'budget_min'  => 1200000,
                'budget_max'  => 2000000,
                'budget_type' => 'fixed',
                'duration'    => 'wiki 2',
                'urgency'     => 'normal',
                'category'    => 'ujenzi-ufundi',
                'skills'      => ['umeme'],
            ],
            // 7 — Nyumbani & Usafi
            [
                'title'       => 'Msaidizi wa Nyumbani — Usafi na Kupika (Wiki 1)',
                'description' => 'Ninahitaji msaidizi wa nyumbani kwa wiki 1 kufanya usafi wa kina wa nyumba nzima na kuandaa chakula cha mchana na usiku. Nyumba: vyumba 4. Eneo: Msasani, Dar es Salaam. Chakula ni cha kawaida cha Kitanzania.',
                'location'    => 'Msasani, Dar es Salaam',
                'budget_min'  => 70000,
                'budget_max'  => 120000,
                'budget_type' => 'fixed',
                'duration'    => 'wiki 1',
                'urgency'     => 'urgent',
                'category'    => 'nyumbani-usafi',
                'skills'      => [],
            ],
            // 8 — Elimu & Mafunzo
            [
                'title'       => 'Mwalimu wa Kiingereza kwa Watoto (Darasa la 5–7)',
                'description' => 'Watoto wangu wawili (darasa 5 na 7) wanahitaji mwalimu wa Kiingereza mara 3 kwa wiki. Masomo nyumbani kwetu, Mikocheni A. Saa 2 kwa siku. Ninahitaji mtu mwenye subira na uzoefu wa kufundisha watoto.',
                'location'    => 'Mikocheni, Dar es Salaam',
                'budget_min'  => 150000,
                'budget_max'  => 250000,
                'budget_type' => 'hourly',
                'duration'    => 'miezi 3',
                'urgency'     => 'normal',
                'category'    => 'elimu-mafunzo',
                'skills'      => [],
            ],
            // 9 — Afya & Ustawi
            [
                'title'       => 'Mshauri wa Lishe na Mpango wa Mazoezi (Miezi 2)',
                'description' => 'Ninatafuta mtaalamu wa lishe na mazoezi kutayarisha mpango wangu wa kupoteza uzito kwa njia ya afya. Ninahitaji mtu aweze kunisaidia kwa mashauri ya kila wiki na mpango wa chakula. Magonjwa: hakuna. Uzito wa sasa: 92kg, lengo 75kg.',
                'location'    => 'Dar es Salaam (online au uso kwa uso)',
                'budget_min'  => 200000,
                'budget_max'  => 400000,
                'budget_type' => 'fixed',
                'duration'    => 'miezi 2',
                'urgency'     => 'normal',
                'category'    => 'afya-ustawi',
                'skills'      => [],
            ],
            // 10 — Kilimo & Mazingira
            [
                'title'       => 'Kupanda Bustani ya Mboga — Nyumba Kibamba',
                'description' => 'Nina nafasi ya bustani nyuma ya nyumba (takriban mita 50 mraba). Ninahitaji mtu wa kusaidia kupanga, kulima, na kupanda mboga mbalimbali: nyanya, pilipili, mchicha, na matango. Mtu awe tayari kuja mara 2 kwa wiki.',
                'location'    => 'Kibamba, Dar es Salaam',
                'budget_min'  => 80000,
                'budget_max'  => 150000,
                'budget_type' => 'fixed',
                'duration'    => 'miezi 1',
                'urgency'     => 'normal',
                'category'    => 'kilimo-mazingira',
                'skills'      => ['bustani', 'kilimo-cha-mboga'],
            ],
            // 11 — Burudani & Matukio
            [
                'title'       => 'MC wa Kitaalamu kwa Sherehe ya Ndoa — Desemba 2026',
                'description' => 'Ninahitaji MC mzuri wa Kiswahili kwa sherehe ya ndoa itakayofanyika Desemba 2026 Dar es Salaam. Wageni takriban 300. Sherehe itakuwa ya kisasa na ya Kitanzania. Tafadhali tuma video za kazi zako za awali.',
                'location'    => 'Dar es Salaam',
                'budget_min'  => 500000,
                'budget_max'  => 1000000,
                'budget_type' => 'fixed',
                'duration'    => 'siku 1',
                'urgency'     => 'normal',
                'category'    => 'burudani-matukio',
                'skills'      => [],
            ],
            // 12 — Usafiri & Ushirikishaji
            [
                'title'       => 'Delivery ya Bidhaa za Duka — Dar es Salaam Mjini',
                'description' => 'Duka langu la bidhaa za nyumbani linahitaji msaada wa uwasilishaji bidhaa kwa wateja mjini Dar. Takriban deliveries 10–20 kwa siku. Unahitaji pikipiki au baiskeli ya motorized na ujue maeneo ya Dar vizuri.',
                'location'    => 'Dar es Salaam',
                'budget_min'  => 80000,
                'budget_max'  => 150000,
                'budget_type' => 'hourly',
                'duration'    => 'miezi 2',
                'urgency'     => 'normal',
                'category'    => 'usafiri-ushirikishaji',
                'skills'      => [],
            ],
            // 13 — Teknolojia & IT
            [
                'title'       => 'Kurekebisha Mfumo wa POS wa Duka',
                'description' => 'Duka langu linatumia mfumo wa POS (Point of Sale) unaohitaji marekebisho ya haraka: kuongeza bidhaa mpya, kutengeneza ripoti za mauzo, na kurekebisha bug ndogo. Mfumo ni wa Windows, built na Python.',
                'location'    => 'Kariakoo, Dar es Salaam',
                'budget_min'  => 300000,
                'budget_max'  => 600000,
                'budget_type' => 'fixed',
                'duration'    => 'wiki 1',
                'urgency'     => 'urgent',
                'category'    => 'teknolojia-it',
                'skills'      => ['python', 'database'],
            ],
            // 14 — Ujenzi & Ufundi
            [
                'title'       => 'Kupaka Rangi Villa — Mbweni Dar es Salaam',
                'description' => 'Villa yangu ya vyumba 5 (ghorofa 2) inahitaji kupakwa rangi ndani na nje. Rangi itatolewa na mwenye nyumba (rangi nyeupe ndani, beige nje). Kazi ya siku 7–10. Lazima uje na wafanyakazi wako.',
                'location'    => 'Mbweni, Dar es Salaam',
                'budget_min'  => 800000,
                'budget_max'  => 1400000,
                'budget_type' => 'fixed',
                'duration'    => 'wiki 1',
                'urgency'     => 'normal',
                'category'    => 'ujenzi-ufundi',
                'skills'      => ['rangi'],
            ],
            // 15 — Uandishi & Tafsiri
            [
                'title'       => 'Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)',
                'description' => 'Tovuti yangu ya afya inahitaji makala 20 za blog kwa Kiswahili. Kila makala iwe na maneno 600–900, imeandikwa vizuri, na SEO-friendly. Mada zimekwisha wa: afya ya akili, lishe, mazoezi, na mama na mtoto. Nitatoa mada zote.',
                'location'    => 'Remote',
                'budget_min'  => 400000,
                'budget_max'  => 700000,
                'budget_type' => 'fixed',
                'duration'    => 'wiki 3',
                'urgency'     => 'normal',
                'category'    => 'uandishi-tafsiri',
                'skills'      => ['content-writing', 'blog-writing'],
            ],
        ];

        foreach ($jobs as $data) {
            $category = $cats->get($data['category']);

            $job = Job::create([
                'employer_id'    => $mteja->id,
                'category_id'    => $category?->id,
                'title'          => $data['title'],
                'description'    => $data['description'],
                'location'       => $data['location'],
                'budget_min'     => $data['budget_min'],
                'budget_max'     => $data['budget_max'],
                'budget_type'    => $data['budget_type'],
                'duration'       => $data['duration'],
                'status'         => 'open',
                'is_approved'    => true,
                'approved_at'    => now(),
                'urgency'        => $data['urgency'],
                'remote_allowed' => str_contains(strtolower($data['location']), 'remote'),
                'views_count'    => random_int(10, 200),
                'applications_count' => random_int(0, 10),
            ]);

            foreach (($data['skills'] ?? []) as $slug) {
                $skill = $skills->get($slug);
                if ($skill) {
                    $job->skills()->attach($skill->id);
                }
            }
        }

        $this->command->info('✅ DemoDataSeeder: created winga@gmail.com, mteja@gmail.com, and 15 approved jobs.');
    }
}
