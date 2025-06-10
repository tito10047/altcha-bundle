import {Controller} from '@hotwired/stimulus';
import 'altcha/i18n';

export default class extends Controller {

	static targets = ["input","altcha"]

	connect() {
		this.altchaTarget.addEventListener('statechange', (ev) => {
			if (ev.detail.state === 'verified') {
				this.inputTarget.value = ev.detail.payload;
				this.inputTarget.dispatchEvent(new Event('change', { bubbles: true }));
			}
		});
	}
}
