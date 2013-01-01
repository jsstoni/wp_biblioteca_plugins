<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" id="save_box">
	<input type="hidden" name="action" value="svdashboard">
	<h3>ModalBox</h3>
	<p>
	<label for="icon_url">Icon</label><br>
	<input type="text" name="icon_url" id="icon_url" class="regular-text" value="<?php echo get_option( 'icon_url' ); ?>">
	<input type="button" name="upload-btn" id="icon-upload" class="button-secondary" value="Upload Image">
	</p>
	<p>
		<label for="">Texto</label><br>
		<input type="text" name="icontext" class="regular-text" value="<?php echo get_option( 'icontext' ); ?>">
	</p>
	<p>
		<label for="">Modal link</label><br>
		<input type="text" name="link" class="regular-text" value="<?php echo get_option( 'link' ); ?>">
	</p>
	<p>
		<label for="" style="padding: 10px; display: inline-block;" id="cbtn">Color: Button <?php echo get_option( 'colorbtn' ); ?></label><br>
		<input type="text" name="colorbtn" class="regular-text color-pick" value="<?php echo get_option( 'colorbtn' ); ?>" id="selcolor" autocomplete="false">
	</p>
	<p>
		<label for="" style="padding: 10px; display: inline-block;" id="ctext">Color: Text <?php echo get_option( 'colortext' ); ?></label><br>
		<input type="text" name="colortext" class="regular-text color-pick" value="<?php echo get_option( 'colortext' ); ?>" id="selcolor" autocomplete="false">
	</p>
	<input type="submit" name="send" value="Save" class="button-primary">
</form>

<script>
jQuery(document).ready(function($) {
	var HEX = CP.HEX; // Old hex color parser
	CP.HEX = function(x) {
		x = HEX(x);
		if ('string' === typeof x) {
			var count = x.length;
			if (9 === count && x[1] === x[2] && x[3] === x[4] && x[5] === x[6] && x[7] === x[8]) {
				// Shorten!
				return x[0] + x[1] + x[3] + x[5] + x[7];
			}
			if (7 === count && x[1] === x[2] && x[3] === x[4] && x[5] === x[6]) {
				// Shorten!
				return x[0] + x[1] + x[3] + x[5];
			}
		}
		return x;
	};
	var colorpick = document.querySelectorAll('.color-pick');
	for (var i = 0, j = colorpick.length; i < j; ++i) {
		(new CP(colorpick[i])).on('change', function(r, g, b, a) {
			this.source.value = this.color(r, g, b, a);
		});
	}

	$("#icon-upload").on('click', function() {
		var image = wp.media({ 
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e) {
			var uploaded_image = image.state().get('selection').first();
			var icon_url = uploaded_image.toJSON().url;
			$('#icon_url').val(icon_url);
		});
	});

	$("#save_box").on('submit', function(event) {
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(result) {
				alert(result);
			}
		})
		event.preventDefault();
	});
});
</script>