'use strict';
jQuery(document).ready(function () {
	if (typeof wc_cart_fragments_params === 'undefined' || wc_cart_fragments_params === null) {
	} else {
		sessionStorage.removeItem(wc_cart_fragments_params.fragment_name);
	}
});