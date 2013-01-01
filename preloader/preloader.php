<?php
/**
 * @package PPLoad
 */
/*
Plugin Name: PPLoad
Description: Preload
Author: #MarketingCaribe
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class PPLoad
{
	function __construct()
	{
		add_action( 'wp_head', array($this, 'loader_header') );
		add_action( 'wp_footer', array($this, 'loader_footer') );
	}

	public function loader_header()
	{
		include ('views/loader.php');
	}

	public function loader_footer()
	{
		echo "<script>
		jQuery(document).ready(function($) {
			setTimeout(function() {
				$(\".blank-tilby\").animate({ left: '-100%' }, 1000);
				//$(\".blank-tilby\").fadeOut(400);
			}, 7000);
		});
		</script>";
	}
}

$loader = new PPLoad();