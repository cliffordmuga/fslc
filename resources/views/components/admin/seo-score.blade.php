{{-- SEO Score: server-rendered initial + Alpine for live updates --}}
@props([])

<div x-data="seoScore({{ Js::from($initialScore) }}, {{ Js::from($rules) }})"
     x-init="init()"
     class="rounded-lg border border-neutral-200 bg-white p-4"
     {{ $attributes }}>
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="flex h-14 w-14 items-center justify-center rounded-full text-xl font-bold transition-colors"
                 :class="{
                     'bg-emerald-100 text-emerald-700': score >= 80,
                     'bg-amber-100 text-amber-700': score >= 60 && score < 80,
                     'bg-orange-100 text-orange-700': score >= 40 && score < 60,
                     'bg-red-100 text-red-700': score < 40
                 }">
                <span x-text="score"></span>
            </div>
            <div>
                <p class="font-medium text-neutral-900">SEO Score</p>
                <p class="text-sm text-neutral-500">Grade: <span x-text="grade" class="font-semibold"></span></p>
            </div>
        </div>
        <button type="button"
                @click="expanded = !expanded"
                class="text-sm font-medium text-primary-600 hover:text-primary-700">
            <span x-text="expanded ? 'Hide details' : 'View details'"></span>
        </button>
    </div>

    <div x-show="expanded"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="mt-4 space-y-2 border-t border-neutral-100 pt-4">
        <template x-for="(check, i) in checks" :key="i">
            <div class="flex items-start gap-2 text-sm">
                <span x-show="check.pass" class="text-emerald-500 mt-0.5">✓</span>
                <span x-show="!check.pass" class="text-amber-500 mt-0.5">!</span>
                <span :class="check.pass ? 'text-neutral-600' : 'text-amber-700'" x-text="check.msg"></span>
            </div>
        </template>
        <p x-show="suggestions.length > 0 && expanded" class="mt-2 text-xs text-neutral-500">
            Focus on the items marked with ! to improve your score.
        </p>
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('seoScore', (initial, rules) => ({
        score: initial.score,
        grade: initial.grade,
        checks: initial.checks,
        suggestions: initial.suggestions || [],
        expanded: false,
        rules: rules,

        init() {
            this.watchForm();
        },

        watchForm() {
            const form = this.$el.closest('form');
            if (!form) return;
            const fields = ['title', 'slug', 'excerpt', 'content', 'meta_title', 'meta_description'];
            const inputs = fields.map(f => form.querySelector(`[name="${f}"]`)).filter(Boolean);

            const update = () => {
                const data = {};
                inputs.forEach(inp => {
                    if (inp.name === 'content') {
                        try {
                            const ed = typeof tinymce !== 'undefined' && tinymce.get(inp.id);
                            data.content = ed ? (ed.getContent() || '') : (inp.value || '');
                        } catch (_) { data.content = inp.value || ''; }
                    } else {
                        data[inp.name] = inp.value || '';
                    }
                });
                data.meta_title = data.meta_title || data.title;
                data.meta_description = data.meta_description || data.excerpt;
                const alts = form.querySelectorAll('[name^="image_alt["]');
                data.image_alts = Array.from(alts).map(a => a.value);
                const result = this.compute(data);
                this.score = result.score;
                this.grade = result.grade;
                this.checks = result.checks;
                this.suggestions = result.suggestions || [];
            };

            inputs.forEach(inp => inp.addEventListener('input', update));
            inputs.forEach(inp => inp.addEventListener('change', update));
            // TinyMCE content updates
            if (typeof tinymce !== 'undefined') {
                tinymce.on('AddEditor', e => e.editor.on('keyup change', update));
                tinymce.editors?.forEach(ed => ed.on('keyup change', update));
            }
        },

        compute(data) {
            const r = this.rules;
            const metaTitle = (data.meta_title || data.title || '').trim();
            const metaDesc = (data.meta_description || data.excerpt || '').trim();
            const excerpt = (data.excerpt || '').trim();
            const content = (data.content || '').replace(/<[^>]+>/g, ' ');
            const wordCount = content.trim() ? content.trim().split(/\s+/).length : 0;
            const slug = (data.slug || '').trim();
            const alts = (data.image_alts || []).map(a => String(a || '').trim());
            const hasEmptyAlt = alts.length > 0 && alts.some(a => !a);

            let earned = 0;
            const checks = [];

            const mtLen = metaTitle.length;
            const mtPts = (mtLen >= 50 && mtLen <= 60) ? 25 : ((mtLen >= 30 && mtLen <= 70) ? 17 : 0);
            earned += mtPts;
            checks.push({ pass: mtLen >= 30, msg: `Meta title: ${mtLen}/60 chars`, pts: mtPts });

            const mdLen = metaDesc.length;
            const mdPts = (mdLen >= 150 && mdLen <= 160) ? 25 : ((mdLen >= 120 && mdLen <= 165) ? 20 : 0);
            earned += mdPts;
            checks.push({ pass: mdLen >= 120, msg: `Meta description: ${mdLen}/160 chars`, pts: mdPts });

            const exLen = excerpt.length;
            const exPts = exLen >= 120 ? 15 : (exLen > 0 ? 9 : 0);
            earned += exPts;
            checks.push({ pass: exLen > 0, msg: `Excerpt: ${exLen} chars`, pts: exPts });

            const wordPts = wordCount >= 300 ? 15 : (wordCount >= 150 ? 7 : 0);
            earned += wordPts;
            checks.push({ pass: wordCount >= 150, msg: `Content: ${wordCount} words`, pts: wordPts });

            const slugOk = /^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(slug) && slug.length >= 3;
            const slugPts = slugOk ? 10 : 0;
            earned += slugPts;
            checks.push({ pass: slugOk, msg: 'Slug is URL-friendly', pts: slugPts });

            const altPts = hasEmptyAlt ? 5 : 10;
            earned += altPts;
            checks.push({ pass: !hasEmptyAlt || alts.length === 0, msg: 'Images have alt text', pts: altPts });

            const score = Math.min(100, Math.round(earned));
            const grade = score >= 90 ? 'A' : score >= 80 ? 'B' : score >= 70 ? 'C' : score >= 60 ? 'D' : 'F';

            return {
                score,
                grade,
                checks,
                suggestions: checks.filter(c => !c.pass)
            };
        }
    }));
});
</script>
@endpush
@endonce
