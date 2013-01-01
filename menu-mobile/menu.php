<?php
/**
 * @package MenuMobilePro
 */
/*
Plugin Name: MenuMobilePro
Description: Menu Mobile
Author: #MarketingCaribe
*/
class MenuMobile
{
	public function __construct()
	{
		add_action( 'init', array($this, 'show_menu_option_admin') );
		add_action( 'wp_nav_menu_item_custom_fields', array($this, 'add_custom_fields_items_menu'), 10, 2 );
		add_action( 'admin_enqueue_scripts', array($this, 'admin_script') );
		add_action( 'admin_footer', array($this, 'js_admin_footer') );
		if (wp_is_mobile()) {
			add_filter( 'nav_menu_item_title', array($this, 'custom_link_menu'), 10, 2 );
			add_filter( 'nav_menu_link_attributes', array($this, 'add_attributes_items_menu'), 10, 3 );
			add_action( 'wp_head', array($this, 'display_menu_frontend') );
			add_action( 'wp_footer', array($this, 'display_menu_home_frontend') );
			add_action( 'wp_enqueue_scripts', array($this, 'theme_style_menu') );
		}
		add_action( 'wp_update_nav_menu_item', array($this, 'save_options_menu_items'), 10, 2 );
	}

	public function admin_script() {
		wp_enqueue_media();
	}

	public function theme_style_menu($hook)
	{
		wp_enqueue_style( 'tilby-burger-menu', plugins_url( 'assets/css/style.css', __FILE__ ), array( ), false, 'all' );
		wp_enqueue_script( 'menu-js', plugins_url( 'assets/js/menu.js', __FILE__ ), array( 'jquery' ), false, false );
	}

	public function show_menu_option_admin()
	{
		register_nav_menu( 'mobile-menu', __( 'Menu Mobile' ) );
	}

	public function add_attributes_items_menu($atts, $item, $args)
	{
		//$item->ID
		$color_item = get_post_meta( $item->ID, '_color_item', true );
		$bg_item = get_post_meta( $item->ID, '_background_items', true );
		$atts['style'] = "background: {$bg_item}; color: {$color_item}";
		return $atts;
	}

	public function custom_link_menu($title, $item)
	{
		if( is_object( $item ) && isset( $item->ID ) ) {
			$icon = get_post_meta( $item->ID, '_icon_item', true );
			if ( ! empty( $icon ) ) {
				$title = "<img src=\"".$icon."\"><span>".$title."</span><img src=\"".plugins_url( 'assets/right-chevron.svg', __FILE__ )."\" class=\"chevron\">";
			}
		}
		return $title;
	}

	public function add_custom_fields_items_menu($item_id, $item)
	{
		include ('views/fields.php');
	}

	public function display_menu_frontend()
	{
		include ('views/menu.php');
	}

	public function display_menu_home_frontend()
	{
		include ('views/open.php');
	}

	public function save_options_menu_items($menu_id, $menu_item_db_id)
	{
		//BG
		update_post_meta( $menu_item_db_id, '_background_items', isset($_POST['bg_item'][$menu_item_db_id]) ? $_POST['bg_item'][$menu_item_db_id] : '#fff' );
		update_post_meta( $menu_item_db_id, '_color_item', isset($_POST['color_item'][$menu_item_db_id]) ? $_POST['color_item'][$menu_item_db_id] : '#fff' );
		update_post_meta( $menu_item_db_id, '_icon_item', isset($_POST['menu_image'][$menu_item_db_id]) ? $_POST['menu_image'][$menu_item_db_id] : '' );
	}

	public function js_admin_footer()
	{
		echo <<<'EOT'
<script>
jQuery(document).ready(function($) {
	$(document).on('click', ".icon-tilby-upload", function() {
		var name = $(this).data('id');
		var image = wp.media({ 
		title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e) {
			var uploaded_image = image.state().get('selection').first();
			var icon_url = uploaded_image.toJSON().url;
			$('#menu_image-'+name).val(icon_url);
			$('#preview-'+name).attr('src', icon_url);
		});
	});
});
</script>
EOT;
	}
}

$men = new MenuMobile();