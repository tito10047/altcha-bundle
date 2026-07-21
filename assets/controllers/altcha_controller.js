import {Controller} from '@hotwired/stimulus';

// Altcha v3+ (Svelte 5) sets `data-loading` SYNCHRONOUSLY during connectedCallback
// (initial render), which conflicts with Symfony UX Live Component's `data-loading`
// loading-state directive. Static imports are hoisted and run before any module code,
// so we must use a dynamic import to ensure this patch is active before altcha executes.
//
// At the time setAttribute is called the element is not yet appended to the DOM, so
// closest('altcha-widget') returns null. We target altcha's known internal class names
// instead: altcha-checkbox, altcha-checkbox-native, altcha-switch.
{
	const _orig = Element.prototype.setAttribute;
	if (!_orig._altchaDataLoadingPatched) {
		const patched = function (name, value) {
			if (name === 'data-loading' && (
				this.classList.contains('altcha-checkbox') ||
				this.classList.contains('altcha-checkbox-native') ||
				this.classList.contains('altcha-switch')
			)) return;
			return _orig.call(this, name, value);
		};
		patched._altchaDataLoadingPatched = true;
		Element.prototype.setAttribute = patched;
	}
}

await import('altcha/dist/main/altcha.i18n.js');

export default class extends Controller {

	static targets = ["input", "altcha"];
	static values = {
		hideLogo:       Boolean,
		hideFooter:     Boolean,
		useSentinel:    Boolean,
		overlayContent: String,
		challengeUrl:   String,
	}

	connect() {
		this.altchaTarget.addEventListener('statechange', (ev) => {
			if (ev.detail.state === 'verified') {
				this.inputTarget.value = ev.detail.payload;
				this.inputTarget.dispatchEvent(new Event('change', {bubbles: true}));
			}
		});

		const config = {};

		if (this.hasHideLogoValue) {
			config.hideLogo = this.hideLogoValue;
		}
		if (this.hasHideFooterValue) {
			config.hideFooter = this.hideFooterValue;
		}
		if (this.hasUseSentinelValue) {
			config.useSentinel = this.useSentinelValue;
			config.fetch = this.altchaChallengeFetchWithFallback.bind(this);
		}
		if (this.hasOverlayContentValue) {
			config.overlayContent = this.overlayContentValue;
		}
		this.altchaTarget.configure(config);
	}

	async altchaChallengeFetchWithFallback(url, init) {
		try {
			return await fetch(url, init);
		} catch (e) {
			return await fetch(this.challengeUrlValue, init);
		}
	}
}
