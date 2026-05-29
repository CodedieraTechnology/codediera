<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Project;
use App\Models\DigitalSkillsItem;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodedieraDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Truncate existing tables to avoid duplicate entries and clean up test data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Service::truncate();
        Project::truncate();
        TeamMember::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Services
        $services = [
            [
                'title' => 'Website Design & Development',
                'service_type' => 'other',
                'description' => 'Professional website design and development tailored to your business needs. Clean, responsive, and SEO-friendly.',
                'icon' => '💻',
                'is_free' => false,
                'price' => 150000.00,
                'payment_type' => 'one_time',
                'delivery_duration_value' => 14,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'title' => 'Software & Web Application Development',
                'service_type' => 'other',
                'description' => 'Scalable custom web applications, SaaS products, admin portals, and business automation platforms built with Laravel and modern JS.',
                'icon' => '⚙️',
                'is_free' => false,
                'price' => 350000.00,
                'payment_type' => 'one_time',
                'delivery_duration_value' => 30,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 20,
                'is_active' => true,
            ],
            [
                'title' => 'Mobile App Development',
                'service_type' => 'other',
                'description' => 'Native and cross-platform mobile application development for Android and iOS devices.',
                'icon' => '📱',
                'is_free' => false,
                'price' => 500000.00,
                'payment_type' => 'one_time',
                'delivery_duration_value' => 45,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 30,
                'is_active' => true,
            ],
            [
                'title' => 'UI/UX Design',
                'service_type' => 'other',
                'description' => 'User interface and user experience design. Wireframes, high-fidelity mockups, and interactive prototypes using Figma.',
                'icon' => '🎨',
                'is_free' => false,
                'price' => 80000.00,
                'payment_type' => 'one_time',
                'delivery_duration_value' => 10,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 40,
                'is_active' => true,
            ],
            [
                'title' => 'Digital Skills Training',
                'service_type' => 'other',
                'description' => 'Practical, industry-focused tech training courses covering frontend development, backend development, UI/UX, and more.',
                'icon' => '🎓',
                'is_free' => false,
                'price' => 50000.00,
                'payment_type' => 'one_time',
                'delivery_duration_value' => 90,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 50,
                'is_active' => true,
            ],
            [
                'title' => 'Tech Mentorship & Tutorials',
                'service_type' => 'other',
                'description' => 'Personalized one-on-one mentorship, career guidance, and learning roadmaps for aspiring and junior software developers.',
                'icon' => '🤝',
                'is_free' => false,
                'price' => 15000.00,
                'payment_type' => 'monthly',
                'delivery_duration_value' => 30,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'message'],
                'sort_order' => 60,
                'is_active' => true,
            ],
            [
                'title' => 'Database Management Systems',
                'service_type' => 'other',
                'description' => 'Database design, optimization, migration, and management using MySQL, PostgreSQL, and other relational databases.',
                'icon' => '💾',
                'is_free' => false,
                'price' => 120000.00,
                'payment_type' => 'one_time',
                'delivery_duration_value' => 14,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 70,
                'is_active' => true,
            ],
            [
                'title' => 'IT Consultancy & Support',
                'service_type' => 'other',
                'description' => 'Professional IT consultancy, system auditing, technical support, and infrastructure management for organizations.',
                'icon' => '🛠️',
                'is_free' => false,
                'price' => 30000.00,
                'payment_type' => 'monthly',
                'delivery_duration_value' => 30,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 80,
                'is_active' => true,
            ],
            [
                'title' => 'Hosting & Deployment Solutions',
                'service_type' => 'other',
                'description' => 'Website and web application deployment, cloud server configuration (AWS, DigitalOcean), SSL installation, and domain management.',
                'icon' => '🌐',
                'is_free' => false,
                'price' => 25000.00,
                'payment_type' => 'yearly',
                'delivery_duration_value' => 365,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => false,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 90,
                'is_active' => true,
            ],
            [
                'title' => 'Social Media & Digital Solutions',
                'service_type' => 'social_media_management',
                'description' => 'Complete social media management, brand strategy, content creation, and digital marketing services to boost your online presence.',
                'icon' => '📣',
                'is_free' => false,
                'price' => 45000.00,
                'payment_type' => 'monthly',
                'delivery_duration_value' => 30,
                'delivery_duration_unit' => 'days',
                'grace_trial_enabled' => true,
                'inquiry_fields' => ['phone', 'company', 'message'],
                'sort_order' => 100,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // 3. Seed Projects
        $projects = [
            [
                'title' => 'Codediera LMS (Learning Management System)',
                'description' => 'A modern online learning platform with video lessons, quiz assessments, course ratings, and progress tracking built for Codediera Academy.',
                'url' => 'https://codediera.com/skills',
                'cost' => 450000.00,
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'title' => 'E-Commerce Enterprise Suite',
                'description' => 'A robust online shopping cart system with real-time inventory management, Paystack integration, order tracking, and sales analytics.',
                'url' => 'https://shop.codediera.com',
                'cost' => 600000.00,
                'sort_order' => 20,
                'is_active' => true,
            ],
            [
                'title' => 'School Management Portal',
                'description' => 'A comprehensive school management system featuring student registration, grade reports, fee payment, and parent-teacher communication portals.',
                'url' => 'https://portal.codediera.com',
                'cost' => 850000.00,
                'sort_order' => 30,
                'is_active' => true,
            ],
            [
                'title' => 'Hospitality Booking System',
                'description' => 'An online hotel reservation and room booking software featuring real-time room availability calendar, payment gateways, and staff check-in dashboard.',
                'url' => 'https://book.codediera.com',
                'cost' => 350000.00,
                'sort_order' => 40,
                'is_active' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        // 4. Seed new Digital Skills
        $newSkills = [
            [
                'title' => 'Frontend Web Development (HTML, CSS, JS)',
                'description' => 'Master the core building blocks of the web. Learn HTML5, CSS3 layout techniques (Flexbox, Grid), responsive design, and JavaScript fundamentals.',
                'instructor_user_id' => 2, // Codediera Admin
                'total_hours' => 40.0,
                'is_free' => true,
                'price' => 0.00,
                'currency' => 'NGN',
                'sort_order' => 40,
                'is_active' => true,
            ],
            [
                'title' => 'Backend Development with PHP & Laravel',
                'description' => 'Learn how to build secure, scalable web applications from scratch using PHP and the Laravel framework. Includes MVC architecture, databases, APIs, and authentication.',
                'instructor_user_id' => 2,
                'total_hours' => 60.0,
                'is_free' => false,
                'price' => 35000.00,
                'currency' => 'NGN',
                'sort_order' => 50,
                'is_active' => true,
            ],
            [
                'title' => 'Introduction to UI/UX Design with Figma',
                'description' => 'Gain hands-on experience in user research, wireframing, prototyping, and high-fidelity UI design using Figma.',
                'instructor_user_id' => 2,
                'total_hours' => 30.0,
                'is_free' => false,
                'price' => 25000.00,
                'currency' => 'NGN',
                'sort_order' => 60,
                'is_active' => true,
            ],
            [
                'title' => 'Python Programming for Beginners',
                'description' => 'Discover the fundamentals of Python, from syntax and data structures to object-oriented programming, data analysis, and writing automations.',
                'instructor_user_id' => 2,
                'total_hours' => 35.0,
                'is_free' => true,
                'price' => 0.00,
                'currency' => 'NGN',
                'sort_order' => 70,
                'is_active' => true,
            ],
        ];

        foreach ($newSkills as $skill) {
            DigitalSkillsItem::firstOrCreate(
                ['title' => $skill['title']],
                $skill
            );
        }

        // 5. Seed Team Members
        $team = [
            [
                'name' => 'Vitalis Njoku',
                'role' => 'Developer',
                'bio' => 'Experienced Full-Stack Developer specializing in web application development, custom software engineering, and API integrations.',
                'photo_path' => 'team/vitalis.png',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Williams Emmanuel',
                'role' => 'Developer',
                'bio' => 'Frontend Developer passionate about building beautiful, responsive user interfaces and modern, user-friendly digital products.',
                'photo_path' => 'team/williams.png',
                'is_active' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'Tony A.',
                'role' => 'Database Developer',
                'bio' => 'Database Architect and Administrator specializing in database schema design, query optimization, security, and migration systems.',
                'photo_path' => 'team/tony.png',
                'is_active' => true,
                'sort_order' => 30,
            ],
        ];

        foreach ($team as $member) {
            TeamMember::create($member);
        }
    }
}
