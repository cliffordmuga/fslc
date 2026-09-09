<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Tag;
use Illuminate\Support\Str;

/**
 * Page-level SEO copy, canonical URLs, and JSON-LD schema builders.
 * Extracted from ContentService so content fetching and SEO concerns stay separate.
 */
class PageSeoService
{
    public function getSeoData(string $page, ?Content $content = null): array
    {
        if ($content) {
            return [
                'title' => $content->getSeoTitle(),
                'description' => $content->getSeoDescription(),
                'keywords' => $content->seoMetadata?->meta_keywords ?? 'digital agency Kenya, web development, Laravel, East Africa',
                'og_title' => $content->seoMetadata?->og_title ?? $content->getSeoTitle(),
                'og_description' => $content->seoMetadata?->og_description ?? $content->getSeoDescription(),
                'og_image' => $content->getOgImage(),
                'canonical_url' => $content->getCanonicalUrl(),
                'noindex' => (bool) ($content->seoMetadata?->noindex ?? false),
                'nofollow' => (bool) ($content->seoMetadata?->nofollow ?? false),
                'structured_data' => $content->getStructuredData(),
                'preload_image' => $content->getOgImage(),
            ];
        }

        $page = trim($page);

        $defaultTitle = $this->getPageSeoTitle($page);
        $defaultDescription = $this->getPageSeoDescription($page);

        $keywordMap = [
            'home' => 'HMIS Kenya, hospital management system Kenya, web development company Kenya, election digital strategy Kenya',
            'about' => 'HMIS vendor Kenya, digital transformation Nairobi, Forefront Solutions, healthcare technology Kenya',
            'portfolio' => 'HMIS case studies Kenya, hospital EMR projects, campaign websites Kenya, software development Kenya',
            'services' => 'HMIS Kenya, EMR Kenya, Laravel developers Kenya, branding agency Kenya, political campaign website Kenya',
            'blog' => 'HMIS Kenya, SHA integration, election digital strategy, M-Pesa integration, SEO Kenya',
            'contact' => 'HMIS demo Kenya, software consultation Nairobi, campaign strategy Kenya, Forefront Solutions contact',
            'privacy' => 'privacy policy Kenya, data protection healthcare IT',
            'terms' => 'terms of service, IT consultancy Kenya',
        ];
        $keywords = $keywordMap[$page] ?? 'portfolio, services, digital agency, Kenya';

        return [
            'title' => $defaultTitle,
            'description' => $defaultDescription,
            'keywords' => $keywords,
            'og_title' => $defaultTitle ? $defaultTitle : config('app.name'),
            'og_description' => $defaultDescription,
            'og_image' => asset('images/default-og-image.png'),
            'canonical_url' => $this->pageCanonicalUrl($page),
            'noindex' => false,
            'structured_data' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $defaultTitle ? ($defaultTitle.' | '.config('app.name')) : config('app.name'),
                'url' => $this->pageCanonicalUrl($page),
            ],
        ];
    }

    protected function pageCanonicalUrl(string $page): string
    {
        $routeMap = [
            'home' => 'home',
            'about' => 'about',
            'portfolio' => 'portfolio.index',
            'services' => 'services.index',
            'blog' => 'insights.index',
            'contact' => 'contact',
        ];

        if (isset($routeMap[$page])) {
            return route($routeMap[$page]);
        }

        return canonical_url();
    }

    /**
     * @param  array<string, mixed>  $seoData
     * @return array<string, mixed>
     */
    public function applyPaginatedHubSeo(array $seoData, string $routeName, int $page): array
    {
        $seoData['canonical_url'] = $page > 1
            ? route($routeName, ['page' => $page])
            : route($routeName);

        return $seoData;
    }

    /**
     * @param  array<string, mixed>  $seoData
     * @return array<string, mixed>
     */
    public function applyFilteredHubSeo(array $seoData, string $routeName): array
    {
        $seoData['canonical_url'] = route($routeName);
        $seoData['noindex'] = true;

        return $seoData;
    }

    public function getTagSeoData(Tag $tag): array
    {
        $company = setting('company_name', config('app.name'));
        $title = "{$tag->name} — Content Hub";
        $description = $tag->meta_description
            ?: ($tag->hub_intro ?: (config("forefront.tag_hub_intros.{$tag->slug}") ?? "Browse {$tag->name} insights, services, and case studies from {$company}."));
        $description = meta_description($description);

        $page = max(1, (int) request()->query('page', 1));
        $typeFilter = (string) request()->query('type', 'all');
        $hasTypeFilter = $typeFilter !== '' && $typeFilter !== 'all';

        $canonical = $hasTypeFilter
            ? route('tags.show', $tag->slug)
            : ($page > 1
                ? route('tags.show', ['slug' => $tag->slug, 'page' => $page])
                : route('tags.show', $tag->slug));

        return [
            'title' => $title,
            'description' => $description,
            'og_title' => $title,
            'og_description' => $description,
            'og_image' => cdn_asset('images/default-og-image.png'),
            'canonical_url' => $canonical,
            'noindex' => $hasTypeFilter,
        ];
    }

    protected function getPageSeoTitle(string $page): ?string
    {
        return match ($page) {
            'home' => 'HMIS & Digital Health Kenya | Software, Branding & Public Engagement',
            'about' => 'About Forefront Solutions — Healthcare Technology Partner in Kenya',
            'portfolio' => 'Case Studies — HMIS, Software, Campaign & SACCO Projects in Kenya',
            'services' => 'Core Services — HMIS, Software, Branding & Political Engagement',
            'blog' => 'Insights — HMIS, Elections, Software & Digital Marketing in Kenya',
            'contact' => 'Contact Forefront Solutions | HMIS Demo, Software & Campaign Consultation',
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms & Conditions',
            'sitemap' => 'Sitemap',
            default => mb_strlen($page) ? ucfirst($page) : null,
        };
    }

    protected function getPageSeoDescription(string $page): string
    {
        $foundedYear = setting('founded_year', '2015');

        return match ($page) {
            'home' => 'Forefront Solutions is a Kenyan digital transformation company specializing in HMIS and healthcare technology, custom software development, digital communications, and public engagement platforms.',
            'about' => "Since {$foundedYear}, Forefront Solutions has focused on four pillars — HMIS & digital health, custom software, branding & marketing, and political & public engagement — for hospitals, government, NGOs, and campaigns in Kenya.",
            'portfolio' => 'Explore Forefront case studies: county HMIS deployments, NGO EMR programmes, government portals, M-Pesa e-commerce, campaign digital hubs, and SACCO member portals.',
            'services' => 'Four focused service pillars: HMIS & digital health, custom software & web development, digital strategy & branding, and political & public engagement — built for Kenyan institutions.',
            'blog' => 'HMIS procurement, SHA integration, election digital strategy, Laravel development, M-Pesa integration, and SEO guides for Kenyan healthcare, government, and campaign teams.',
            'contact' => 'Request an HMIS demo, software quote, or campaign strategy session with Forefront Solutions Kenya. We respond within 24 hours.',
            'privacy' => 'How Forefront Solutions (K) Ltd collects, uses, and protects personal information submitted through our website and client portals.',
            'terms' => 'Terms governing use of the Forefront Solutions website and professional technology, HMIS, and communications services in Kenya.',
            'sitemap' => 'Browse Forefront Solutions — HMIS, services, portfolio, insights, and contact pages.',
            default => setting('site_description', 'HMIS, digital health, web development, and strategic communications — Kenya.'),
        };
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<int, array<string, mixed>>
     */
    public function getPageSchemas(string $page, array $context = []): array
    {
        $schemas = [];

        switch ($page) {
            case 'home':
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => setting('company_name'),
                    'url' => url('/'),
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => ['@type' => 'EntryPoint', 'urlTemplate' => url('/search?q={search_term_string}')],
                        'query-input' => 'required name=search_term_string',
                    ],
                    'description' => setting('site_description', 'HMIS and digital transformation partner in Kenya.'),
                ];

                $portfolioItems = $context['portfolioItems'] ?? collect();
                if ($portfolioItems->isNotEmpty()) {
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'ItemList',
                        'name' => 'Featured Portfolio — '.setting('company_name'),
                        'itemListElement' => $portfolioItems->take(6)->values()->map(fn ($item, $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $item->title,
                            'url' => $item->url ?? url('/'),
                        ])->all(),
                    ];
                }

                $services = $context['services'] ?? collect();
                if ($services->isNotEmpty()) {
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'ItemList',
                        'name' => 'Core Services — '.setting('company_name'),
                        'itemListElement' => $services->take(4)->values()->map(fn ($item, $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $item->title,
                            'url' => $item->url ?? url('/'),
                        ])->all(),
                    ];
                }
                break;

            case 'about':
                $about = $context['about'] ?? null;
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => setting('company_name'),
                    'description' => ($about?->excerpt ?? setting('site_description', 'HMIS vendor and digital transformation partner in Kenya.')),
                    'url' => route('about'),
                    'foundingDate' => setting('founded_year', '2015'),
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => setting('address_street', '123 Business Ave, Suite 100'),
                        'addressLocality' => setting('address_city', 'Nairobi'),
                        'addressRegion' => setting('address_state', 'Nairobi'),
                        'postalCode' => setting('address_zip', '00100'),
                        'addressCountry' => 'KE',
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'contactType' => 'Customer Service',
                        'url' => route('contact').'#contact-form',
                        'areaServed' => 'KE',
                        'availableLanguage' => ['English', 'Swahili'],
                    ],
                ];
                $faqItems = $context['faqItems'] ?? [];
                if (! empty($faqItems)) {
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'FAQPage',
                        'mainEntity' => array_map(function ($faq) {
                            $answer = html_entity_decode(strip_tags((string) ($faq['answer'] ?? '')), ENT_QUOTES, 'UTF-8');
                            $answer = trim(preg_replace('/\s+/', ' ', $answer) ?? '');

                            return [
                                '@type' => 'Question',
                                'name' => strip_tags((string) ($faq['question'] ?? '')),
                                'acceptedAnswer' => [
                                    '@type' => 'Answer',
                                    'text' => Str::limit($answer, 5000, ''),
                                ],
                            ];
                        }, $faqItems),
                    ];
                }
                break;

            case 'services':
                $services = $context['services'] ?? collect();
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => setting('company_name').' — Core Services',
                    'serviceType' => 'Healthcare Technology and Digital Transformation',
                    'provider' => $this->organizationSchema(),
                    'areaServed' => ['KE', 'East Africa'],
                    'knowsAbout' => ['HMIS', 'Hospital Management System', 'Software Development', 'Digital Marketing', 'Election Digital Strategy'],
                    'offers' => $services->map(fn ($s) => [
                        '@type' => 'Offer',
                        'itemOffered' => ['@type' => 'Service', 'name' => $s->title, 'description' => generate_excerpt($s->excerpt)],
                        'price' => $s->price ? 'KES '.number_format($s->price) : 'Custom Quote',
                        'priceCurrency' => 'KES',
                    ])->values()->toArray(),
                ];
                break;

            case 'portfolio':
                $portfolioItems = $context['portfolioItems'] ?? collect();
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    'name' => 'Portfolio',
                    'itemListElement' => $portfolioItems->take(6)->map(function ($item, $index) {
                        $featuredUrl = $item->featured_image ?? $item->featured_image_url ?? asset('images/default-og-image.png');

                        return [
                            '@type' => 'CreativeWork',
                            'name' => $item->title,
                            'image' => $featuredUrl,
                            'url' => $item->url,
                            'description' => generate_excerpt($item->excerpt),
                            'position' => $index + 1,
                        ];
                    })->values()->toArray(),
                ];
                break;

            case 'blog':
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Blog',
                    'name' => 'Insights — '.setting('company_name'),
                    'description' => 'HMIS guides, election digital strategy, software development, and digital marketing insights for Kenyan institutions.',
                    'url' => route('insights.index'),
                ];
                break;

            case 'contact':
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'ContactPage',
                    'name' => 'Contact '.setting('company_name'),
                    'url' => route('contact'),
                    'description' => 'Request an HMIS demo, software quote, brand consultation, or campaign strategy session in Kenya.',
                    'mainEntity' => $this->organizationSchema(),
                ];
                break;

            case 'search':
                $query = $context['query'] ?? '';
                $results = $context['results'] ?? null;
                $searchSchema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'SearchResultsPage',
                    'name' => $query === '' ? 'Site Search' : "Search Results for '{$query}'",
                    'url' => request()->fullUrl(),
                    'searchTerms' => $query,
                    'about' => [
                        '@type' => 'Organization',
                        'name' => setting('company_name'),
                        'description' => 'HMIS vendor and digital transformation partner in Kenya.',
                    ],
                ];

                if ($results && method_exists($results, 'count') && $results->count() > 0) {
                    $items = method_exists($results, 'getCollection')
                        ? $results->getCollection()
                        : collect($results);

                    $searchSchema['mainEntity'] = [
                        '@type' => 'ItemList',
                        'numberOfItems' => method_exists($results, 'total') ? $results->total() : $results->count(),
                        'itemListElement' => $items->take(10)->values()->map(fn ($item, $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $item->title,
                            'url' => $item->url ?? url('/'),
                        ])->all(),
                    ];
                }

                $schemas[] = $searchSchema;
                break;

            case 'tag':
                $tag = $context['tag'] ?? null;
                if ($tag) {
                    $intro = $tag->hub_intro ?: (config("forefront.tag_hub_intros.{$tag->slug}") ?? null);
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'CollectionPage',
                        'name' => "{$tag->name} — Content Hub",
                        'url' => route('tags.show', $tag->slug),
                        'description' => $intro ?? "Browse content tagged with {$tag->name}.",
                    ];
                }
                break;

            case 'details':
                $content = $context['content'] ?? null;
                if (! $content) {
                    break;
                }
                $schemaType = match ($content->type) {
                    'portfolio' => 'CreativeWork',
                    'blog' => 'BlogPosting',
                    'services' => $content->slug === 'hmis-digital-health-solutions-kenya' ? 'MedicalBusiness' : 'Service',
                    default => 'Article',
                };
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => $schemaType,
                    'headline' => $content->title,
                    'name' => $content->title,
                    'description' => generate_excerpt($content->excerpt ?? $content->content),
                    'datePublished' => $content->published_at?->toIso8601String(),
                    'inLanguage' => 'en-KE',
                    'author' => [
                        '@type' => 'Organization',
                        'name' => setting('company_name'),
                    ],
                    'image' => $content->featured_image_url ?? asset('images/og/company.svg'),
                    'url' => $content->url ?? request()->url(),
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => setting('company_name'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => asset('images/og/company.svg'),
                        ],
                    ],
                    'areaServed' => ['KE', 'East Africa'],
                ];

                if ($content->type === 'services' && $content->slug === 'hmis-digital-health-solutions-kenya') {
                    $schema['medicalSpecialty'] = 'Hospital Information Systems';
                    $schema['category'] = 'Hospital Management System';
                }

                $schemas[] = $schema;
                break;
        }

        return $schemas;
    }

    private function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => setting('company_name'),
            'url' => url('/'),
            'foundingDate' => setting('founded_year', '2015'),
            'description' => setting('site_description'),
            'knowsAbout' => ['HMIS', 'Software Development', 'Digital Marketing', 'Election Digital Strategy'],
            'address' => $this->postalAddressSchema(),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'telephone' => setting('phone'),
                'email' => setting('email'),
                'url' => route('contact').'#contact-form',
                'areaServed' => 'KE',
                'availableLanguage' => ['English', 'Swahili'],
            ],
        ];
    }

    private function postalAddressSchema(): array
    {
        return [
            '@type' => 'PostalAddress',
            'streetAddress' => setting('address_street', 'The Place Plaza, 5th Floor, Off Church Road'),
            'addressLocality' => setting('address_city', 'Nairobi'),
            'addressRegion' => setting('address_state', 'Nairobi County'),
            'postalCode' => setting('address_zip', '00100'),
            'addressCountry' => 'KE',
        ];
    }
}
