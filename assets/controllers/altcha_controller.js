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
