<div class="icon-modal-box" id="open-modal">
	<a href="#" class="closebox" id="close-icon">x</a>
	<a href="<?php echo get_option( 'link' ); ?>" target="_blank"><img src="<?php echo get_option( 'icon_url' ); ?>" alt=""></a>
	<a href="<?php echo get_option( 'link' ); ?>" target="_blank" class="icontext" style="background: <?php echo get_option( 'colorbtn'); ?>; color: <?php echo get_option( 'colortext' ); ?>"><?php echo get_option( 'icontext' ); ?></a>
</div>

<script>
jQuery(document).ready(function($) {
	$("#close-icon").on('click', function() {
		$("#open-modal").fadeOut(1000);
	});
});
</script>