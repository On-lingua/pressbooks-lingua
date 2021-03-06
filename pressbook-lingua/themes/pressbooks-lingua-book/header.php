<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ --> 
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js">
      </script>
    <![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
	if ( is_front_page() ) {
		echo pb_get_seo_meta_elements();
		echo pb_get_microdata_elements();
		print_book_microdata_meta_tags(); //print microdata of the book
	} else {
		echo pb_get_microdata_elements();
		print_chapter_microdata_meta_tags(); //print microdata of the chapter
	}
?>
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" />
<title><?php
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'pressbooks' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); //wordpress header ?>

</head>
<body <?php body_class(); if(wp_title('', false) != '') { print ' id="' . str_replace(' ', '', strtolower(wp_title('', false))) . '"'; } ?>>
<?php $social_media = get_option( 'pressbooks_theme_options_web' );
if ( 1 === @$social_media['social_media'] || !isset( $social_media['social_media'] ) ) { ?>
	<!-- Faccebook share js sdk -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; //connect to facebook
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, "script", "facebook-jssdk"));</script>
<?php } ?>

<?php get_template_part( 'content', 'accessibility-toolbar' ); ?>
<?php if (is_front_page()):?>
 
 	<!-- home page wrap -->
 	<span itemscope itemtype="http://schema.org/Book" itemref="about alternativeHeadline author copyrightHolder copyrightYear datePublished description editor 
	      image inLanguage keywords publisher">
	<div class="book-info-container">

<?php else: ?>  	 
	<span itemscope itemtype="http://schema.org/WebPage" itemref="about copyrightHolder copyrightYear inLanguage publisher">

	<div class="nav-container">
		<nav>
 			<!-- Book Title -->
	    	<h1 class="book-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
    		<!-- Left nav-menu -->
		    <div class="sub-nav-left">
				<!-- Logo -->
				<!-- Dynamic link to home page. If you want to use a static url comment till "End dynamic link" --> 
				<?php 
					$url = get_site_url();
					$pieces = parse_url($url);
					$hostname = $pieces['host'];
					preg_match ("/\.([^\/]+)/", $hostname, $domain_only);
					$host =  'http://'.$domain_only[1]; 
				?>
				<h2 class="pb-lingua-logo"><a target="_blank" href="<?php echo $host; ?>"></a></h2>
			<!-- End dynamic link -->

			<!-- Custom link for static Home Page
				<h2 class="pb-lingua-logo"><a target="_blank" href="---INSERT LINK---"></a></h2>
			-->

		    </div> <!-- end .sub-nav-left -->
		    
		    <div class="sub-nav-right">

		    	<!-- Gets the value of Exercises and Activities link value and, if not null, shows them as a button in the header  -->
		    	<?php 
		    		$pm_CR = Pressbooks_Metadata_Chapter_Resources::get_instance();
	                $meta = $pm_CR->get_current_metadata_flat();
	                foreach ( $meta as $key=>$elt ) {
		                if($elt->get_name()==='Exercises'){
                        	$ex_link=$elt->get_value();
			                $pos = strpos($ex_link, 'http://');
			                if($pos===false){                 
			                    $ex_link='http://'.$ex_link;
			                }
	                    }
	                    if($elt->get_name()==='Activities'){
	                    	$act_link = $elt->get_value();
	                    	$pos = strpos($act_link, 'http://');
	                    	if($pos===false){
	                    		$act_link = 'http://'.$act_link;
	                    	}
	                    }        
	                }
   				
			    	if($ex_link != null){	//if the link has content?>
		    			<!-- Exercises button -->
				    	<div id="exercise_h" class="exercise">
				    		<a target="_blank" id="ex_h_a" class="level" href='<?php echo $ex_link; ?>'><?php _e('Exercises', 'pressbooks');?></a>
				    	</div>
			    	<?php  }
			    				
			    	if($act_link != null){	//if the link has content?>	
		    			<!-- Activities button -->
				    	<div id="activity_h" class="activity">
				    		<a target="_blank" id="act_h_a" class="level" href='<?php echo $act_link; ?>'><?php _e('Activities', 'pressbooks');?></a>
				    	</div>		
		    	<?php  }	?>	

			    <?php if ( @array_filter( get_option( 'pressbooks_ecommerce_links' ) ) ) : ?>
				    <!-- Download button -->
				    <div id="download-h" class="buy-h">
						<a  target="_blank" id="dwn-h-a" href="<?php echo get_option('home'); ?>/buy" class="button-red"><?php _e('Download', 'pressbooks'); ?></a> <!--  -->
					</div>
				<?php endif; ?>	
				
				<?php get_template_part( 'content', 'social-header' ); ?> 
			
			</div> <!-- end .sub-nav-right -->
		</nav>    		    
	</div> <!-- end .nav-container -->

	<div class="wrapper"><!-- for sitting footer at the bottom of the page -->	    
			<div id="wrap">	    
				<div id="content">

<?php endif; ?>	