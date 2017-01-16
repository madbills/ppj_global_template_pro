<!DOCTYPE html>
<?php
$lang = explode('-', get_bloginfo('language'));
$lang = gettype($lang) === 'array' ? $lang[0] : $lang;
?>
<html lang="<?=$lang?>">
<head>
	<!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Red Balloon">
    <meta name="google" content="notranslate" />

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">

    <title><?php wp_title( '-', true, 'right' );bloginfo( 'name' );?></title>

    <!-- Favicons -->
	<link rel="shortcut icon" type="image/ico" href="<?php bloginfo('template_url');?>/images/favicon.ico">

	<!-- Browser specific files (including no js -->
	    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
	<!-- End Browser Specifics -->

    <?// Important: Add styles to inc/enqueue-resources.php. Don't add them here. ?>

	<?php wp_head(); ?>

</head>

<body id="body" <?php body_class(); ?>>

<header class="header" id="header">
	<a href="#content" class="sr-only sr-only-focusable">Skip to main content</a>
	<div class="container">
		<div class="row">
			<div class='omin-bar'>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Language Switcher') ) : ?><?php endif; ?>
				
				<?
				// Values that get brought out from the settings page:

				// Contact number:
				$country_contact_number = get_option( 'country-contact-number' );
				// If not set bring it from site 1:
				$country_contact_number = strlen($country_contact_number) > 0 ? $country_contact_number : esc_attr( get_blog_option( 1, 'country-contact-number' ) );

				// Country name:
				$country_display_name = esc_attr( get_option( 'country-display-name' ) );
				// If not set bring it from site 1:
				$country_display_name = strlen($country_display_name) > 0 ? $country_display_name : esc_attr( get_blog_option( 1, 'country-display-name' ) );
				$country_display_name = stripslashes($country_display_name);
				
				?>
				<a href="tel:<?echo preg_replace("/[(^0-9)]/", "", $country_contact_number);?>" class="call-order">
					<i class='icon-phone'></i>
					<span><?=$country_contact_number?>, <?=$country_display_name?></span>
				</a>
			</div>
		</div>
	</div>
	<nav class="primary-nav">
		<div class="container">
			<div class="row">
				<div class='nav-wrap'>
					<div class="col-md-4 col-sm-4">
						<div class="logo-wrap">
							<a class="logo logo-image" href="<?=home_url()?>">
								<h1 class="text-hide">Home</h1>
							</a>
						</div>
					</div>
					<?php wp_nav_menu( array(
						'menu'=>'Main Menu',
						'theme_location'    => 'primary',
						'depth' => 1,
						'menu_class'=>'col-md-offset-2 col-md-18', 
						'container' => false, // set true to wrap ul in a div with class "container".
						'walker'=> new Custom_Nav_Walker() // Defined in functions.php
					));?>
				</div>
			</div>
		</div>
	</nav>
</header>


<?
$slides = get_post_meta($post->ID, '_slide_ids', true); // Gets the value of a custom meta field by post id and name.
if (strlen($slides) >= 1){?>
	<div class="container">
		<? echo do_shortcode( '[slides include="'.$slides.'" carousel_id="hero-carousel" arrows="hide"]' );?>
		<?// do homepage slides shortcode ?>
	</div>
<?
}
?>