<?php
/*
Plugin Name: Load more 
Author: Miquel Capó
Version: 1.0
Description: Load more for posts
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// load More images
add_action('wp_ajax_load_posts_by_ajax', 'load_posts_by_ajax_callback');
add_action('wp_ajax_nopriv_load_posts_by_ajax', 'load_posts_by_ajax_callback');

// $myData = array(
//     'ajaxurl' => admin_url( 'admin-ajax.php' ),
// );
// wp_localize_script( "myScript", "alm_localize", $myData );

// AJAX callback
function load_posts_by_ajax_callback() {

    check_ajax_referer('load_more_posts', 'security');
    $paged = $_POST['page'];

    $args = array(
			'post_status' => 'publish',
			'post_type'	=> 'work',
			'order' => 'DESC',
			'posts_per_page'	=> 6,
      		'paged' => $paged,
    );



  $my_posts = new WP_Query( $args );


    if ( $my_posts->have_posts() ) :
        ?>
		<?php while ( $my_posts->have_posts() ) : $my_posts->the_post();
		
		//TEMPLATE  HERE
		/////////////////
		
			if( function_exists('sal_work_template') ){
				sal_work_template();
			}else{
				echo ' no template';
			}
			
		/// END TEMAPLTE
		//////////////////	
		endwhile; ?>

 		<?php wp_reset_postdata();?>



        <?php


	else:

				?>

				<script type="text/javascript">
					(function($) {


					$(".load-text-image").text("No hay más proyectos");
					$(".load-image").removeClass("sal_js-loadmore");


					})( jQuery );
					</script>


				<?php
    endif;

    wp_die();
}




add_shortcode('load-more-script', 'load_more_script');

function load_more_script(){

?>

<script type="text/javascript">
var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
var page_image = 2;



jQuery(function($) {


	$('body').on('click', '.sal_js-loadmore', function() {

		console.log('innnn');

			var scroll = $(document).scrollTop();

					var data_image = {
					'action': 'load_posts_by_ajax',
					'page': page_image,
					'security': '<?php echo wp_create_nonce("load_more_posts"); ?>'
					};

					$.post(ajaxurl, data_image, function(response) {
						console.log(response);
					var child = $('.sal_js-loadmore-container').find('.sal_js-loadmore-item').first();
					child.parent().append(response);

					page_image++;
					});


					$('html, body').animate({scrollTop: scroll}, 800);

			});
  
});
</script>

<?php


}