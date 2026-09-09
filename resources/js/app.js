// resources/js/app.js
/* ============================================================================
   FILE: resources/js/app.js – Final Optimized Alpine.js Setup (2026)
   ============================================================================ */

import Alpine from "alpinejs";

// ============================================================================
// Alpine Directives & Magic Properties
// ============================================================================

/**
 * Debounce directive – delays execution (great for search inputs)
 * Usage: x-model.debounce.300ms="searchQuery"
 */
Alpine.directive(
    "debounce",
    (el, { modifiers, expression }, { evaluateLater, effect }) => {
        let timeout;
        const evaluate = evaluateLater(expression);

        effect(() => {
            evaluate((val) => {
                clearTimeout(timeout);
                timeout = setTimeout(
                    () => {
                        el.dispatchEvent(
                            new CustomEvent("debounce", { detail: val }),
                        );
                    },
                    modifiers.includes("100")
                        ? 100
                        : modifiers.includes("500")
                          ? 500
                          : 250,
                );
            });
        });
    },
);

// Optional magic property for loading states
Alpine.magic("loading", () => (el) => el.loading);

// ============================================================================
// Utility Functions (exported for reuse)
// ============================================================================

export function debounce(func, delay) {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
}

export function throttle(func, limit) {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            func(...args);
            inThrottle = true;
            setTimeout(() => (inThrottle = false), limit);
        }
    };
}

export function formatCurrency(value, currency = "USD") {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency,
    }).format(value);
}

// ============================================================================
// Analytics & Performance Monitoring
// ============================================================================

window.trackEvent = (eventName, eventData = {}) => {
    if (window.gtag) window.gtag("event", eventName, eventData);
};

window.addEventListener("load", () => {
    if (window.gtag) {
        window.gtag("pageview", {
            page_path: window.location.pathname,
            page_title: document.title,
        });
    }
});

if (import.meta.env.DEV && "PerformanceObserver" in window) {
    try {
        const observer = new PerformanceObserver((list) => {
            for (const entry of list.getEntries()) {
                console.debug(
                    `${entry.name}: ${entry.startTime?.toFixed(2) || entry.value?.toFixed(2)}ms`,
                );
            }
        });

        observer.observe({
            entryTypes: [
                "largest-contentful-paint",
                "first-input",
                "layout-shift",
            ],
        });
    } catch (e) {
        console.warn("PerformanceObserver not supported:", e);
    }
}

// ============================================================================
// Protected Contact Link Upgrader (email / tel / whatsapp) - DRY + accessible
// ============================================================================

function safeAtob(v) {
    try {
        return atob(v);
    } catch {
        return null;
    }
}

/** Reverse obfuscated contact value (matches obfuscate_contact() server-side) */
function deobfuscateContact(v) {
    if (!v || typeof v !== 'string') return v;
    return v.split('').reverse().join('');
}

function cleanPhone(v) {
    return (v || "").replace(/\D/g, "");
}

function buildHref(type, decoded, message) {
    if (type === "email") return `mailto:${decoded}`;
    if (type === "tel") return `tel:${cleanPhone(decoded)}`;
    if (type === "whatsapp") {
        const phone = cleanPhone(decoded);
        const text =
            message ||
            "Hi! I'd like a free 30-min consultation for my digital project.";
        return `https://wa.me/${phone}${text ? `?text=${encodeURIComponent(text)}` : ""}`;
    }
    return null;
}

function upgradeProtectedContact(el) {
    const type = el.dataset.type;
    const encoded = el.dataset.value;
    if (!type || !encoded) return el;

    let decoded = safeAtob(encoded);
    if (!decoded) return el;
    decoded = deobfuscateContact(decoded);

    let msg = el.dataset.message ? safeAtob(el.dataset.message) : null;
    if (msg) msg = deobfuscateContact(msg);
    const label =
        el.dataset.label ||
        el.getAttribute("aria-label") ||
        `Contact via ${type}`;
    const href = buildHref(type, decoded, msg);
    if (!href) return el;

    // If already <a>, just set attrs
    if (el.tagName.toLowerCase() === "a") {
        el.setAttribute("href", href);
        el.setAttribute("aria-label", label);

        if (type === "whatsapp") {
            el.setAttribute("target", el.getAttribute("target") || "_blank");
            el.setAttribute(
                "rel",
                el.getAttribute("rel") || "noopener noreferrer nofollow",
            );
        }
        return el;
    }

    // Replace span/div/etc with <a> (prevents nested anchors)
    const a = document.createElement("a");
    a.href = href;
    a.className = el.className.replace(/\bprotected-contact\b/g, "").trim();
    a.innerHTML = el.innerHTML;
    a.setAttribute("aria-label", label);

    if (type === "whatsapp") {
        a.target = "_blank";
        a.rel = "noopener noreferrer nofollow";
    }

    if (el.getAttribute("role") === "link") a.setAttribute("role", "link");
    if (el.hasAttribute("tabindex")) a.tabIndex = 0;

    el.replaceWith(a);
    return a;
}

function bindProtectedContactKeyboard(el) {
    if (el.dataset.protectedKeyboardBound === "1") return;
    el.dataset.protectedKeyboardBound = "1";

    if (!el.hasAttribute("tabindex")) el.tabIndex = 0;
    if (!el.getAttribute("role")) el.setAttribute("role", "link");

    el.addEventListener("keydown", (e) => {
        if (e.key !== "Enter" && e.key !== " ") return;
        e.preventDefault();
        const link = upgradeProtectedContact(el);
        if (link && link.href) link.click();
    });
}

function initProtectedContacts(root = document) {
    root.querySelectorAll(".protected-contact").forEach((el) => {
        bindProtectedContactKeyboard(el);
        upgradeProtectedContact(el);
    });
}

document.addEventListener("DOMContentLoaded", () => initProtectedContacts());
window.initProtectedContacts = initProtectedContacts;

// ============================================================================
// Guest layout enhancements (moved from inline scripts in guest.blade.php)
// ============================================================================

function initReadingProgress() {
    const bar = document.getElementById("reading-progress-bar");
    if (!bar) return;
    const updateBar = () => {
        const d = document.documentElement;
        const scrolled = d.scrollTop || document.body.scrollTop;
        const total = d.scrollHeight - d.clientHeight;
        bar.style.width =
            (total > 0 ? Math.min(100, (scrolled / total) * 100) : 0) + "%";
    };
    window.addEventListener("scroll", updateBar, { passive: true });
}

function initPreloader() {
    const p = document.getElementById("preloader");
    if (!p) return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        p.remove();
        return;
    }
    let hidden = false;
    const hide = () => {
        if (hidden || !p) return;
        hidden = true;
        p.style.opacity = "0";
        setTimeout(() => p?.remove(), 300);
    };
    document.readyState === "complete"
        ? hide()
        : window.addEventListener("load", hide, { once: true });
    setTimeout(hide, 1500);
}

function initBackToTop() {
    const btn = document.getElementById("back-to-top");
    if (!btn) return;
    btn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
    window.addEventListener(
        "scroll",
        () => {
            const show = window.scrollY > 400;
            btn.style.opacity = show ? "1" : "0";
            btn.style.pointerEvents = show ? "auto" : "none";
        },
        { passive: true },
    );
}

function initSkeletonImageCleanup() {
    const clearSkeleton = (img) => {
        const wrap = img.closest("[data-img-wrap]");
        if (!wrap) return;
        const skel = wrap.querySelector("[data-skeleton]");
        if (skel) skel.remove();
    };
    document.querySelectorAll("[data-img-wrap] img").forEach((img) => {
        if (img.complete && img.naturalWidth > 0) {
            clearSkeleton(img);
        } else {
            img.addEventListener("load", () => clearSkeleton(img));
            img.addEventListener("error", () => clearSkeleton(img));
        }
    });
}

function initCodeCopyButtons() {
    if (!navigator.clipboard) return;
    document.querySelectorAll("pre code").forEach((block) => {
        const pre = block.parentElement;
        if (!pre || pre.querySelector(".copy-btn")) return;
        pre.style.position = "relative";
        const btn = document.createElement("button");
        btn.className = "copy-btn";
        btn.textContent = "Copy";
        btn.setAttribute("aria-label", "Copy code");
        btn.style.cssText =
            "position:absolute;top:0.5rem;right:0.5rem;padding:0.2rem 0.5rem;font-size:0.65rem;font-family:ui-monospace,monospace;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;background:#404040;color:#d4d4d4;border:none;cursor:pointer;transition:background 0.15s;line-height:1.5;";
        btn.addEventListener("mouseenter", () => {
            btn.style.background = "#525252";
        });
        btn.addEventListener("mouseleave", () => {
            btn.style.background = "#404040";
        });
        btn.addEventListener("click", () => {
            navigator.clipboard.writeText(block.textContent).then(() => {
                btn.textContent = "Copied!";
                btn.style.background = "#166534";
                btn.style.color = "#bbf7d0";
                setTimeout(() => {
                    btn.textContent = "Copy";
                    btn.style.background = "#404040";
                    btn.style.color = "#d4d4d4";
                }, 2000);
            });
        });
        pre.appendChild(btn);
    });
}

function initGuestLayoutEnhancements() {
    initReadingProgress();
    initPreloader();
    initBackToTop();
    initSkeletonImageCleanup();
    initCodeCopyButtons();
}

document.addEventListener("DOMContentLoaded", initGuestLayoutEnhancements);

// ============================================================================

(async () => {
    if (document.querySelector('[x-data*="contentGalleryLightbox"]')) {
        const { registerGalleryLightbox } = await import(
            "./modules/gallery-lightbox.js"
        );
        registerGalleryLightbox(Alpine);
    }

    if (document.querySelector('[x-data*="contactFormApp"]')) {
        const { registerContactForm } = await import(
            "./modules/contact-form.js"
        );
        registerContactForm(Alpine);
    }

    window.Alpine = Alpine;
    Alpine.start();
})();
