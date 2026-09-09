/**
 * Gallery lightbox — lazy-loaded on pages with content-gallery only.
 */
export function registerGalleryLightbox(Alpine) {
    Alpine.data("contentGalleryLightbox", (images = []) => ({
        images: Array.isArray(images) ? images : [],
        currentIndex: 0,
        isOpen: false,
        lastFocused: null,

        init() {
            this._onKey = (e) => {
                if (!this.isOpen) return;
                if (e.key === "Escape") {
                    e.preventDefault();
                    this.close();
                    return;
                }
                if (e.key === "ArrowRight") {
                    e.preventDefault();
                    this.next();
                    return;
                }
                if (e.key === "ArrowLeft") {
                    e.preventDefault();
                    this.prev();
                    return;
                }
                if (e.key === "Tab") {
                    this.trapTab(e);
                }
            };
        },

        trapTab(e) {
            const root = this.$refs.overlay;
            if (!root) return;
            const sel =
                'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
            const nodes = [...root.querySelectorAll(sel)].filter(
                (el) => el.offsetParent !== null || el.getClientRects().length > 0,
            );
            if (nodes.length === 0) return;
            const first = nodes[0];
            const last = nodes[nodes.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        },

        openAt(index) {
            this.lastFocused = document.activeElement;
            this.currentIndex = Math.max(
                0,
                Math.min(index, this.images.length - 1),
            );
            this.isOpen = true;
            document.body.style.overflow = "hidden";
            window.addEventListener("keydown", this._onKey, true);
            this.$nextTick(() => {
                this.$refs.lightboxClose?.focus();
            });
        },

        close() {
            this.isOpen = false;
            document.body.style.overflow = "";
            window.removeEventListener("keydown", this._onKey, true);
            const ref = this.lastFocused;
            this.lastFocused = null;
            this.$nextTick(() => {
                if (ref && typeof ref.focus === "function") {
                    ref.focus();
                }
            });
        },

        next() {
            if (this.images.length <= 1) return;
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },

        prev() {
            if (this.images.length <= 1) return;
            this.currentIndex =
                (this.currentIndex - 1 + this.images.length) %
                this.images.length;
        },

        destroy() {
            if (this.isOpen) {
                document.body.style.overflow = "";
                window.removeEventListener("keydown", this._onKey, true);
            }
        },
    }));
}
