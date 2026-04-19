<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class WingaSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $muajili = Role::firstOrCreate(['name' => 'muajili']); // Employer
        $mfanyakazi = Role::firstOrCreate(['name' => 'mfanyakazi']); // Worker
        $admin = Role::firstOrCreate(['name' => 'admin']);

        // Permissions
        $permissions = [
            'post-job', 'edit-job', 'delete-job', 'manage-applications',
            'generate-code', 'view-escrow',
            'apply-job', 'enter-code', 'manage-portfolio', 'withdraw-funds',
            'manage-users', 'manage-disputes', 'view-analytics', 'manage-categories',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $muajili->syncPermissions([
            'post-job', 'edit-job', 'delete-job', 'manage-applications',
            'generate-code', 'view-escrow',
        ]);

        $mfanyakazi->syncPermissions([
            'apply-job', 'enter-code', 'manage-portfolio', 'withdraw-funds',
        ]);

        $admin->syncPermissions(Permission::all());

        // Categories with Kiswahili names
        $categories = [
            ['name' => 'Teknolojia & IT', 'icon' => '💻', 'description' => 'Programu, tovuti, simu na zaidi'],
            ['name' => 'Ubunifu & Sanaa', 'icon' => '🎨', 'description' => 'Michoro, logo, video na picha'],
            ['name' => 'Uandishi & Tafsiri', 'icon' => '✍️', 'description' => 'Makala, blog, tafsiri na uhariri'],
            ['name' => 'Masoko Dijitali', 'icon' => '📱', 'description' => 'SEO, mitandao ya kijamii, matangazo'],
            ['name' => 'Usimamizi & Ofisi', 'icon' => '📋', 'description' => 'Uhasibu, data entry, msaada wa mteja'],
            ['name' => 'Ujenzi & Ufundi', 'icon' => '🔨', 'description' => 'Ujenzi, umeme, mabomba, rangi'],
            ['name' => 'Usafiri & Ushirikishaji', 'icon' => '🚚', 'description' => 'Uwasilishaji, usafiri, logistics'],
            ['name' => 'Elimu & Mafunzo', 'icon' => '📚', 'description' => 'Taaluma, lugha, stadi za kazi'],
            ['name' => 'Afya & Ustawi', 'icon' => '🏥', 'description' => 'Huduma za afya, lishe, mazoezi'],
            ['name' => 'Kilimo & Mazingira', 'icon' => '🌱', 'description' => 'Kilimo, bustani, mazingira'],
            ['name' => 'Nyumbani & Usafi', 'icon' => '🏠', 'description' => 'Usafi, kupika, utunzaji wa nyumba'],
            ['name' => 'Burudani & Matukio', 'icon' => '🎭', 'description' => 'Muziki, DJ, MC, sherehe'],
        ];

        foreach ($categories as $catData) {
            Category::firstOrCreate(
                ['slug' => Str::slug($catData['name'])],
                array_merge($catData, ['slug' => Str::slug($catData['name'])])
            );
        }

        // Skills
        $skills = [
            'Teknolojia & IT' => ['PHP', 'Laravel', 'JavaScript', 'React', 'Python', 'WordPress', 'Mobile App', 'UI/UX Design', 'Database', 'API Development'],
            'Ubunifu & Sanaa' => ['Photoshop', 'Illustrator', 'Video Editing', 'Logo Design', 'Animation', 'Photography', '3D Modeling'],
            'Uandishi & Tafsiri' => ['Content Writing', 'Copywriting', 'Kiswahili-English Translation', 'Proofreading', 'Blog Writing', 'Academic Writing'],
            'Ujenzi & Ufundi' => ['Uashi', 'Umeme', 'Mabomba', 'Rangi', 'Seremala', 'Welding', 'Tile Fitting'],
            'Kilimo & Mazingira' => ['Kilimo cha Mboga', 'Bustani', 'Ufugaji', 'Irrigation', 'Permaculture'],
        ];

        foreach ($skills as $categoryName => $skillNames) {
            $category = Category::where('name', $categoryName)->first();
            if ($category) {
                foreach ($skillNames as $skillName) {
                    Skill::firstOrCreate(
                        ['slug' => Str::slug($skillName)],
                        [
                            'name' => $skillName,
                            'slug' => Str::slug($skillName),
                            'category_id' => $category->id,
                        ]
                    );
                }
            }
        }
    }
}
