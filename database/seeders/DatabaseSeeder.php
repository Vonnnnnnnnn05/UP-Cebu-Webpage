<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\News;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admins
        Admin::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'full_name' => 'TTBDO Super Administrator',
                'role' => 'superadmin',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'editor@gmail.com'],
            [
                'username' => 'editor',
                'password' => Hash::make('password'),
                'full_name' => 'TTBDO Content Editor',
                'role' => 'editor',
            ]
        );

        // 2. Seed News
        $newsItems = [
            [
                'title' => 'UP Cebu and DOST Regional Office VII Formalize Regional Innovation Hub Partnership',
                'slug' => 'up-cebu-dost-regional-innovation-hub-partnership',
                'category' => 'PARTNERSHIP',
                'badge_label' => 'FEATURED STORY',
                'summary' => 'The University of the Philippines Cebu, through the Technology Transfer and Business Development Office (TTBDO), collaborates with the Department of Science and Technology (DOST-VII) to establish dedicated prototyping and acceleration tracks for faculty and student spin-offs throughout Central Visayas.',
                'content' => '<p>The University of the Philippines Cebu, through the Technology Transfer and Business Development Office (TTBDO), collaborates with the Department of Science and Technology (DOST-VII) to establish dedicated prototyping and acceleration tracks for faculty and student spin-offs throughout Central Visayas.</p><p>This initiative leverages modern equipment, research mentorship, and acceleration tracks to empower regional innovators.</p>',
                'image_url' => 'assets/hero-campus.jpg',
                'author' => 'TTBDO Media Communications',
                'meeting_focus' => 'Innovation Hub & Prototyping Track',
                'startups_supported' => '12 Startups Assisted',
                'coverage_area' => 'Cebu & Region VII',
                'is_featured' => true,
                'is_published' => true,
                'published_date' => '2025-04-15',
            ],
            [
                'title' => '2025 Research & Commercialization Grants Open for Faculty Teams',
                'slug' => '2025-research-commercialization-grants-open',
                'category' => 'CALL FOR PROPOSALS',
                'badge_label' => 'GRANT CALL',
                'summary' => 'Faculty and research associates may submit disclosure abstracts for competitive seed validation funding up to ₱500,000 per project under the Innovation Acceleration Program.',
                'content' => '<p>Faculty and research associates may submit disclosure abstracts for competitive seed validation funding up to ₱500,000 per project under the Innovation Acceleration Program.</p><p>Selected proposals will undergo preliminary patentability assessment and commercial readiness validation.</p>',
                'image_url' => 'assets/resources.jpg',
                'author' => 'TTBDO Grants Secretariat',
                'meeting_focus' => null,
                'startups_supported' => null,
                'coverage_area' => null,
                'is_featured' => false,
                'is_published' => true,
                'published_date' => '2025-04-08',
            ],
            [
                'title' => 'Five UP Cebu Inventions Secure IPOPHL Certificates of Registration',
                'slug' => 'five-up-cebu-inventions-secure-ipophl-certificates',
                'category' => 'PATENT & IP',
                'badge_label' => 'MILESTONE',
                'summary' => 'The Intellectual Property Office of the Philippines has officially awarded utility model and industrial design protection to research outputs from the Department of Computer Science and School of Management.',
                'content' => '<p>The Intellectual Property Office of the Philippines has officially awarded utility model and industrial design protection to research outputs from the Department of Computer Science and School of Management.</p><p>These registered protections validate years of rigorous development and open pathways for commercial licensing.</p>',
                'image_url' => 'assets/resources.jpg',
                'author' => 'TTBDO IP Protection Unit',
                'meeting_focus' => null,
                'startups_supported' => null,
                'coverage_area' => null,
                'is_featured' => false,
                'is_published' => true,
                'published_date' => '2025-03-24',
            ],
            [
                'title' => 'TTBDO Conducts Prior-Art Patent Search Workshop for STEM Educators',
                'slug' => 'ttbdo-conducts-prior-art-patent-search-workshop',
                'category' => 'CAPACITY BUILDING',
                'badge_label' => 'TRAINING',
                'summary' => 'Over 45 regional educators and lab coordinators completed the intensive two-day patent landscape workshop hosted at the UP Cebu Performing Arts Hall.',
                'content' => '<p>Over 45 regional educators and lab coordinators completed the intensive two-day patent landscape workshop hosted at the UP Cebu Performing Arts Hall.</p><p>Participants navigated global patent databases, learned claim crafting principles, and evaluated novelty requirements.</p>',
                'image_url' => 'assets/resources.jpg',
                'author' => 'TTBDO Training Committee',
                'meeting_focus' => null,
                'startups_supported' => null,
                'coverage_area' => null,
                'is_featured' => false,
                'is_published' => true,
                'published_date' => '2025-03-12',
            ],
        ];

        foreach ($newsItems as $item) {
            News::updateOrCreate(['slug' => $item['slug']], $item);
        }

        // 3. Seed Events
        $eventItems = [
            [
                'title' => 'Central Visayas Innovation Summit & Startup Demo Day 2025',
                'slug' => 'central-visayas-innovation-summit-2025',
                'category' => 'ANNUAL SUMMIT',
                'badge_label' => 'FLAGSHIP EVENT',
                'event_date' => '2025-04-15',
                'start_time' => '08:30 AM',
                'end_time' => '05:00 PM',
                'venue' => 'UP Cebu SRP Campus & Live Stream',
                'venue_type' => 'hybrid',
                'summary' => 'Annual gathering connecting student innovators, faculty researchers, angel investors, and regional venture partners for live pitch presentations and tech demonstrations.',
                'description' => '<p>Annual gathering connecting student innovators, faculty researchers, angel investors, and regional venture partners for live pitch presentations and tech demonstrations.</p>',
                'registration_url' => '#contact',
                'is_featured' => true,
                'status' => 'upcoming',
            ],
            [
                'title' => 'Legal Clinic: Navigating Generative AI and Copyright Law in the Philippines',
                'slug' => 'legal-clinic-generative-ai-copyright-law',
                'category' => 'IP LEGAL CLINIC',
                'badge_label' => 'FREE WEBINAR',
                'event_date' => '2025-03-28',
                'start_time' => '02:00 PM',
                'end_time' => '04:30 PM',
                'venue' => 'Online via Zoom & UP Cebu AVR',
                'venue_type' => 'hybrid',
                'summary' => 'Dedicated legal consultation on intellectual property, algorithmic training datasets, and copyright protections for digital creators and software developers.',
                'description' => '<p>Dedicated legal consultation on intellectual property, algorithmic training datasets, and copyright protections for digital creators and software developers.</p>',
                'registration_url' => '#contact',
                'is_featured' => false,
                'status' => 'upcoming',
            ],
            [
                'title' => 'Pitch Perfect: SIMP Cohort 4 Mentorship & Investor Readiness Lab',
                'slug' => 'pitch-perfect-simp-cohort-4-mentorship',
                'category' => 'STUDENT MENTORSHIP',
                'badge_label' => 'WORKSHOP',
                'event_date' => '2025-04-03',
                'start_time' => '09:00 AM',
                'end_time' => '12:00 PM',
                'venue' => 'TIC Innovation Lab, 3rd Floor',
                'venue_type' => 'in-person',
                'summary' => 'Intensive deck refinement and financial modeling clinic for undergraduate capstone teams qualifying for DOST regional prototype validation grants.',
                'description' => '<p>Intensive deck refinement and financial modeling clinic for undergraduate capstone teams qualifying for DOST regional prototype validation grants.</p>',
                'registration_url' => '#contact',
                'is_featured' => false,
                'status' => 'upcoming',
            ],
        ];

        foreach ($eventItems as $item) {
            Event::updateOrCreate(['slug' => $item['slug']], $item);
        }

        // 4. Seed Inquiries
        $inquiries = [
            [
                'full_name' => 'Maria Santos',
                'email' => 'maria.santos@up.edu.ph',
                'contact_number' => '09171234567',
                'affiliation' => 'faculty',
                'inquiry_type' => 'ip_protection',
                'subject' => 'Invention Disclosure for AI-assisted AgriTech Sensor',
                'message' => 'Good day TTBDO team. Our research group has finished testing our IoT humidity and soil acidity sensor. We would like to initiate prior art search and utility model filing.',
                'status' => 'in_review',
                'admin_notes' => 'Coordinated initial meeting with CS department lead.',
            ],
            [
                'full_name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@gmail.com',
                'contact_number' => '09189876543',
                'affiliation' => 'msme',
                'inquiry_type' => 'msme_support',
                'subject' => 'DOST SETUP and Product Packaging Consultation',
                'message' => 'Hello. We are a food processing enterprise based in Mandaue City and interested in adopting UP Cebu packaging technology and technical advisory.',
                'status' => 'pending',
                'admin_notes' => null,
            ],
        ];

        foreach ($inquiries as $inq) {
            Inquiry::firstOrCreate(['email' => $inq['email'], 'subject' => $inq['subject']], $inq);
        }

        // 5. Seed Site Settings
        $settings = [
            ['setting_key' => 'office_title', 'setting_value' => 'Technology Transfer and Business Development Office', 'setting_group' => 'general', 'description' => 'Official office name'],
            ['setting_key' => 'university_name', 'setting_value' => 'University of the Philippines Cebu', 'setting_group' => 'general', 'description' => 'Parent institution name'],
            ['setting_key' => 'office_location', 'setting_value' => '3rd Floor, Technology Innovation Center, UP Cebu, Gorordo Ave., Lahug, Cebu City', 'setting_group' => 'contact', 'description' => 'Physical office address'],
            ['setting_key' => 'contact_email', 'setting_value' => 'ttbdo@upcebu.edu.ph', 'setting_group' => 'contact', 'description' => 'Primary contact email'],
            ['setting_key' => 'contact_phone', 'setting_value' => '(032) 232-6001 loc. 301', 'setting_group' => 'contact', 'description' => 'Official trunkline / local'],
            ['setting_key' => 'facebook_url', 'setting_value' => 'https://www.facebook.com/upcebuttbdo', 'setting_group' => 'social', 'description' => 'Official Facebook page'],
            ['setting_key' => 'office_hours', 'setting_value' => 'Monday - Friday: 8:00 AM - 5:00 PM', 'setting_group' => 'general', 'description' => 'Official office operating hours'],
            ['setting_key' => 'motto', 'setting_value' => 'Nurtured to Create • Inspired to Innovate • Destined to Serve', 'setting_group' => 'general', 'description' => 'UP Cebu Official Motto'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['setting_key' => $setting['setting_key']], $setting);
        }
    }
}
