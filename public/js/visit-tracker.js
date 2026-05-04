(() => {
    'use strict';

    const currentScript = document.currentScript;

    const endpoint = currentScript?.dataset.endpoint;

    if (!endpoint) {
        console.warn('[visit-tracker] data-endpoint attribute is required.');
        return;
    }

    const STORAGE_KEY = 'visit_tracker_visitor_id';

    const createUuid = () => {
        if (window.crypto?.randomUUID) {
            return window.crypto.randomUUID();
        }

        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (char) => {
            const random = Math.random() * 16 | 0;
            const value = char === 'x'
                ? random
                : (random & 0x3) | 0x8;

            return value.toString(16);
        });
    };

    const getVisitorId = () => {
        let visitorId = localStorage.getItem(STORAGE_KEY);

        if (!visitorId) {
            visitorId = createUuid();

            localStorage.setItem(STORAGE_KEY, visitorId);
        }

        return visitorId;
    };

    const getDeviceType = () => {
        const userAgent = navigator.userAgent.toLowerCase();

        if (/mobile|android|iphone|ipod|blackberry|windows phone/.test(userAgent)) {
            return 'mobile';
        }

        if (/tablet|ipad/.test(userAgent)) {
            return 'tablet';
        }

        return 'desktop';
    };

    const getScreenSize = () => {
        if (!window.screen) {
            return null;
        }

        return `${window.screen.width}x${window.screen.height}`;
    };

    const buildPayload = () => ({
        visitor_id: getVisitorId(),
        device_type: getDeviceType(),
        user_agent: navigator.userAgent || null,
        page_url: window.location.href,
        referrer: document.referrer || null,
        language: navigator.language || null,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone || null,
        screen: getScreenSize(),
    });

    const sendVisit = () => {
        const body = JSON.stringify(buildPayload());

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body,
            keepalive: true,
            credentials: 'omit',
        }).catch((error) => {
            console.warn('[visit-tracker] Visit was not sent.', error);
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sendVisit, { once: true });
    } else {
        sendVisit();
    }
})();