<?php
//
// Recommended way to include parent theme styles.
// (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//  

/**
 * Creating the debug function for dev purpose
 */







//////////////////////////////////* HIDE UNUSED MENU FOR SOME CLARITY *//////////////////////////////////

/*add_action( 'admin_init', function () {
    echo '<pre>' . print_r( $GLOBALS[ 'menu' ], true) . '</pre>';
} );*/

function wpdocs_remove_menus(){
//  remove_menu_page( 'edit.php' );                       //Posts => ARTICLES
//  remove_menu_page( 'edit.php?post_type=nasa_block' );                       //Posts => ARTICLES
  remove_menu_page( 'edit-comments.php' );          //Comments
//  remove_menu_page( 'edit.php?post_type=header' );      // Header builder
//  remove_menu_page( 'edit.php?post_type=footer' );      // Footer builder
  remove_menu_page( 'edit.php?post_type=nasa_pin_pb' ); // Banner Products
  remove_menu_page( 'edit.php?post_type=nasa_pin_mb' ); // Banner Material
  remove_menu_page( 'admin.php?page=wc-admin' );                  //Marketing
  remove_menu_page( 'admin.php?page=yith_woocompare_panel' );                  //Woo commerce compare
  remove_menu_page( 'admin.php?page=vc-general' );                  //Editeur de page Wp bakery
  /*remove_menu_page( 'tools.php' ); */                 //Outils dont suppression données clients
}
add_action( 'admin_menu', 'wpdocs_remove_menus' );



function wpse_custom_menu_order( $menu_ord ) {
    if ( !$menu_ord ) return true;

    return array(
        'index.php', // Dashboard
         'edit.php?post_type=collection', 
         'edit.php?post_type=retailer_order', 
         'edit.php?post_type=product', 
         'separator1', // First separator
         'admin.php?page=wpcf7', 
         'edit.php?post_type=page', 
         'edit.php?post_type=nasa_block', 
         'upload.php', 
         'separator2', // Second separator
        'separator3', // Third separator
        
    );
}
add_filter( 'custom_menu_order', 'wpse_custom_menu_order', 10, 1 );
add_filter( 'menu_order', 'wpse_custom_menu_order', 10, 1 );


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


function wpdocs_selectively_enqueue_admin_script( $hook ) {
    if ( 'post.php' != $hook ) {
        return;
    }
    wp_enqueue_script('setRetailerPriceBackEnd', get_stylesheet_directory_uri() . '/assets/js/setRetailerPriceBackEnd.js', [], true, true);
}
add_action( 'admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script' );

function theme_enqueue_styles_and_scripts() {
    wp_enqueue_style('elessi-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('elessi-child-style', get_stylesheet_uri());

    wp_enqueue_script('shopTilt', get_stylesheet_directory_uri() . '/assets/js/shopTilt.js', [], true, true);
    wp_enqueue_script('customTextLabel', get_stylesheet_directory_uri() . '/assets/js/customTextLabel.js', [], true , true);

    /**
 * Replace the home link URL FOR ELESSI BREADRUMB
 */
        add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
        if(is_tax('product_cat') ||  is_shop() || is_tax('product-category') || is_product()){    
            function woo_custom_breadrumb_home_url() {
               /* return get_home_url() . '/shop/'; */
               return get_home_url() . '/';
            }
        }
    else {
        function woo_custom_breadrumb_home_url() {
           /* return get_home_url() . '/shop/'; */
           return get_home_url() . '/';
        }
    }
    
    
    if(is_tax('product_cat') ||  is_shop() || is_tax('product-category')){
        $currentObj = get_queried_object();
        $currentTermId = '';
        $termObj = '';
        $termSlug = '';
        if(isset(get_queried_object()->term_id)){
            $currentTermId = get_queried_object()->term_id;
        }
        if($currentTermId != '') {
            $termObj = get_term_by('id', $currentTermId, 'product_cat');
            $termSlug = $termObj->slug;
        }
        else {
           
        }
	   /* wp_enqueue_script('elessi-retailer-custom-page-js', get_stylesheet_directory_uri() . '/assets/js/retailerPage.js', [], true , true);*/
	    wp_enqueue_script('elessi-sweetalert2js', get_stylesheet_directory_uri() . '/assets/js/sweetalert2.all.min.js', [], true , true);
	    wp_enqueue_style('elessi-sweetalert2css', get_stylesheet_directory_uri() . '/assets/css/sweetalert2.min.css', [], true);
	    wp_enqueue_style('elessi-retailer-shop', get_stylesheet_directory_uri() . '/assets/css/retailershop.css', [], true);
        }
    else {
         wp_enqueue_script('customTextLabel', get_stylesheet_directory_uri() . '/assets/js/customTextLabel.js', [], true , true);
         wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-1.11.3.min.js', [], true , true);
    }
    }
add_action('wp_enqueue_scripts', 'theme_enqueue_styles_and_scripts', 998);

require __DIR__  . DIRECTORY_SEPARATOR . 'postTypes'  . DIRECTORY_SEPARATOR . 'collectionPostType.php';

function my_phpmailer_configuration( $phpmailer ) {
	$phpmailer->isSMTP();
//	$phpmailer->SMTPDebug = SMTP::DEBUG_SERVER;
//	$phpmailer->SMTPDebug = 2; //Alternative to above constant
	$phpmailer->SMTPSecure = true;
	$phpmailer->SMTPAutoTLS = true;
	$phpmailer->Host = 'mail.infomaniak.com';
	$phpmailer->SMTPAuth = true; // Indispensable pour forcer l'authentification
	$phpmailer->Port = 587;
	$phpmailer->Username = 'webmaster@eyecone.com';
	$phpmailer->Password = 'oXdINYiS_5Vb';

	// Configurations complémentaires
	$phpmailer->SMTPSecure = "tls"; // Sécurisation du serveur SMTP : ssl ou tls
	$phpmailer->From = "info@messoeursetmoi.be"; // Adresse email d'envoi des mails
	$phpmailer->FromName = "Messoeursetmoi"; // Nom affiché lors de l'envoi du mail
}
add_action( 'phpmailer_init', 'my_phpmailer_configuration' );


////////////////////////////////////////////////////////////////////////////////////
// Register Custom Taxonomy
/*function retailer() {
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
add_action( 'init', 'retailer', 0 );*/


////////////////////////////////////////////////////////////////////////////////////
// Register saison taxonomy
function collection_visibility() {
	$labels = array(
		'name'                       => _x( 'Collection visible', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Collection visibles', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Collection visible', 'text_domain' ),
		'all_items'                  => __( 'All Collection visibles', 'text_domain' ),
		'parent_item'                => __( 'Parent Collection visible', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Collection visible :', 'text_domain' ),
		'new_item_name'              => __( 'New Collection visible', 'text_domain' ),
		'add_new_item'               => __( 'Add a new Collection visible', 'text_domain' ),
		'edit_item'                  => __( 'Edit Collection visible', 'text_domain' ),
		'update_item'                => __( 'Update Collection visible', 'text_domain' ),
		'view_item'                  => __( 'See Collection visible', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate item with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Collection visible', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from most used Collection visible', 'text_domain' ),
		'popular_items'              => __( 'Popular Collection visible', 'text_domain' ),
		'search_items'               => __( 'Search Collection visible', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'Not in Collection visible', 'text_domain' ),
		'items_list'                 => __( 'Collection visible list ', 'text_domain' ),
		'items_list_navigation'      => __( 'Collection visible list navigation', 'text_domain' ),
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
	register_taxonomy( 'collection_visible', array( 'collection' ), $args );


}
add_action( 'init', 'collection_visibility', 0 );




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
    $user = wp_get_current_user();
    if($user->roles && $user->roles[0]) {
       if ($user->roles[0] == 'retailer' || current_user_can( 'edit_posts' )) {
                $tax_query = (array) $q->get( 'tax_query' );

            $tax_query[] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array(''),
                    'operator' => 'OR',
                   ) 
            );
        }
    }
    else {
     $tax_query = (array) $q->get( 'tax_query' );
        $tax_query[] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array( 'retailers'),
                'operator' => 'NOT IN',
               ) 
        );
        $q->set( 'tax_query', $tax_query );
    }
}
add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );

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

	if( !empty($order) ){
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
	}
	else{
		$response = [
			'result' => 'error',
			'message' => __('Une commande vide ne peux pas être envoyée', 'elessi-theme')
		];
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

/* Removing the action of the size guide to re add it to show it before the add to cart */
add_action('after_setup_theme', 'changeSizeguiePlace');
function changeSizeguiePlace(){
	remove_action('woocommerce_single_product_summary', 'nasa_size_guide' ,35);
	add_action('woocommerce_single_product_summary', 'nasa_size_guide', 29);
}






function addSizeToName( $productName, $cart_item = null, $cart_item_key = null ){
	$name = explode(',', $productName);
	if( !empty($name[1]) ){
		return $name[0] . ', Size: '.$name[1];
	}
	else{
		return $name[0];
	}
}
add_filter('woocommerce_cart_item_name', 'addSizeToName');


add_filter( 'woocommerce_hide_invisible_variations', '__return_false' );
function wcbv_variation_is_active( $active, $variation ) {
 if(!$variation->is_in_stock()) {
 return false;
 }
 return $active;
}
add_filter( 'woocommerce_variation_is_active', 'wcbv_variation_is_active', 10, 2 );



add_filter( 'woocommerce_ajax_variation_threshold', 'marce_wc_inc_ajax_threshold' );
function marce_wc_inc_ajax_threshold() {
    return 110;
}
/* Disable payement method for custom user role */

/* 
Gateway name     => GATEWAY ID


Visa / master card  =>  stripe
Direct Bank transfer=>  bacs
Cheque payment      =>  cheque
Cash on delivery    =>  cod
Paypal              => paypal ( redirect on paypal)
ppec_paypal         => paypal ( paypal ajax validation)

*/


function bbloomer_paypal_disable_manager( $available_gateways ) {
    $user = wp_get_current_user();
/*echo "<pre>";
        var_dump($available_gateways);
        echo "</pre>";*/
    if($user->roles && $user->roles[0] || current_user_can( 'edit_posts' )) {
       if ($user->roles[0] == 'retailer' || $user->roles[0] == 'administrator') {
        unset( $available_gateways['ppec_paypal'] , $available_gateways['bacs'] , $available_gateways['paypal']);
       }
        else {
        unset( $available_gateways['cheque'] );
        return $available_gateways; 
        }
       return $available_gateways;
    }
    else {
        unset( $available_gateways['cheque'] );
        return $available_gateways; 
    }
}

add_filter( 'woocommerce_available_payment_gateways', 'bbloomer_paypal_disable_manager' );






//Using this code you can activate your plugin from the functions.php
    function activate_plugin_via_php() {
        $active_plugins = get_option( 'active_plugins' );
        array_push($active_plugins, 'woocommerce/woocommerce.php'); /* Here just replace unyson plugin directory and plugin file*/
        update_option( 'active_plugins', $active_plugins );    
    }
add_action( 'init', 'activate_plugin_via_php' );



/* HIDE RELATED PRODUCT */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );



/** "New user" email to john@snow.com instead of admin. */
add_filter( 'wp_new_user_notification_email', 'my_wp_new_user_notification_email', 10, 3 );
function my_wp_new_user_notification_email( $notification, $user, $blogname ) {
    
    $lienEditAdresse = "https://www.messoeursetmoi.be/mon-compte/edit-address/";
    $lienEditAccount = "https://www.messoeursetmoi.be/mon-compte/edit-account/";
    $lienConnection  = "https://messoeursetmoi.be/mon-compte";
    $userPassword    =  $user->user_pass;
    $userMail   =  $user->user_email;
    $user_login = $user->user_login;
    $reset_key = get_password_reset_key( $user );
    $user_id = $user->ID;
    
    $regenPassword = esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) );
    $regenPasswordEN = "https://www.messoeursetmoi.be/my-account/lost-password/?key={$reset_key}&id={$user_id}&lang=en";
    
    $notification['headers'] = "From: Messoeursetmoi <info@messoeursetmoi.be> \n\r Content-Type: text/html; charset=utf-8\r\n MIME-Version: 1.0\r\n";
    $notification['subject'] = sprintf( "[%s] Thanks %s for your registration.", $blogname, $user->user_login );
    $notification['message'] = "
                 <!DOCTYPE html>
                <table border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' style='background-color:#f7f7f7;padding-top:60px;padding-bottom:60px;'>
				<tbody>
                    <tr>
                        <td align='center' valign='top' style='width:600px;'>
                            <p style='margin-top:0'><img src='https://messoeursetmoi.be/wp-content/uploads/2020/04/Logo-mes-soeurs.png' alt='Mes soeurs et moi' style='border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;max-width:100%;margin-left:0;margin-right:0' class='CToWUd'></p>						
                            <table border='0' cellpadding='0' cellspacing='0'  id='m_-8477349831290930821template_container' style='background-color:#ffffff;border:1px solid #dedede;border-radius:3px;width:600px;text-align:center;'>
                                <tbody>
                                    <div style='margin-left:15px;margin-top:15px;margin-right:15px;'>
                                        <h2 style='margin-left:15px;margin-top:15px;margin-right:15px;'>Bonjour {$user->user_login},</h2>
                                        <p style='margin-left:15px;margin-top:15px;margin-right:15px;'>
                                           C’est avec plaisir que nous vous confirmons votre accès à notre plateforme B2B. <br/>Vous trouverez ci-dessous les données liées à votre compte :
                                        </p>
                                        <p style='margin-left:15px;margin-top:15px;margin-right:15px;'>
                                            Identifiant : {$userMail}<br/>
                                            <p>
                                                <a class='link' href='{$regenPassword}'  style='display:inline-block;height:35px;width:250px;background-color:black;color:white;text-decoration:none;text-align:center;'><span style='display:block;margin-left:20px;margin-top:10px;margin-right:20px;margin-bottom:10px;'>&nbsp;&nbsp; Créer votre mot de passe &nbsp;&nbsp;</span>  </a>
                                            </p>
                                            Nous vous souhaitons une belle découverte et restons à votre disposition pour toute question.
                                        </p>
                                        <p>
                                            Belle journée,<br/>
                                            Mes Sœurs &amp; Moi<br/>
                                            <a href='https://www.messoeursetmoi.be/' style='color:back;'>www.messoeuretmoi.be</a>
                                        </p>
                                        <p>
                                            Suivez-nous sur les réseaux sociaux<br/>
                                            <a href='https://www.facebook.com/messoeursetmoi/' style='color:back;'>Facebook</a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href='https://www.instagram.com/messoeursetmoi_official/' style='color:back;'>Instagram</a>
                                        </p>
                                    </div>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                            <br/><br/>
                            <table border='0' cellpadding='0' cellspacing='0'  id='m_-8477349831290930821template_container' style='background-color:#ffffff;border:1px solid #dedede;border-radius:3px;width:600px;text-align:center;'>
                                <tbody>
                                    <div style='margin-left:15px;margin-top:15px;margin-right:15px;'>
                                        <h2 style='margin-left:15px;margin-top:15px;margin-right:15px;'>Dear {$user->user_login},</h2>
                                        <p style='margin-left:15px;margin-top:15px;margin-right:15px;'>
                                           We are pleased to confirm your access to our B2B platform. Please find below the data related to your account :
                                        </p>
                                        <p style='margin-left:15px;margin-top:15px;margin-right:15px;'>
                                            Username : {$userMail}<br/>
                                             <p>
                                                <a class='link' href='{$regenPasswordEN}&lang=en'  style='display:inline-block;height:35px;width:250px;background-color:black;color:white;text-decoration:none;text-align:center;'><span style='display:block;margin-left:20px;margin-top:10px;margin-right:20px;margin-bottom:10px;'>&nbsp;&nbsp; Set your password &nbsp;&nbsp;</span>  </a>
                                            </p>
                                            We wish you a nice discovery and if you have any questions, please do not hesitate to contact us.
                                        </p>
                                        <p>
                                            Kind regards,<br/>
                                            Mes Sœurs &amp; Moi<br/>
                                            <a href='https://www.messoeursetmoi.be/' style='color:back;'>www.messoeuretmoi.be</a>
                                        </p>
                                        <p>
                                            Follow us on social media<br/>
                                            <a href='https://www.facebook.com/messoeursetmoi/' style='color:back;'>Facebook</a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href='https://www.instagram.com/messoeursetmoi_official/' style='color:back;'>Instagram</a>
                                        </p>
                                    </div>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
    ";
    
  return $notification;
}




