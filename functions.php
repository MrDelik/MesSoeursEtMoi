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
function theme_enqueue_styles_and_scripts() {
    wp_enqueue_style('elessi-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('elessi-child-style', get_stylesheet_uri());

    if(is_tax('retailer')){
	    wp_enqueue_script('elessi-retailer-custom-page-js', get_stylesheet_directory_uri() . '/assets/js/retailerPage.js', [], true , true);
	    wp_enqueue_script('elessi-sweetalert2js', get_stylesheet_directory_uri() . '/assets/js/sweetalert2.all.min.js', [], true , true);
	    wp_enqueue_style('elessi-sweetalert2css', get_stylesheet_directory_uri() . '/assets/css/sweetalert2.min.css', [], true);
	}
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles_and_scripts', 998);

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
		'register_meta_box_cb' => 'registerRetailerOrderMetaBoxes',
		'menu_position' => 56,
		'map_meta_cap' => true,
		'capabilities' => [
			'create_posts' => false
		]
	];
	register_post_type('retailer_order', $postTypeArgs);
}
add_action( 'init', 'retailer', 0 );

/**
 * register all the meta boxes here for the retailer orders
 * @param WP_Post $post
 */
function registerRetailerOrderMetaBoxes( WP_Post $post ){
	wp_enqueue_style(
		'retailer-order-style',
		get_stylesheet_directory_uri().'/assets/css/admin-retailer-order.css',
		[],
		true
	);

	add_meta_box(
		'retailer_order_total',
		__('Total', 'woocommerce'),
		'messoeursetmoi_render_order_total',
		null,
		'side',
		'default',
		[$post]
	);

	add_meta_box(
		'retailer_order_addresses',
		__('Addresses', 'woocommerce'),
		'messoeursetmoi_render_order_address',
		null,
		'advanced',
		'default',
		[$post]
	);

	add_meta_box(
		'retailer_order_products',
		__('Order products', 'woocommerce'),
		'messoeursetmoi_render_order_products',
		null,
		'advanced',
		'high',
		[$post]
	);
}

/**
 * Render the order total meta box content
 * @param WP_Post $post
 */
function messoeursetmoi_render_order_total( WP_Post $post ){
	$orderInfos = get_post_meta( $post->ID, '_order', true );
	$currency = '€';

	$total = 0;
	foreach( $orderInfos as $prods ){
		foreach( $prods as $prod ){
			$total += (float)$prod['qty'] * (float)$prod['price'];
		}
	}

	echo '
		<div class="total-container">'.$total.$currency.'</div>
	';
}

/**
 * Render the addresses related to the oder in the meta box
 * @param WP_Post $post
 */
function messoeursetmoi_render_order_address( WP_Post $order ){
	global $woocommerce;

	$billingAddress = get_post_meta($order->ID, '_billing_address', true);
	$shippingAddress = get_post_meta($order->ID, '_shipping_address', true);

	echo '
		<div class="addresses-container">
			<div class="address">
				<h2>'.__('Billing address', 'woocommerce').'</h2>
				<address>
					'.$woocommerce->countries->get_formatted_address($billingAddress).'
				</address>
				' . (!empty($billingAddress['phone']) ? '<div class="phoneNbr">'.$billingAddress['phone'].'</div>' : '') . '
				' . (!empty($billingAddress['email']) ? '<div class="phoneNbr">'.$billingAddress['email'].'</div>' : '') . '
			</div>
			<div class="address">
				<h2>'.__('Shipping address', 'woocommerce').'</h2>
				<address>
					'.$woocommerce->countries->get_formatted_address($shippingAddress).'
				</address>
				' . (!empty($shippingAddress['phone']) ? '<div class="phoneNbr">'.$shippingAddress['phone'].'</div>' : '') . '
				' . (!empty($shippingAddress['email']) ? '<div class="phoneNbr">'.$shippingAddress['email'].'</div>' : '') . '
			</div>
		</div>
	';
}

/**
 * Render the meta box content
 * @param WP_Post $post
 */
function messoeursetmoi_render_order_products( WP_Post $post){
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'Retailer_Order_Product_Table.php';

	$orderInfos = get_post_meta($post->ID, '_order', true);

	$products = wc_get_products([
		'include' => array_keys($orderInfos)
	]);

	$productsArr = [];
	foreach( $products as $product ){
		$prodInfos = $orderInfos[$product->get_id()];

		foreach($prodInfos as $sizeColor => $prod){
			list($size, $color) = explode('-', $sizeColor);

			$productsArr[] = [
				'id' => $product->get_id(),
				'product' => $product->get_name() . ' - ' . $size . ' - ' . $color,
				'quantity' => $prod['qty'],
				'price' => $prod['price'],
				'total' => (float)$prod['qty'] * (float)$prod['price']
			];

		}
	}

	$productsTable = new Retailer_Order_Product_Table();
	$productsTable->items = $productsArr;
	$productsTable->prepare_items();
	$productsTable->display();
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

/**
 * Save the retailer order via ajax
 * Need to verify the nonce for security
 */
function saveRetailerOrder(){
	$nonceChecked = check_ajax_referer('saveRetailerOrder');

	if( $nonceChecked === false ){
		die('Nonce not valid');
	}

	if( !array_key_exists('retailerProducts', $_COOKIE) ){
		die('No retailer order to validate');
	}

	global $woocommerce;
	$userID = get_current_user_id();
	$prefix = $_POST['selectedBillingAddress'];

	$billingAddress = [
		'first_name' => get_user_meta($userID, $prefix.'_first_name', true),
		'last_name' => get_user_meta($userID, $prefix.'_last_name', true),
		'company' => get_user_meta($userID, $prefix.'_company', true),
		'address_1' => get_user_meta($userID, $prefix.'_address_1', true),
		'address_2' => get_user_meta($userID, $prefix.'_address_2', true),
		'city' => get_user_meta($userID, $prefix.'_city', true),
		'postcode' => get_user_meta($userID, $prefix.'_postcode', true),
		'country' => get_user_meta($userID, $prefix.'_country', true),
		'state' => get_user_meta($userID, $prefix.'_state', true),
		'phone' => get_user_meta($userID, $prefix.'_phone', true),
		'email' => get_user_meta($userID, $prefix.'_email', true)
	];
	$shippingAddress = [
		'first_name' => $woocommerce->customer->get_billing_first_name(),
		'last_name' => $woocommerce->customer->get_billing_last_name(),
		'company' => $woocommerce->customer->get_billing_company(),
		'address_1' => $woocommerce->customer->get_billing_address_1(),
		'address_2' => $woocommerce->customer->get_billing_address_2(),
		'city' => $woocommerce->customer->get_billing_city(),
		'postcode' => $woocommerce->customer->get_billing_postcode(),
		'country' => $woocommerce->customer->get_billing_country(),
		'state' => $woocommerce->customer->get_billing_state(),
		'phone' => $woocommerce->customer->get_billing_phone(),
		'email' => $woocommerce->customer->get_billing_email()
	];

	/* decode the order to store it in meta */
	$order = json_decode(stripslashes($_COOKIE['retailerProducts']), true);
	$currentDate = new DateTime();

	$order_id = wp_insert_post([
		'post_type' => 'retailer_order',
		'post_author' => $userID,
		'post_title' => 'Retailer order at '.$currentDate->format('d/m/Y').' at '.$currentDate->format('H:i:s'),
		'meta_input' => [
			'_order' => $order,
			'_billing_address' => $billingAddress,
			'_shipping_address' => $shippingAddress
		]
	]);

	if( is_wp_error($order_id) ){
		$response = [
			'result' => 'error',
			'message' => $order_id->get_error_message()
		];
	}
	else{
		$response = [
			'result' => 'success',
			'message' => __('Commande créée avec succès', 'elessi-theme')
		];

		$emails = [get_bloginfo('admin_email')];
		if( !empty($_POST['getCopy']) ){
			$currentUser = wp_get_current_user();
			$emails[] = $currentUser->user_email;
		}

		do_action('send_messoeursetmoi_retailer_order_validated', $order_id, implode(',', $emails));
	}

	/* return the reponse as json */
	wp_send_json( $response );
}
add_action('wp_ajax_save_retailer_order', 'saveRetailerOrder');

/**
 *  Add a custom email to the list of emails WooCommerce should load
 *
 * @since 0.1
 * @param array $email_classes available email classes
 * @return array filtered available email classes
 */
function add_expedited_order_woocommerce_email( $email_classes ) {

	// include our custom email class
	require( 'includes/class-wc-retailer-order-email.php' );

	// add the email class to the list of email classes that WooCommerce loads
	$email_classes['WC_Expedited_Order_Email'] = new WC_Retailer_Order_Email();

	return $email_classes;

}
add_filter( 'woocommerce_email_classes', 'add_expedited_order_woocommerce_email' );

function elessi_register_retailer_order_validated_action( $actions ) {

	$actions[] = 'send_messoeursetmoi_retailer_order_validated';

	return $actions;
}
add_filter( 'woocommerce_email_actions', 'elessi_register_retailer_order_validated_action');
