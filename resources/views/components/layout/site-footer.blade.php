{{-- Public site footer — newsletter, columns, legal bar --}}
<footer class="bg-neutral-900 text-neutral-400 border-t border-neutral-800" aria-label="Footer" role="contentinfo">
    <div class="border-b border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-white">HMIS procurement &amp; digital health</p>
                    <p class="text-xs text-neutral-500 mt-0.5">Checklists and guides for Kenyan hospitals — no spam.</p>
                </div>
                <form action="{{ route('newsletter.subscribe') }}" method="POST"
                      class="flex gap-2 w-full lg:w-auto lg:max-w-sm"
                      x-data="{ loading: false }"
                      @submit.prevent="loading = true; $el.submit(); setTimeout(() => loading = false, 2000)">
                    @csrf
                    <input type="hidden" name="inquiry_type" value="newsletter">
                    <input type="hidden" name="utm_source" value="{{ request()->query('utm_source', 'newsletter') }}">
                    <input type="hidden" name="utm_medium" value="footer">
                    <input type="hidden" name="utm_campaign" value="lead_gen_{{ date('Y-m-d') }}">
                    <input type="text" name="website_url" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                    <input type="url" name="company_website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                    <input type="hidden" name="_form_rendered_at" value="{{ time() }}">
                    <input type="email" name="email" placeholder="Your email" required
                           class="flex-1 px-4 py-2.5 bg-neutral-800 border border-neutral-700 text-white text-sm placeholder-neutral-500 focus:border-primary-500 focus:outline-none min-h-[40px]">
                    <button type="submit" :disabled="loading"
                            class="px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors min-h-[40px] disabled:opacity-50">
                        <span x-show="!loading">Subscribe</span>
                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
            <div class="col-span-2 lg:col-span-1">
                <div class="flex items-center gap-2.5 mb-4">
                    <x-cms.brand-mark size="md" :show-name="true" name-class="text-base font-bold text-white tracking-tight" />
                </div>
                <p class="text-xs text-neutral-500 leading-relaxed max-w-xs">
                    {{ setting('company_tagline', 'High-performance digital solutions for ambitious brands in Kenya and beyond.') }}
                </p>
                <div class="flex gap-2 mt-5">
                    @foreach (config('social.footer_links', []) as $key => $social)
                        @if (setting($key))
                            <a href="{{ setting($key) }}" target="_blank" rel="noopener noreferrer"
                               class="w-11 h-11 min-w-[44px] min-h-[44px] flex items-center justify-center text-neutral-600 hover:text-white hover:bg-neutral-700 transition-colors"
                               aria-label="{{ $social['label'] }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="{{ $social['path'] }}"/>
                                </svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-xs font-semibold text-neutral-300 uppercase tracking-widest mb-4">Pages</h3>
                <ul class="space-y-2">
                    @foreach (['home' => 'Home', 'about' => 'About', 'portfolio.index' => 'Portfolio', 'services.index' => 'Services', 'insights.index' => 'Insights', 'contact' => 'Contact'] as $r => $label)
                        <li>
                            <a href="{{ route($r) }}" class="text-xs text-neutral-500 hover:text-white transition-colors">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-semibold text-neutral-300 uppercase tracking-widest mb-4">Services</h3>
                <ul class="space-y-2">
                    @foreach (config('forefront.footer_service_links', []) as $slug => $label)
                        <li>
                            <a href="{{ route('services.show', $slug) }}" class="text-xs text-neutral-500 hover:text-white transition-colors">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-semibold text-neutral-300 uppercase tracking-widest mb-4">Get In Touch</h3>
                <div class="space-y-3">
                    <x-cms.contact-link type="email" :value="setting('email')"
                        class="flex items-center gap-2 text-xs text-neutral-500 hover:text-white transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email Us
                    </x-cms.contact-link>
                    <x-cms.contact-link type="tel" :value="setting('phone')"
                        class="flex items-center gap-2 text-xs text-neutral-500 hover:text-white transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Call Us
                    </x-cms.contact-link>
                    <a href="{{ route('contact', ['inquiry_type' => 'hmis-demo']) }}#contact-form"
                       class="flex items-center gap-2 text-xs text-neutral-500 hover:text-white transition-colors">
                        Request HMIS Demo
                    </a>
                    <a href="{{ route('contact', ['inquiry_type' => 'hmis-checklist']) }}#contact-form"
                       class="flex items-center gap-2 text-xs text-neutral-500 hover:text-white transition-colors">
                        Get HMIS Checklist
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-neutral-800 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-neutral-600">
            <p>© {{ date('Y') }} {{ config('app.name', env('APP_NAME', 'App')) }}. All rights reserved.</p>
            <div class="flex gap-5">
                <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy</a>
                <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms</a>
                <a href="{{ route('sitemap') }}" class="hover:text-white transition-colors">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
