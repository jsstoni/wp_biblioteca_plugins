<?php
/**
 * @package IconPickColor
 */
/*
Plugin Name: IconPickColor
Description: Icon color
Author: Angel Bazan
*/
class Modal
{
	public function __construct()
	{
		add_action( 'admin_menu', array($this, 'dashboard_menu') );
		add_action( 'admin_enqueue_scripts', array($this, 'apariencie') );
		add_action( 'wp_ajax_nopriv_svdashboard', array($this, 'save_dashboard') );
		add_action( 'wp_ajax_svdashboard', array($this, 'save_dashboard') );
		add_action( 'wp_enqueue_scripts', array($this, 'assetsWP') );
		//add_action( 'wp_footer', array($this, 'displayModal') );
		add_shortcode( 'iconfooter', array($this, 'displayModal') );
	}

	public function apariencie($hook)
	{
		if( $hook != 'toplevel_page_modalbox' ) {
			return;
		}
		wp_enqueue_style( 'noto-sans', 'https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap', array( ), false, 'all' );
		wp_enqueue_style( 'color-pick', plugins_url( 'assets/color-picker.css', __FILE__ ), array(), false, 'all' );
		wp_enqueue_script( 'cp_js', plugins_url( 'assets/color-picker.js', __FILE__ ), array(), false, false );
		wp_enqueue_script('jquery');
		wp_enqueue_media();
		//plugins_url( 'assets/js/custom.js', __FILE__ )
	}

	public function dashboard_menu()
	{
		add_menu_page( 'ModalBox', 'ModalBox', 'manage_options', 'modalbox', array($this, 'dashboard'), 'dashicons-index-card' );
	}

	public function dashboard()
	{
		include ('views/panel.php');
	}

	public function save_dashboard()
	{
		$post = (isset($_POST['icon_url']) && !empty($_POST['icon_url'])) &&
		(isset($_POST['icontext']) && !empty($_POST['icontext'])) &&
		(isset($_POST['link']) && !empty($_POST['link']));
		if ($post) {
			update_option( 'icon_url', $_POST['icon_url'] );
			update_option( 'icontext', $_POST['icontext'] );
			update_option( 'link', $_POST['link'] );
			update_option( 'colorbtn', isset($_POST['colorbtn']) ? $_POST['colorbtn'] : '#000' );
			update_option( 'colortext', isset($_POST['colortext']) ? $_POST['colortext'] : '#fff' );
			echo "save";
		}else {
			echo "Completar todos los campos";
		}
		wp_die();
	}

	public function assetsWP()
	{
		wp_enqueue_style( 'ModalBox', plugins_url('assets/modal.css', __FILE__), array(), '1.0.0', 'all' );
	}

	public function displayModal()
	{
		if (!empty(get_option( 'icon_url' ))) {
			include('views/box.php');
		}
	}
}

$modalBox = new Modal();