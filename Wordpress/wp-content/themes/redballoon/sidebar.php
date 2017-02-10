<?php
/*
  This is a standard sidebar for the site. If the simple page plugin is loaded and set for a page that sidebar will over-write the content of this page.
  For sites with a static sidebars use this file for the sidebar.
*/
?>

<?php/* Checks if you are using sidebar-1 then loads sidebar-1 from the widgets menu in wordpress */?>
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
<?php endif; ?>
