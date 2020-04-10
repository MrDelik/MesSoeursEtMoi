<?php
//
// Recommended way to include parent theme styles.
// (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//  
add_action('wp_enqueue_scripts', 'theme_enqueue_styles', 998);

function theme_enqueue_styles() {
    wp_enqueue_style('elessi-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('elessi-child-style', get_stylesheet_uri());
}

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



/*function register_taxonomy_retailer(){
	register_taxonomy(
        'retailer',
		'product',
		[
			'labels' => [
				'name' => 'Retailers',
				'singular_name' => 'Retailer'
			]
		]
	);
}
add_action('init', 'register_taxonomy_retailer');*/


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
}
add_action( 'init', 'retailer', 0 );


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

