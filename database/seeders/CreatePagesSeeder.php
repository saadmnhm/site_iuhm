<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class CreatePagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Articles - L'Observateur Urbain
        Page::updateOrCreate(
            ['slug' => 'observateur-urbain'],
            [
                'title' => [
                    'en' => 'L\'Observateur Urbain',
                    'fr' => 'L\'Observateur Urbain',
                    'ar' => 'المراقب الحضري',
                ],
                'excerpt' => [
                    'en' => 'In-depth analyses, on-the-ground reports, and visions for a more inclusive and sustainable city.',
                    'fr' => 'Analyses approfondies, reportages de terrain et visions pour une ville plus inclusive et durable.',
                    'ar' => 'تحليلات متعمقة وتقارير ميدانية ورؤى لمدينة أكثر شمولاً واستدامة.',
                ],
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => 1,
            ]
        );

        // Services
        Page::updateOrCreate(
            ['slug' => 'services'],
            [
                'title' => [
                    'en' => 'Services',
                    'fr' => 'Services',
                    'ar' => 'الخدمات',
                ],
                'excerpt' => [
                    'en' => 'At the service of our urban community.',
                    'fr' => 'Au service de notre communauté urbaine.',
                    'ar' => 'في خدمة مجتمعنا الحضري.',
                ],
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => 2,
            ]
        );

        // About
        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => [
                    'en' => 'About Us',
                    'fr' => 'À Propos',
                    'ar' => 'عن الهيئة',
                ],
                'excerpt' => [
                    'en' => 'Building Tomorrow, Together in Casablanca.',
                    'fr' => 'Bâtir Demain, Ensemble à Casablanca.',
                    'ar' => 'بناء الغد معاً في الدار البيضاء.',
                ],
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => 3,
            ]
        );

        // Resources
        Page::updateOrCreate(
            ['slug' => 'resources'],
            [
                'title' => [
                    'en' => 'Resource Library',
                    'fr' => 'Bibliothèque de Ressources',
                    'ar' => 'مكتبة الموارد',
                ],
                'excerpt' => [
                    'en' => 'Access our complete collection of publications, guides and research reports.',
                    'fr' => 'Accédez à notre collection complète de publications, guides et rapports de recherche.',
                    'ar' => 'الوصول إلى مجموعتنا الكاملة من المنشورات والأدلة وتقارير البحث.',
                ],
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => 4,
            ]
        );
    }
}
