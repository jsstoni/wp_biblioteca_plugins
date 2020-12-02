<?php
/**
 * @package Ulisting-Export
 */
/*
Plugin Name: Ulisting-Export
Description: Export posts ulisting
Author: #Jsstoni
*/
function export_db_csv()
{
	add_options_page('Exportar publicaciones', 'Ulisting-Exp', 'manage_options', 'export', 'export_page_view');
}
add_action('admin_menu', 'export_db_csv');

function export_page_view()
{
?>
<a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=exportcsv" style="background: #3498DB; color: #fff; padding: 20px 35px; margin: 45px 0; text-align: center; border-radius: 50px; display: block;">Exportar publicaciones</a>
<?php
}

function cleanData(&$str) {
	$str = preg_replace("/\t/", "\\t", $str);
	$str = preg_replace("/\r?\n/", "\\n", $str);
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	$str = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
}

function ajax_admin_exportcsv()
{
	global $wpdb;
	$sql_ids = "SELECT ID FROM $wpdb->posts WHERE post_type = 'listing'";
	$ids = $wpdb->get_results($sql_ids);
	$ids = array_map(function($k) {
		return $k->ID;
	}, $ids);
	$sql_export = "SELECT p.post_title, p.post_date, p.post_modified, l.* FROM wp_ulisting_listing_attribute_relationships AS l LEFT JOIN {$wpdb->posts} AS p ON (l.listing_id = p.ID) WHERE listing_id IN (".implode(",", $ids).") AND attribute IN ('address', 'latitude', 'longitude', 'square_feet', 'price', 'tamao_ha', 'description')";
	$result = $wpdb->get_results($sql_export);
	$export = array();
	foreach($result as $key => $data) {
		$export[$data->listing_id]['ID'] = $data->listing_id;
		$export[$data->listing_id]['titulo'] = $data->post_title;
		$export[$data->listing_id]['creado'] = $data->post_date;
		$export[$data->listing_id]['modificado'] = $data->post_modified;
		$export[$data->listing_id][$data->attribute] = $data->value;
	}
	$rows = array();
	foreach ($export as $k => $d) {
		$rows[] = array('ID' => $d['ID'], 'titulo' => $d['titulo'], 'creado' => $d['creado'], 'modificado' => $d['modificado'], 'Direccion' => $d['address'], 'Lat' => $d['latitude'], 'Lng' => $d['longitude'], 'precio' => $d['price'], 'Hectárea' => $d['tamao_ha'], 'M2' => $d['square_feet'], 'Contenido' => $d['description']);
	}
	$file = date("d-m-Y")."_publicaciones.xls";
	header("Content-Disposition: attachment; filename=\"$file\"");
	header("Content-Type: application/vnd.ms-excel");
	$flag = false;
	foreach($rows as $row) {
		if(!$flag) {
			echo implode("\t", array_keys($row)) . "\r\n";
			$flag = true;
		}
		array_walk($row, __NAMESPACE__ . '\cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	}
	exit;
}

add_action('wp_ajax_exportcsv', 'ajax_admin_exportcsv');
add_action('wp_ajax_nopriv_exportcsv', 'ajax_admin_exportcsv');