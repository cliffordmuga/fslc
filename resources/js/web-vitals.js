import { onCLS, onINP, onLCP } from "web-vitals/attribution";

function sendWebVital(metric) {
    if (typeof window.gtag !== "function") {
        return;
    }

    window.gtag("event", metric.name, {
        event_category: "Web Vitals",
        value: Math.round(
            metric.name === "CLS" ? metric.value * 1000 : metric.value,
        ),
        event_label: metric.id,
        non_interaction: true,
    });
}

onCLS(sendWebVital);
onINP(sendWebVital);
onLCP(sendWebVital);
