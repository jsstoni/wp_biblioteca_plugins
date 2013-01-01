<?php
/*
Plugin Name: SUSCRIPTOS
Description: usuarios supcristos bloquear por categoria
Author: #JSSTONI
*/
/**
 * Funciones por Marketing Caribe
 */
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 20);
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
//Permite agregar un solo producto al carro
function only_one_item_in_cart( $cartItemData ) {
	wc_empty_cart();
	return $cartItemData;
}
add_filter( 'woocommerce_add_cart_item_data', 'only_one_item_in_cart', 10, 1 );

//Eliminar cantidad de productos
function woo_remove_all_quantity_fields( $return, $product ) {
	return true;
}
add_filter( 'woocommerce_is_sold_individually', 'woo_remove_all_quantity_fields', 10, 2 );

//Redireccionar al agregar producto al carro
function cod_redirect_checkout_add_cart($url)
{
	$url = WC()->cart->get_checkout_url();
	return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'cod_redirect_checkout_add_cart' );

//redireccioar si es la pagina de carrito al checkout
function redirect_to_checkout_if_cart() {
	if ( !is_cart() ) return;
	global $woocommerce;
	if ( $woocommerce->cart->is_empty() ) {
		wp_redirect( get_home_url(), 302 );
	} else {
		wp_redirect( $woocommerce->cart->get_checkout_url(), 302 );
	}
	exit;
}
add_action( 'template_redirect', 'redirect_to_checkout_if_cart' );

//Quitar mensjae al agregar al carro
add_filter( 'wc_add_to_cart_message_html', '__return_false' );

//Quitar nota adicional
add_filter( 'woocommerce_checkout_fields' , 'bbloomer_remove_checkout_order_notes' );
function bbloomer_remove_checkout_order_notes( $fields ) {
	unset($fields['order']['order_comments']);
	return $fields;
}
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

//validar usuario suscriptor
function has_active_subs($user_id = '') {
	if ($user_id == '' && is_user_logged_in())
		$user_id = get_current_user_id();
	return wcs_user_has_subscription( $user_id, '', 'active' );
}
function check_subs_user_in_page_post($content) {
	global $post;
	if (has_category('subs', $post->ID)) {
		if (has_active_subs())
			return $content;
	}else {
		return $content;
	}
	return 'Solo usuarios VIP';
}
add_filter( 'the_content', 'check_subs_user_in_page_post' );
/**
 * end funciones
 */