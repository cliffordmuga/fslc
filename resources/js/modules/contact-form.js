/**
 * Contact form Alpine component — lazy-loaded on /contact only.
 */
export function registerContactForm(Alpine) {
    Alpine.data("contactFormApp", (config = {}) => ({
        step: config.singleStep ? 2 : 1,
        singleStep: Boolean(config.singleStep),
        requireService: config.requireService !== false,
        formData: {
            email: "",
            name: "",
            phone: "",
            service_content_id: "",
            message: "",
            file: null,
        },
        errors: {},
        loading: false,
        draftKey: config.draftKey || "fsl_contact_form_draft_v1",
        _draftTimer: null,
        inquiryType: config.inquiryType || "general",
        formAction: config.formAction || "",
        leadEventsUrl: config.leadEventsUrl || "",
        successRedirect: config.successRedirect || "",

        init() {
            this.$watch("formData", () => {
                this.validate();
                this.scheduleDraftSave();
            });
            this.loadDraft();
            if (config.preselectedServiceId) {
                this.formData.service_content_id = String(
                    config.preselectedServiceId,
                );
            }
        },

        loadDraft() {
            try {
                const raw = sessionStorage.getItem(this.draftKey);
                if (!raw) return;
                const d = JSON.parse(raw);
                if (d.email) this.formData.email = d.email;
                if (d.name) this.formData.name = d.name;
                if (d.phone) this.formData.phone = d.phone;
                if (d.message) this.formData.message = d.message;
                if (d.service_content_id)
                    this.formData.service_content_id = String(
                        d.service_content_id,
                    );
                if (d.step === 2 && !this.singleStep) this.step = 2;
            } catch (e) {}
        },

        scheduleDraftSave() {
            clearTimeout(this._draftTimer);
            this._draftTimer = setTimeout(() => this.saveDraft(), 600);
        },

        saveDraft() {
            try {
                sessionStorage.setItem(
                    this.draftKey,
                    JSON.stringify({
                        email: this.formData.email,
                        name: this.formData.name,
                        phone: this.formData.phone,
                        message: this.formData.message,
                        service_content_id: this.formData.service_content_id,
                        step: this.step,
                    }),
                );
            } catch (e) {}
        },

        clearDraft() {
            try {
                sessionStorage.removeItem(this.draftKey);
            } catch (e) {}
        },

        validate() {
            this.errors = {};
            const onSubmitStep = this.singleStep || this.step === 2;
            if (!this.formData.email) this.errors.email = "Email is required.";
            if (onSubmitStep && !this.formData.name)
                this.errors.name = "Name is required.";
            if (
                onSubmitStep &&
                this.requireService &&
                !this.formData.service_content_id
            ) {
                this.errors.service_content_id = "Please select a service.";
            }
            if (
                onSubmitStep &&
                this.inquiryType !== "hmis-checklist" &&
                !this.formData.message
            ) {
                this.errors.message = "Message is required.";
            }
        },

        trackLeadEvent(eventName, stepName = null) {
            const params = new URLSearchParams(window.location.search);
            const payload = JSON.stringify({
                event: eventName,
                step: stepName,
                source: this.inquiryType,
                page: window.location.pathname,
                utm_source: params.get("utm_source") || "direct",
                utm_medium: params.get("utm_medium") || "organic",
                utm_campaign: params.get("utm_campaign") || null,
            });
            if (navigator.sendBeacon) {
                navigator.sendBeacon(
                    this.leadEventsUrl,
                    new Blob([payload], { type: "application/json" }),
                );
                return;
            }
            fetch(this.leadEventsUrl, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: payload,
                keepalive: true,
            }).catch(() => {});
        },

        async submitForm(e) {
            this.loading = true;
            if (!this.singleStep) this.step = 2;
            this.validate();
            if (Object.keys(this.errors).length > 0) {
                this.loading = false;
                return;
            }
            try {
                const formData = new FormData(e.target);
                formData.set("inquiry_type", this.inquiryType);
                const params = new URLSearchParams(window.location.search);
                formData.set("utm_source", params.get("utm_source") || "direct");
                formData.set("utm_medium", params.get("utm_medium") || "organic");
                formData.set("utm_campaign", params.get("utm_campaign") || "");
                const response = await fetch(this.formAction, {
                    method: "POST",
                    body: formData,
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });
                if (response.ok) {
                    this.clearDraft();
                    const data = await response.json().catch(() => ({}));
                    this.trackLeadEvent("lead_submit_success", "submit");
                    window.location.href =
                        data.redirect || this.successRedirect;
                } else {
                    const errorData = await response.json().catch(() => ({}));
                    this.errors = errorData.errors || {
                        general:
                            errorData.message || "Submission failed.",
                    };
                }
            } catch (error) {
                this.errors.general =
                    "Network error. Please check connection.";
            }
            this.loading = false;
        },
    }));
}
