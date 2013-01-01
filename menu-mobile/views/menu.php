<nav class="menu-burger-tilby" id="tilby-menu-display">
<a href="#" class="menu-close-tilby" id="menu-close-tilby">x</a>
<?php
wp_nav_menu( array( 
	'theme_location' => 'mobile-menu',
	'container' => false,
	'items_wrap' => '<ul>%3$s</ul>'
) );
?>
</nav>