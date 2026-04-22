<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Post;
use App\Models\Section;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::query()->updateOrCreate([
            'email' => 'admin@iuhm.test',
        ], [
            'name' => 'IUHM Admin',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $homePage = Page::query()->updateOrCreate([
            'slug' => 'home',
        ], [
            'title' => [
                'en' => 'Build Your Future at IUHM',
                'fr' => 'Construisez votre avenir a IUHM',
                'ar' => 'ابن مستقبلك في IUHM',
            ],
            'excerpt' => [
                'en' => 'A modern learning environment focused on innovation, employability, and real impact.',
                'fr' => 'Un environnement d apprentissage moderne axe sur l innovation, l employabilite et l impact reel.',
                'ar' => 'بيئة تعليمية حديثة تركز على الابتكار والجاهزية المهنية والتاثير الحقيقي.',
            ],
            'hero_image' => null,
            'is_published' => true,
            'published_at' => now()->subDay(),
            'sort_order' => 0,
        ]);

        Page::query()->updateOrCreate([
            'slug' => 'about',
        ], [
            'title' => [
                'en' => 'About IUHM',
                'fr' => 'A propos de IUHM',
                'ar' => 'حول IUHM',
            ],
            'excerpt' => [
                'en' => 'Discover our mission, values, and academic approach.',
                'fr' => 'Decouvrez notre mission, nos valeurs et notre approche academique.',
                'ar' => 'تعرف على رسالتنا وقيمنا ونهجنا الاكاديمي.',
            ],
            'hero_image' => null,
            'is_published' => true,
            'published_at' => now()->subDays(2),
            'sort_order' => 1,
        ]);

        Section::query()->updateOrCreate([
            'page_id' => $homePage->id,
            'key' => 'programs_overview',
        ], [
            'heading' => [
                'en' => 'Academic Programs Built for Industry',
                'fr' => 'Des programmes academiques concus pour l industrie',
                'ar' => 'برامج اكاديمية مصممة لسوق العمل',
            ],
            'body' => [
                'en' => 'From management to applied technologies, every track blends theory with project-based practice.',
                'fr' => 'De la gestion aux technologies appliquees, chaque parcours combine theorie et projets pratiques.',
                'ar' => 'من الادارة الى التقنيات التطبيقية، يجمع كل مسار بين المعرفة النظرية والتطبيق العملي.',
            ],
            'cta_label' => [
                'en' => 'View Programs',
                'fr' => 'Voir les programmes',
                'ar' => 'عرض البرامج',
            ],
            'cta_url' => '/news',
            'image_path' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Section::query()->updateOrCreate([
            'page_id' => $homePage->id,
            'key' => 'student_life',
        ], [
            'heading' => [
                'en' => 'Campus Life and Student Experience',
                'fr' => 'Vie de campus et experience etudiante',
                'ar' => 'الحياة الجامعية وتجربة الطالب',
            ],
            'body' => [
                'en' => 'Join clubs, events, and mentoring initiatives designed to help you grow beyond the classroom.',
                'fr' => 'Rejoignez des clubs, des evenements et des initiatives de mentorat pour progresser au-dela des cours.',
                'ar' => 'انضم الى الاندية والفعاليات وبرامج التوجيه التي تطور مهاراتك خارج القاعات الدراسية.',
            ],
            'cta_label' => [
                'en' => 'Explore Student Life',
                'fr' => 'Explorer la vie etudiante',
                'ar' => 'اكتشف الحياة الطلابية',
            ],
            'cta_url' => '/news',
            'image_path' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Post::query()->updateOrCreate([
            'slug' => 'iuhm-launches-innovation-lab',
        ], [
            'page_id' => $homePage->id,
            'section_id' => null,
            'user_id' => $admin->id,
            'title' => [
                'en' => 'IUHM Launches New Innovation Lab',
                'fr' => 'IUHM lance un nouveau laboratoire d innovation',
                'ar' => 'IUHM يطلق مختبر الابتكار الجديد',
            ],
            'excerpt' => [
                'en' => 'The campus opened a multi-disciplinary lab to accelerate student projects and startup ideas.',
                'fr' => 'Le campus a ouvert un laboratoire pluridisciplinaire pour accelerer les projets et idees de startup.',
                'ar' => 'افتتحت الجامعة مختبرا متعدد التخصصات لتسريع مشاريع الطلاب وافكار الشركات الناشئة.',
            ],
            'content' => [
                'en' => 'The new lab includes rapid prototyping stations, collaborative spaces, and weekly mentorship sessions with industry experts.',
                'fr' => 'Le nouveau laboratoire comprend des stations de prototypage rapide, des espaces collaboratifs et des sessions hebdomadaires de mentorat avec des experts du secteur.',
                'ar' => 'يضم المختبر الجديد محطات نمذجة سريعة ومساحات تعاون وجلسات ارشاد اسبوعية مع خبراء من القطاع.',
            ],
            'featured_image' => null,
            'is_published' => true,
            'published_at' => now()->subHours(8),
        ]);

        Post::query()->updateOrCreate([
            'slug' => 'career-week-2026',
        ], [
            'page_id' => $homePage->id,
            'section_id' => null,
            'user_id' => $admin->id,
            'title' => [
                'en' => 'Career Week 2026 Opens with 40+ Employers',
                'fr' => 'La semaine de l emploi 2026 s ouvre avec plus de 40 employeurs',
                'ar' => 'انطلاق اسبوع الوظائف 2026 بمشاركة اكثر من 40 جهة توظيف',
            ],
            'excerpt' => [
                'en' => 'Students can connect directly with companies through workshops, interviews, and portfolio reviews.',
                'fr' => 'Les etudiants peuvent echanger directement avec les entreprises via des ateliers, entretiens et revues de portfolio.',
                'ar' => 'يستطيع الطلاب التواصل مباشرة مع الشركات عبر ورش العمل والمقابلات ومراجعات الملفات المهنية.',
            ],
            'content' => [
                'en' => 'Career Week features mock interviews, CV coaching sessions, and networking events hosted by partner organizations.',
                'fr' => 'La semaine de l emploi propose des simulations d entretien, des sessions de coaching CV et des evenements de reseautage avec les partenaires.',
                'ar' => 'يتضمن اسبوع الوظائف مقابلات تجريبية وجلسات تطوير السيرة الذاتية وفعاليات تواصل مع شركاء الجامعة.',
            ],
            'featured_image' => null,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
    }
}
