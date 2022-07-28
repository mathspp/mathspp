function custom_button_dismiss() {
    document.cookie = 'custom-banner-dismiss=true; max-age=1800; SameSite=Strict';
    document.getElementsByClassName('custom-banner-container')[0].style.display = 'none';
}

const hidden = document.cookie
	.split('; ')
	.find(row => row.startsWith('custom-banner-dismiss='));

document.addEventListener('DOMContentLoaded', (event) => {
	if (hidden) {
		custom_button_dismiss();
	}
});
