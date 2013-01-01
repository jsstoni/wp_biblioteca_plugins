jQuery(document).ready(function($) {
	$("#open-menu-tilby").on('click', function() {
		$("#tilby-menu-display").fadeIn(400);
	});
	$("#menu-close-tilby").on('click', function() {
		$("#tilby-menu-display").fadeOut(400);
	});
});