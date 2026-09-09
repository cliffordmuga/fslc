<?php
namespace Database\Seeders;

use App\Models\Content;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->error('No admin user found. Run AdminUserSeeder first.');
            return;
        }

        // Create intro content
        Content::firstOrCreate(
            ['type' => 'intro', 'slug' => 'homepage-intro'],
            [
                'title' => 'Welcome to Our Portfolio',
                'content' => 'We create exceptional digital experiences that drive results. From concept to completion, we deliver professional solutions tailored to your unique needs.',
                'excerpt' => 'Professional digital solutions that drive results',
                'status' => 'published',
                'published_at' => now(),
                'created_by' => $admin->id,
            ]
        );

        // Create about content
        Content::firstOrCreate(
            ['type' => 'about', 'slug' => 'about-us'],
            [
                'title' => 'About Our Company',
                'content' => 'With over a decade of experience in digital innovation, we have helped hundreds of businesses transform their online presence and achieve their goals.',
                'excerpt' => 'Experienced digital innovation partners',
                'status' => 'published',
                'published_at' => now(),
                'created_by' => $admin->id,
            ]
        );

        // Create sample portfolio items
        $portfolioItems = [
            [
                'title' => 'E-commerce Platform Redesign',
                'slug' => 'ecommerce-platform-redesign',
                'excerpt' => 'Complete redesign of a major e-commerce platform resulting in 40% increase in conversions',
                'content' => 'This comprehensive project involved redesigning the entire user experience of a major e-commerce platform. We conducted extensive user research, created new wireframes, and implemented a modern design system that resulted in significant improvements in user engagement and conversion rates.',
            ],
            [
                'title' => 'Mobile Banking App',
                'slug' => 'mobile-banking-app',
                'excerpt' => 'Secure and user-friendly mobile banking application with biometric authentication',
                'content' => 'Developed a cutting-edge mobile banking application with advanced security features including biometric authentication, real-time transaction monitoring, and intuitive user interface design.',
            ],
            [
                'title' => 'Corporate Website Development',
                'slug' => 'corporate-website-development',
                'excerpt' => 'Modern corporate website with CMS and lead generation capabilities',
                'content' => 'Created a professional corporate website with custom CMS, lead generation forms, and analytics integration to help track and convert website visitors into qualified leads.',
            ]
        ];

        foreach ($portfolioItems as $item) {
            Content::firstOrCreate(
                ['type' => 'portfolio', 'slug' => $item['slug']],
                array_merge($item, [
                    'type' => 'portfolio',
                    'status' => 'published',
                    'published_at' => now(),
                    'created_by' => $admin->id,
                ])
            );
        }

        // Create sample services
        $services = [
            [
                'title' => 'Web Development',
                'slug' => 'web-development',
                'excerpt' => 'Custom web applications built with modern technologies',
                'content' => 'We create custom web applications using the latest technologies and best practices to ensure your digital presence is fast, secure, and scalable.',
            ],
            [
                'title' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'excerpt' => 'User-centered design that converts visitors into customers',
                'content' => 'Our design process focuses on understanding your users and creating experiences that not only look great but also drive conversions and business growth.',
            ],
            [
                'title' => 'Digital Consulting',
                'slug' => 'digital-consulting',
                'excerpt' => 'Strategic guidance for your digital transformation',
                'content' => 'Get expert advice on digital strategy, technology selection, and implementation planning to ensure your projects succeed from conception to launch.',
            ]
        ];

        foreach ($services as $service) {
            Content::firstOrCreate(
                ['type' => 'service', 'slug' => $service['slug']],
                array_merge($service, [
                    'type' => 'service',
                    'status' => 'published',
                    'published_at' => now(),
                    'created_by' => $admin->id,
                ])
            );
        }

        // Create sample testimonials
        $testimonials = [
            [
                'client_name' => 'Sarah Johnson',
                'testimonial' => 'Working with this team was incredible. They delivered exactly what we needed and exceeded our expectations.',
                'rating' => 5,
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'client_name' => 'Michael Chen',
                'testimonial' => 'Professional, responsive, and delivered on time. Would definitely work with them again.',
                'rating' => 5,
                'is_featured' => true,
                'status' => 'approved',
            ],
            [
                'client_name' => 'Emma Davis',
                'testimonial' => 'The results speak for themselves. Our conversion rate increased by 40% after the redesign.',
                'rating' => 5,
                'is_featured' => false,
                'status' => 'approved',
            ]
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(
                ['client_name' => $testimonial['client_name']],
                $testimonial
            );
        }
    }
}