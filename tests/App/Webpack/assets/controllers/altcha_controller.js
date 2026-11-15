import {Controller} from '@hotwired/stimulus';
import 'altcha/dist/main/altcha.i18n.js';

export default class extends Controller {

	static targets = ["input", "altcha"];
	static values = {
		hideLogo:       Boolean,
		hideFooter:     Boolean,
		useSentinel:    Boolean,
		overlayContent: String,
		challengeUrl:   String,
	}

	async connect() {
		const widget = this.altchaTarget;

		widget.addEventListener('statechange', (ev) => {
			if (ev.detail.state === 'verified') {
				this.inputTarget.value = ev.detail.payload;
				this.inputTarget.dispatchEvent(new Event('change', {bubbles: true}));
			}
		});

		await this.waitForWidget(widget);
		if (!this.element.isConnected) {
			return;
		}

		widget.configure(this.buildConfig());
	}

	/**
	 * Altcha v3 (Svelte 5 custom element) exposes configure() only after the
	 * inner component is mounted, which happens asynchronously in connectedCallback.
	 */
	async waitForWidget(widget) {
		await customElements.whenDefined('altcha-widget');
		while (typeof widget.configure !== 'function' && this.element.isConnected) {
			await new Promise((resolve) => requestAnimationFrame(resolve));
		}
	}

	buildConfig() {
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

		return config;
	}

	async altchaChallengeFetchWithFallback(url, init) {
		try {
			return await fetch(url, init);
		} catch (e) {
			return await fetch(this.challengeUrlValue, init);
		}
	}
}
