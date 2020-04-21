<?php
//
// Recommended way to include parent theme styles.
// (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//  

/**
 * Creating the debug function for dev purpose
 */
if( !function_exists('debug') ){
	function debug( ...$vars ){
		echo '<pre>';
		foreach( $vars as $var ){
			var_dump( $var );
		}
		echo '</pre>';

		$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT , 1);
		echo "<br>";
		die('end debug in '.$backtrace[0]['file'].' at line '.$backtrace[0]['line']);
	}
}

/**
 *	Register all scripts and styles for this child theme
 */
function theme_enqueue_styles() {
    wp_enqueue_style('elessi-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('elessi-child-style', get_stylesheet_uri());

    if(is_tax('retailer')){
	    wp_enqueue_script('elessi-retailer-custom-page-js', get_stylesheet_directory_uri() . '/assets/js/retailerPage.js', [], true , true);
	}
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles', 998);

require __DIR__  . DIRECTORY_SEPARATOR . 'postTypes'  . DIRECTORY_SEPARATOR . 'collectionPostType.php';

/* adding informations to the user page */
function messoeursetmoi_extra_user_fields($user){
	if( current_user_can('edit_user') ):
		$isretailer = get_user_meta($user->ID, 'isRetailer', true);
		$disabledStyle = 'opacity:0.6;cursor:not-allowed;';
		?>
		<h3><?=__('Retailer', 'blank')?></h3>
		<p><?=__('If the user is a retailer or not')?></p>
		<table class="form-table" id="fieldset-retailer">
			<tbody>
			<tr>
				<th>
					<label for="isRetailerCheckbox" style="<?=get_current_user_id() == $user->ID ? $disabledStyle : ''?>">
						Is he a retailer ?
					</label>
				</th>
				<td>
					<input type="checkbox" id="isRetailerCheckbox" name="isRetailer" value="true" <?=!empty($isretailer) ? 'checked' : ''?> <?=get_current_user_id() == $user->ID ? 'disabled' : ''?> style="<?=get_current_user_id() == $user->ID ? $disabledStyle : ''?>">
					<label for="isRetailerCheckbox" style="<?=get_current_user_id() == $user->ID ? $disabledStyle : ''?>">
						<?=__('Checked if the user is a retailer', 'blank')?>
					</label>
				</td>
			</tr>
			</tbody>
		</table>
	<?php
	endif;
}
add_action( 'show_user_profile', 'messoeursetmoi_extra_user_fields' );
add_action( 'edit_user_profile', 'messoeursetmoi_extra_user_fields' );


function messoeursetmoi_save_extra_user_fields($user_id){
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	update_user_meta( $user_id, 'isRetailer', array_key_exists('isRetailer', $_POST) ? $_POST['isRetailer'] : false );
}
add_action( 'personal_options_update', 'messoeursetmoi_save_extra_user_fields' );
add_action( 'edit_user_profile_update', 'messoeursetmoi_save_extra_user_fields' );

function my_phpmailer_configuration( $phpmailer ) {
	$phpmailer->isSMTP();
//	$phpmailer->SMTPDebug = SMTP::DEBUG_SERVER;
//	$phpmailer->SMTPDebug = 2; //Alternative to above constant
//	$phpmailer->SMTPSecure = false;
//	$phpmailer->SMTPAutoTLS = false;
	$phpmailer->Host = 'mail.infomaniak.com';
	$phpmailer->SMTPAuth = true; // Indispensable pour forcer l'authentification
	$phpmailer->Port = 587;
	$phpmailer->Username = 'webmaster@eyecone.com';
	$phpmailer->Password = 'mN5F_KVnlYhf';

	// Configurations complémentaires
//	$phpmailer->SMTPSecure = "none"; // Sécurisation du serveur SMTP : ssl ou tls
	$phpmailer->From = "webmaster@eyecone.com"; // Adresse email d'envoi des mails
	$phpmailer->FromName = "Eyecone"; // Nom affiché lors de l'envoi du mail
}
add_action( 'phpmailer_init', 'my_phpmailer_configuration' );


////////////////////////////////////////////////////////////////////////////////////
// Register Custom Taxonomy
function retailer() {
	$labels = array(
		'name'                       => _x( 'Retailer', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Retailers', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Retailer', 'text_domain' ),
		'all_items'                  => __( 'All Retailers', 'text_domain' ),
		'parent_item'                => __( 'Parent Retailer', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Retailer :', 'text_domain' ),
		'new_item_name'              => __( 'New Retailer', 'text_domain' ),
		'add_new_item'               => __( 'Add a new Retailer', 'text_domain' ),
		'edit_item'                  => __( 'Edit Retailer', 'text_domain' ),
		'update_item'                => __( 'Update Retailer', 'text_domain' ),
		'view_item'                  => __( 'See Retailer', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate item with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Retailer', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from most used Retailer', 'text_domain' ),
		'popular_items'              => __( 'Popular Retailer', 'text_domain' ),
		'search_items'               => __( 'Search Retailer', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'Not in Retailer', 'text_domain' ),
		'items_list'                 => __( 'Retailer list ', 'text_domain' ),
		'items_list_navigation'      => __( 'Retailer list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'retailer', array( 'product' ), $args );

	$postTypeLabels = [
		'name'                       => _x( 'Retailer order', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Retailer orders', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Retailer order', 'text_domain' ),
		'all_items'                  => __( 'All Retailer orders', 'text_domain' ),
		'parent_item'                => __( 'Parent retailer order', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent retailer order :', 'text_domain' ),
		'new_item_name'              => __( 'New retailer order', 'text_domain' ),
		'add_new_item'               => __( 'Add a new retailer order', 'text_domain' ),
		'edit_item'                  => __( 'Edit Retailer order', 'text_domain' ),
		'update_item'                => __( 'Update Retailer order', 'text_domain' ),
		'view_item'                  => __( 'See Retailer order', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate item with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove retailer order', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from most used retailer order', 'text_domain' ),
		'popular_items'              => __( 'Popular Retailer order', 'text_domain' ),
		'search_items'               => __( 'Search Retailer order', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'Not in Retailer order', 'text_domain' ),
		'items_list'                 => __( 'Retailer orders list ', 'text_domain' ),
		'items_list_navigation'      => __( 'Retailer orders list navigation', 'text_domain' ),
	];
	$postTypeArgs = [
		'labels' => $postTypeLabels,
		'description' => 'Order done by retailers',
		'public' => false,
		'show_ui' => true,
		'supports' => ['title', 'author'],
		'register_meta_box_cb' => 'registerRetailerOrderMetaBoxes'
	];
	register_post_type('retailer_order', $postTypeArgs);
}
add_action( 'init', 'retailer', 0 );

/**
 * register all the meta boxes here for the retailer orders
 */
function registerRetailerOrderMetaBoxes(){

}


/**
 * Exclude products from a particular category on the shop page
 */
function custom_pre_get_posts_query( $q ) {

    $tax_query = (array) $q->get( 'tax_query' );

    $tax_query[] = array(
        array(
            'taxonomy' => 'retailer',
            'field'    => 'slug',
            'terms'    => array( 'retailers-only'),
            'operator' => 'NOT IN',
           ) 
    );


    $q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );

/**
 * Add the retailer price to the simple product
 */
function misha_adv_product_options(){
	echo '<div class="options_group">';

	woocommerce_wp_text_input( array(
		'id' => 'retailer_price',
		'label' => 'Retailer price (&euro;):',
		'value' => get_post_meta( get_the_ID(), 'retailer_price', true ),
		'wrapper_class' => 'form-field-wide',
		'type' => 'number',
		'custom_attributes' => ['step' => 'any', 'min' => '0']
	) );

	echo '</div>';
}
add_action( 'woocommerce_product_options_pricing', 'misha_adv_product_options');

function misha_save_fields( $ord_id ){
	update_post_meta( $ord_id, 'retailer_price', $_POST[ 'retailer_price' ] );
}
add_action( 'woocommerce_process_product_meta', 'misha_save_fields', 10, 2 );

/**
 * Add the retailer price product variation
 * @param $loop
 * @param $variation_data
 * @param $variation
 */
function bbloomer_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
	woocommerce_wp_text_input( array(
			'id' => 'retailer_price[' . $loop . ']',
			'class' => 'short',
			'label' => __( 'Retailer price', 'woocommerce' ),
			'value' => get_post_meta( $variation->ID, 'retailer_price', true ),
			'type' => 'number'
		)
	);
}
add_action( 'woocommerce_variation_options_pricing', 'bbloomer_add_custom_field_to_variations', 10, 3 );

/**
 * Save the retailer price meta
 * @param $variation_id
 * @param $i
 */
function bbloomer_save_custom_field_variations( $variation_id, $i ) {
	$custom_field = $_POST['retailer_price'][$i];
	if ( isset( $custom_field ) ) update_post_meta( $variation_id, 'retailer_price', esc_attr( $custom_field ) );
}
add_action( 'woocommerce_save_product_variation', 'bbloomer_save_custom_field_variations', 10, 2 );

/**
 * Save the retailer price for the variation type
 * @param $variations
 * @return mixed
 */
function bbloomer_add_custom_field_variation_data( $variations ) {
	$variations['retailer_price'] = get_post_meta( $variations[ 'variation_id' ], 'retailer_price', true );
	return $variations;
}
add_filter( 'woocommerce_available_variation', 'bbloomer_add_custom_field_variation_data' );
