<?php
	/*-----------------------------------------------------------------------------------*/
	/* This template will be called by all other template files to begin
	/* rendering the page and display the header/nav
	/*-----------------------------------------------------------------------------------*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title>
	<?php bloginfo('name'); // show the blog name, from settings ?> |
	<?php is_front_page() ? bloginfo('description') : wp_title(''); // if we're on the home page, show the description, from the site's settings - otherwise, show the title of the post or page ?>
</title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // We are loading our theme directory style.css by queuing scripts in our functions.php file,
	// so if you want to load other stylesheets,
	// I would load them with an @import call in your style.css
?>

<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head();
// This fxn allows plugins, and Wordpress itself, to insert themselves/scripts/css/files
// (right here) into the head of your website.
// Removing this fxn call will disable all kinds of plugins and Wordpress default insertions.
// Move it if you like, but I would keep it around.
?>

</head>

<body class="wrapper">

<img src="http://localhost:10004/wp-content/uploads/2022/02/compgrowing.png" class="background background-img">



		<header class="header-wrapper">
			<hgroup class="masthead">
				<img src="http://localhost:10004/wp-content/uploads/2022/02/logo-corner.png" class="logo logo-img rounded-circle">
				<h4 class="site-description">
					<?php bloginfo( 'description' ); // Display the blog description, found in General Settings ?>
				</h4>
					<section class="nameplate">


						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); // Link to the home page ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); // Title it with the blog name ?>" rel="home"><?php bloginfo( 'name' ); // Display the blog name ?></a>
						</h1>

					</section><!-- /brand -->

					<nav id="site-navigation" class="nav clearfix">
							<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => NULL, 'menu_class'=>'menu', 'fallback_cb' => false, 'walker' => new MYTHEME_Menu_Attribute_Walker() )); ?>
					</nav><!-- #site-navigation -->
			</hgroup>

			<div class="clear"></div>
		</header><!-- #masthead .site-header -->
