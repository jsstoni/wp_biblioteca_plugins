<?php
/**
 * @package CRSelect
 */
/*
Plugin Name: Chile CR
Description: Comundas y Regiones de Chile
Author: jsstoni
*/ 
define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

$comunas = array();
$region = array(
	"" => "Seleccionar Región",
	1 => "Arica y Parinacota",
	2 => "Tarapacá",
	3 => "Antofagasta",
	4 => "Atacama",
	5 => "Coquimbo",
	6 => "Valparaiso",
	7 => "Metropolitana",
	8 => "Libertador General Bernardo O'Higgins",
	9 => "Maule",
	10 => "Ñuble",
	11 => "Biobío",
	12 => "La Araucanía",
	13 => "Los Ríos",
	14 => "Los Lagos",
	15 => "Aisén",
	16 => "Magallanes y de la Antártica Chilena"
);
function remove_fields_addrres($fields)
{
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_state']);
	return $fields;
}
add_filter('woocommerce_checkout_fields', 'remove_fields_addrres');

function select_comuna_region($fields)
{
	global $region;
	$fields['region'] = array(
		'type' => 'select',
		'label' => __('Región', 'woocommerce'),
		'required' => true,
		'id' => 'select-region',
		'options' => $region,
		'priority' => 40,
		'default' => 0
	);
	$fields['comuna'] = array(
		'type' => 'select',
		'label' => __('Comuna', 'woocommerce'),
		'required' => true,
		'id' => 'communes',
		'custom_attributes' => array( 'disabled' => true),
		'options' => array('0' => 'Seleccionar comuna'),
		'priority' => 40,
	);
	return $fields;
}
add_filter( 'woocommerce_default_address_fields' , 'select_comuna_region' );

function rc_add_jscript_checkout()
{
?>
<script>
jQuery(document).on('ready', function() {
	jQuery("#select-region").on('change', function() {
		var id = jQuery(this).find('option:selected').val();
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			data: 'action=region'+'&id='+id,
			success: function(result) {
				jQuery("#communes").prop("disabled", false);
				jQuery("#communes").find('option:not(:first-of-type)').remove();
				var d = JSON.parse(result);
				jQuery.each(d, function(i, v) {
					jQuery("#communes").append(jQuery("<option/>").val(v).text(v));
				});
			}
		})
	});
});
</script>
<?php
}
add_action( 'woocommerce_after_checkout_form', 'rc_add_jscript_checkout');

function ajax_change_region()
{
	include MY_PLUGIN_PATH.'/comunas.php';
	$id = sanitize_text_field($_POST['id']);
	echo json_encode($communes[$id-1]);
	wp_die();
}
add_action('wp_ajax_region', 'ajax_change_region');
add_action('wp_ajax_nopriv_region', 'ajax_change_region');

function save_new_checkout_field( $order_id ) {
	global $region;
	if ( $_POST['billing_region'] ) update_post_meta( $order_id, 'billing_region', esc_attr( $region[$_POST['billing_region']] ) );
	if ( $_POST['billing_comuna'] ) update_post_meta( $order_id, 'billing_comuna', esc_attr( $_POST['billing_comuna'] ) );
}
add_action( 'woocommerce_checkout_update_order_meta', 'save_new_checkout_field' );

function select_checkout_field_display($order){
	echo '<p><strong>'.__('Región').':</strong> ' . get_post_meta( $order->id, 'billing_region', true ) . '</p>';
	echo '<p><strong>'.__('Comuna').':</strong> ' . get_post_meta( $order->id, 'billing_comuna', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'select_checkout_field_display', 10, 1 );

function view_order_page($order)
{
	echo '<p><strong>'.__('Región').':</strong> ' . get_post_meta( $order->id, 'billing_region', true ) . '</p>';
	echo '<p><strong>'.__('Comuna').':</strong> ' . get_post_meta( $order->id, 'billing_comuna', true ) . '</p>';
}
add_action( 'woocommerce_view_order', 'view_order_page', 20 );
?>