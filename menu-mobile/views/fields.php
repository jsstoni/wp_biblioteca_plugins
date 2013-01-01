<?php
$color_item = get_post_meta( $item_id, '_color_item', true );
$bg_item = get_post_meta( $item_id, '_background_items', true );
$menu_image = get_post_meta( $item_id, '_icon_item', true );
?>
<div class="description-wide" style="margin: 5px 0;">
	<div class="logged-input-holder">
		<label>
			<?php _e( 'Color de texto', 'MenuMobile'); ?>
			<br>
			<input type="text" name="color_item[<?php echo $item_id ;?>]" class="widefat edit-menu-item-title" value="<?php echo esc_attr( $color_item ); ?>" />
		</label>
	</div>

	<div class="logged-input-holder">
		<label>
			<?php _e( 'Fondo de color', 'MenuMobile'); ?>
			<br>
			<input type="text" name="bg_item[<?php echo $item_id ;?>]" class="widefat edit-menu-item-title" value="<?php echo esc_attr( $bg_item ); ?>" />
		</label>
	</div>

	<div>
		<img src="<?php echo $menu_image; ?>" id="preview-<?php echo $item_id; ?>" width="55" height="55">
	</div>

	<div class="logged-input-holder">
		<label for=""><?php _e( 'Imagen', 'MenuMobile'); ?></label>
		<input type="text" name="menu_image[<?php echo $item_id; ?>]" id="menu_image-<?php echo $item_id; ?>" value="<?php echo $menu_image; ?>">
		<input type="button" class="button-secondary icon-tilby-upload" data-id="<?php echo $item_id; ?>" value="Upload Image">
	</div>
</div>