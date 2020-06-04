<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.4.0
 */
if (!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

/**
 * Reemove the choice color and sizeee button
 * To place them to some other place
 */


if( !current_user_can('administrator') || !is_user_logged_in() || get_user_meta(get_current_user_id(), 'isRetailer', true) == 'false'){
    wp_redirect( get_permalink( get_page_by_title('shop') ) );
    exit;
}

remove_action('woocommerce_before_shop_loop_item_title', array(Nasa_WC_Attr_UX::getInstance(), 'product_content_variations_color_label'), 99);

$addressesPrefix = get_user_meta(get_current_user_id(), 'wc_address_book', true);
$addressesPrefix = is_array($addressesPrefix) ? $addressesPrefix : [$addressesPrefix];

$BillingAddresses = [];
$userID = get_current_user_id();
foreach($addressesPrefix as $prefix){
	$BillingAddresses[$prefix] = [
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
}

global $woocommerce;
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

global $nasa_opt, $wp_query;
/**
 * Override cat side-bar layout
 */
$rootCatId = elessi_get_root_term_id();
if ($rootCatId) {
    $sidebar_style = get_term_meta($rootCatId, 'cat_sidebar_layout', true);
    if ($sidebar_style != '') {
        $nasa_opt['category_sidebar'] = $sidebar_style;
    }
}

$typeView = !isset($nasa_opt['products_type_view']) ?
    'grid' : ($nasa_opt['products_type_view'] == 'list' ? 'list' : 'grid');

$nasa_opt['products_per_row'] = isset($nasa_opt['products_per_row']) && (int) $nasa_opt['products_per_row'] ?
    (int) $nasa_opt['products_per_row'] : 4;
$nasa_opt['products_per_row'] = $nasa_opt['products_per_row'] > 6 || $nasa_opt['products_per_row'] < 2 ? 4 : $nasa_opt['products_per_row'];

$nasa_change_view = !isset($nasa_opt['enable_change_view']) || $nasa_opt['enable_change_view'] ? true : false;
$grid_cookie_name = 'archive_grid_view';
$siteurl = get_option('siteurl');
$grid_cookie_name .= $siteurl ? '_' . md5($siteurl) : '';
$typeShow = $typeView == 'grid' ? ($typeView . '-' . ((int) $nasa_opt['products_per_row'])) : 'list';
$typeShow = $nasa_change_view && isset($_COOKIE[$grid_cookie_name]) ? $_COOKIE[$grid_cookie_name] : $typeShow;

$nasa_cat_obj = $wp_query->get_queried_object();
$nasa_term_id = 0;
$nasa_type_page = 'product_cat';
$nasa_href_page = '';
if (isset($nasa_cat_obj->term_id) && isset($nasa_cat_obj->taxonomy)) {
    $nasa_term_id = (int) $nasa_cat_obj->term_id;
    $nasa_type_page = $nasa_cat_obj->taxonomy;
    $nasa_href_page = esc_url(get_term_link($nasa_cat_obj, $nasa_type_page));
}

$nasa_ajax_product = true;
if ((isset($nasa_opt['disable_ajax_product']) && $nasa_opt['disable_ajax_product']) || get_option('woocommerce_shop_page_display', '') != '' || get_option('woocommerce_category_archive_display', '') != '') :
    $nasa_ajax_product = false;
endif;
defined('NASA_AJAX_SHOP') or define('NASA_AJAX_SHOP', $nasa_ajax_product);

$nasa_sidebar = isset($nasa_opt['category_sidebar']) ? $nasa_opt['category_sidebar'] : 'left-classic';
$nasa_has_get_sidebar = false;

if (isset($_REQUEST['sidebar']) && defined('NASATHEME_DEMO') && NASATHEME_DEMO):
    $nasa_has_get_sidebar = true;
endif;

$hasSidebar = true;
$topSidebar = false;
$topSidebar2 = false;
$topbarWrap_class = 'row filters-container nasa-filter-wrap';
$attr = 'nasa-products-page-wrap ';
switch ($nasa_sidebar):
    case 'right':
    case 'left':
        $attr .= 'large-12 columns has-sidebar';
        break;
    
    case 'right-classic':
        $attr .= 'large-9 columns left has-sidebar';
        break;
    
    case 'no':
        $hasSidebar = false;
        $attr .= 'large-12 columns no-sidebar';
        break;
    
    case 'top':
        $hasSidebar = false;
        $topSidebar = true;
        $topbarWrap_class .= ' top-bar-wrap-type-1';
        $attr .= 'large-12 columns no-sidebar top-sidebar';
        break;
    
    case 'top-2':
        $hasSidebar = false;
        $topSidebar2 = true;
        $topbarWrap_class .= ' top-bar-wrap-type-2';
        $attr .= 'large-12 columns no-sidebar top-sidebar-2';
        break;
    
    case 'left-classic':
    default :
        $attr .= 'large-9 columns right has-sidebar';
        break;
endswitch;

$nasa_recom_pos = isset($nasa_opt['recommend_product_position']) ? $nasa_opt['recommend_product_position'] : 'bot';

$layout_style = '';
if (isset($nasa_opt['products_layout_style']) && $nasa_opt['products_layout_style'] == 'masonry-isotope') :
    $layout_style = ' nasa-products-masonry-isotope';
    $layout_style .= isset($nasa_opt['products_masonry_mode']) ? ' nasa-mode-' . $nasa_opt['products_masonry_mode'] : '';
endif;

get_header('shop');
?>
<div class="row fullwidth category-page">
    <?php do_action('woocommerce_before_main_content'); ?>
    
    <div class="nasa_shop_description-wrap">
        <?php
        /**
         * Hook: woocommerce_archive_description.
         *
         * @hooked woocommerce_taxonomy_archive_description - 10
         * @hooked woocommerce_product_archive_description - 10
         */
        do_action('woocommerce_archive_description');
        ?>
    </div>
    
    <?php
    /**
     * Hook: nasa_before_archive_products.
     */
    do_action('nasa_before_archive_products');
    ?>
    
    <div class="large-12 columns">
        <div class="<?php echo esc_attr($topbarWrap_class); ?>">
            <?php
            /**
             * Top Side bar Type 1
             */
            if ($topSidebar) :
                $topSidebar_wrap = $nasa_change_view ? 'large-10 ' : 'large-12 ';

                if (!isset($nasa_opt['showing_info_top']) || $nasa_opt['showing_info_top']) :
                    echo '<div class="showing_info_top hidden-tag">';
                    do_action('nasa_shop_category_count');
                    echo '</div>';
                endif;
                ?>

                <div class="<?php echo esc_attr($topSidebar_wrap); ?>columns nasa-topbar-filter-wrap">
                    <div class="row">
                        <div class="large-10 medium-10 columns nasa-filter-action">
                            <div class="nasa-labels-filter-top">
                                <input name="nasa-labels-filter-text" type="hidden" value="<?php echo (!isset($nasa_opt['top_bar_archive_label']) || $nasa_opt['top_bar_archive_label'] == 'Filter by:') ? esc_attr__('Filter by:', 'elessi-theme') : esc_attr($nasa_opt['top_bar_archive_label']); ?>" />
                                <input name="nasa-widget-show-more-text" type="hidden" value="<?php echo esc_attr__('More +', 'elessi-theme'); ?>" />
                                <input name="nasa-widget-show-less-text" type="hidden" value="<?php echo esc_attr__('Less -', 'elessi-theme'); ?>" />
                                <input name="nasa-limit-widgets-show-more" type="hidden" value="<?php echo (!isset($nasa_opt['limit_widgets_show_more']) || (int) $nasa_opt['limit_widgets_show_more'] < 0) ? '2' : (int) $nasa_opt['limit_widgets_show_more']; ?>" />
                                <a class="toggle-topbar-shop-mobile hidden-tag" href="javascript:void(0);">
                                    <i class="pe-7s-filter"></i><?php echo esc_attr__('&nbsp;Filters', 'elessi-theme'); ?>
                                </a>
                                <span class="nasa-labels-filter-accordion hidden-tag"></span>
                            </div>
                        </div>
                        
                        <div class="large-2 medium-2 columns nasa-sort-by-action right rtl-left">
                            <ul class="sort-bar nasa-float-none margin-top-0">
                                <li class="sort-bar-text nasa-order-label hidden-tag">
                                    <?php esc_html_e('Sort by', 'elessi-theme'); ?>
                                </li>
                                <li class="nasa-filter-order filter-order">
                                    <?php do_action('woocommerce_before_shop_loop'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <?php if ($nasa_change_view) : ?>
                    <div class="large-2 columns nasa-topbar-change-view-wrap">
                        <?php /* Change view ICONS */
                        $type_sidebar = (!isset($nasa_opt['top_bar_cat_pos']) || $nasa_opt['top_bar_cat_pos'] == 'left-bar') ? 'top-push-cat' : 'no';
                        do_action('nasa_change_view', $nasa_change_view, $typeShow, $type_sidebar); ?>
                    </div>
                <?php endif; ?>

                <?php
                /* Sidebar TOP */
                do_action('nasa_top_sidebar_shop');
                
            /**
             * Top Side bar type 2
             */
            elseif ($topSidebar2) :
                ?>
                <div class="large-12 columns">
                    <div class="row">
                        <div class="large-4 medium-6 small-6 columns nasa-toggle-top-bar rtl-right">
                            <a class="nasa-toggle-top-bar-click" href="javascript:void(0);">
                                <i class="pe-7s-angle-down"></i> <?php esc_html_e('Filters', 'elessi-theme'); ?>
                            </a>
                        </div>
                        
                        <div class="large-4 columns nasa-topbar-change-view-wrap hide-for-medium hide-for-small text-center rtl-right">
                            <?php if ($nasa_change_view) : ?>
                                <?php /* Change view ICONS */
                                do_action('nasa_change_view', $nasa_change_view, $typeShow); ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="large-4 medium-6 small-6 columns nasa-sort-by-action nasa-clear-none text-right rtl-text-left">
                            <ul class="sort-bar nasa-float-none margin-top-0">
                                <li class="sort-bar-text nasa-order-label hidden-tag">
                                    <?php esc_html_e('Sort by: ', 'elessi-theme'); ?>
                                </li>
                                <li class="nasa-filter-order filter-order">
                                    <?php do_action('woocommerce_before_shop_loop'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="large-12 columns nasa-top-bar-2-content hidden-tag">
                    <?php do_action('nasa_top_sidebar_shop', '2'); ?>
                </div>
            
            <?php
            /**
             * TOGGLE Side bar in side (Off-Canvas)
             */
            elseif ($hasSidebar && in_array($nasa_sidebar, array('left', 'right'))) : ?>
                <div class="large-4 medium-6 small-6 columns nasa-toggle-layout-side-sidebar">
                    <div class="li-toggle-sidebar">
                        <a class="toggle-sidebar-shop" href="javascript:void(0);">
                            <i class="pe-7s-filter"></i><?php esc_html_e('Filters', 'elessi-theme'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="large-4 columns hide-for-medium hide-for-small nasa-change-view-layout-side-sidebar nasa-min-height">
                    <?php /* Change view ICONS */
                    do_action('nasa_change_view', $nasa_change_view, $typeShow); ?>
                </div>
            
                <div class="large-4 medium-6 small-6 columns nasa-sort-bar-layout-side-sidebar nasa-clear-none nasa-min-height">
                    <ul class="sort-bar">
                        <li class="sort-bar-text nasa-order-label hidden-tag">
                            <?php esc_html_e('Sort by: ', 'elessi-theme'); ?>
                        </li>
                        <li class="nasa-filter-order filter-order">
                            <?php do_action('woocommerce_before_shop_loop'); ?>
                        </li>
                    </ul>
                </div>
            <?php
            
            /**
             * No | left-classic | right-classic side bar
             */
            else : ?>
                <div class="large-4 medium-6 columns hide-for-small">
                    <?php
                        if (!isset($nasa_opt['showing_info_top']) || $nasa_opt['showing_info_top']) :
                            echo '<div class="showing_info_top">';
                            do_action('nasa_shop_category_count');
                            echo '</div>';
                        else :
                            echo '&nbsp;';
                        endif;
                    ?>
                </div>
                
                <div class="large-4 columns hide-for-medium hide-for-small nasa-change-view-layout-side-sidebar nasa-min-height">
                    <?php /* Change view ICONS */
                    do_action('nasa_change_view', $nasa_change_view, $typeShow, $nasa_sidebar);
                    ?>
                </div>
            
                <div class="large-4 medium-6 small-12 columns nasa-clear-none nasa-sort-bar-layout-side-sidebar">
                    <ul class="sort-bar">
                        <?php if ($hasSidebar): ?>
                            <li class="li-toggle-sidebar">
                                <a class="toggle-sidebar" href="javascript:void(0);">
                                    <i class="pe-7s-filter"></i> <?php esc_html_e('Filters', 'elessi-theme'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="sort-bar-text nasa-order-label hidden-tag">
                            <?php esc_html_e('Sort by: ', 'elessi-theme'); ?>
                        </li>
                        <li class="nasa-filter-order filter-order">
                            <?php do_action('woocommerce_before_shop_loop'); ?>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="nasa-archive-product-content">
        <?php if ($topSidebar && (!isset($nasa_opt['top_bar_cat_pos']) || $nasa_opt['top_bar_cat_pos'] == 'left-bar')) :
            $attr .= ' nasa-has-push-cat';
            $class_cat_top = 'nasa-push-cat-filter';
            if (isset ($_REQUEST['push_cat_filter']) && $_REQUEST['push_cat_filter']) :
                $class_cat_top .= ' nasa-push-cat-show';
                $attr .= ' nasa-push-cat-show';
            endif;
            ?>
            <div class="<?php echo esc_attr($class_cat_top); ?>"></div>
        <?php endif; ?>
        
        <div class="<?php echo esc_attr($attr); ?>">

            <?php if (!isset($nasa_opt['disable_ajax_product_progress_bar']) || $nasa_opt['disable_ajax_product_progress_bar'] != 1) : ?>
                <div class="nasa-progress-bar-load-shop"><div class="nasa-progress-per"></div></div>
            <?php endif; ?>

            <?php if ($nasa_recom_pos !== 'bot' && defined('NASA_CORE_ACTIVED') && NASA_CORE_ACTIVED) : ?>
                <span id="position-nasa-recommend-product" class="hidden-tag"></span>
                <?php do_action('nasa_recommend_product', $nasa_term_id); ?>
            <?php endif; ?>

            <div class="nasa-archive-product-warp<?php echo esc_attr($layout_style); ?>">
                <?php
                if (woocommerce_product_loop()) :
                    // Content products in shop
                    if (NASA_WOO_ACTIVED && version_compare(WC()->version, '3.3.0', "<")) :
                        do_action('nasa_archive_get_sub_categories');
                    endif;
                    
                    woocommerce_product_loop_start();
                /* MODIF BY EYECONE */
                    if ($wp_query->post_count) :
                        $_delay = $count = 0;
                        $_delay_item = (isset($nasa_opt['delay_overlay']) && (int) $nasa_opt['delay_overlay']) ? (int) $nasa_opt['delay_overlay'] : 100;

                        while ($wp_query->have_posts()) :
                            $wp_query->the_post();
                            $postID = $post->ID;
                            $postExcerpt =  $post->post_excerpt;

/******************************************************* ******************************/
/******************************************************* ******************************/
    $ordering          = WC()->query->get_catalog_ordering_args();
    $products_per_page = 99;

    $featured_products = wc_get_products( array( 'status' => 'publish', 'limit' => - 1, 'return' => 'ids' ) );
    $productsSaved = [];
	$showClass = '';
	if( array_key_exists('retailerProducts', $_COOKIE) ){
		$productsSaved = json_decode(stripslashes($_COOKIE['retailerProducts']), true);
		$showClass = 'show';
	}
	echo '<div class="save-retailer-order-container '.$showClass.'">
			<button type="button" class="button btn-primary open-retailer-recap-modal modal-toggler" data-target="retailer-recap-modal">
				<i class="fas fa-save"></i> '.__('Valider la commande', 'elessi-theme').'
			</button>
		</div>';
        /*$post_object = get_post( $featured_product );*/
		setup_postdata( $GLOBALS['post'] =& $post_object );
		//wc_get_template_part('content', 'product');

		global $product, $nasa_opt;
		if ( empty( $product ) || ! $product->is_visible() ) :
			return;
		endif;

		$show_in_list = isset( $show_in_list ) ? $show_in_list : true;
		if ( ! isset( $_delay ) ) {
			$_delay = 0;
		}

		/**
		 * Show Categories info
		 */
		$cat_info = isset( $cat_info ) ? $cat_info : true;

		/**
		 * Show Short Description info
		 */
		$description_info = isset( $description_info ) ? $description_info : true;
		$attributes = ' data-wow="fadeInUp" data-wow-duration="1s" data-wow-delay="' . esc_attr( $_delay ) . 'ms"';

		echo ( ! isset( $wrapper ) || $wrapper == 'li' ) ? '<li class="product-warp-item">' : '';
		?>
                


<div <?php wc_product_class( '', $product ); echo $attributes; ?> data-product-id="<?=$postID?>">

			<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

			<div class="product-img-wrap">
				<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
			</div>

<!--			<div id='retailer-noPointerEvent' class="product-info-wrap info" style='pointer-events:none;'>-->
			<div class="product-info-wrap info">
				<?php do_action( 'woocommerce_shop_loop_item_title', $cat_info ); ?>
				<?php Nasa_WC_Attr_UX::getInstance()->product_content_variations_color_label() ?>
				
				
				<?php 
                    echo '<div class="price-wrap">';
                        woocommerce_template_loop_price();
                    echo '</div>';
                ?>
                <?=$postExcerpt?>
                
			</div>
			<?php
			global $product, $nasa_opt;
			if ( $show_in_list && ( ! isset( $nasa_opt['nasa_in_mobile'] ) || ! $nasa_opt['nasa_in_mobile'] ) ) {
				$stock_status = $product->get_stock_status();
				$stock_label  = $stock_status == 'outofstock' ?
					esc_html__( 'Out of stock', 'elessi-theme' ) : esc_html__( 'In stock', 'elessi-theme' );
				?>

				<!-- Clone Group btns for layout List -->
				<!--                        <div class="hidden-tag nasa-list-stock-wrap">
                            <p class="nasa-list-stock-status <?php echo esc_attr( $stock_status ); ?>">
                                <?php echo esc_html__( 'AVAILABILITY: ', 'elessi-theme' ) . '<span>' . $stock_label . '</span>'; ?>
                            </p>
                        </div>

                        <div class="group-btn-in-list-wrap hidden-tag">
                            <div class="group-btn-in-list"></div>
                        </div>-->

				<div class='retailer-product-command' style='display: flex; flex-wrap: wrap;'>
					<div class='retailer-product-price' style='width:100%;'>
						<span class="retailer-info-title">Prix Retailer : </span>
						<span class='retailer-price-container'>50</span> <span class='priceCurrency'>€</span>
					</div>
					<ul class="retailer-product-selected">
						<li class="product-size">
							<span class="retailer-info-title">
								<?=__('Size', 'woocommerce')?> :
							</span>
							<span class="selected-size">

							</span>
						</li>
						<li class="product-color">
							<span class="retailer-info-title">
								<?=__('Color', 'woocommerce')?> :
							</span>
							<span class="selected-color">

							</span>
						</li>
					</ul>
					<div style='display:flex; flex-wrap:nowrap; align-items:center;justify-content:space-between;width:100%;'>
						<label for='quantity'>Quantité : </label>
						<input name='quantity' type='number' class="quantity" style='width:100px; margin:0;' min="1">
					</div>
					<button class="save-retailer" type="button" disabled>
						<?=__('Ajouter au panier', 'woocommerce')?>
					</button>
					<div class='retailer-product-recap' style='display:flex;flex-wrap:wrap;width:100%;'>
						<div class='retailer-product-detail' style='width:100%;margin-top:1rem;border-top:1px solid grey;'>
							Product details :
						</div>
						
						<?php 
                            if( array_key_exists($postID, $productsSaved) ): ?>
							<?php foreach($productsSaved[$postID] as $sizeColor => $retailerProduct): ?>
							<?php $sizeColor = explode('-', $sizeColor); ?>
								<div id="<?=$sizeColor[0]?>-<?=$sizeColor[1]?>-<?=$postID?>" class="productDetailsRow" style="display:flex;justify-content:space-between;align-items;center;width:100%;">
									<div style="display:flex;justify-content:space-between;align-items:center;width:100%;">
										<div>
											<span class="product-color bullet-color" style="background-color:<?=$retailerProduct['colorCode']?>;"><?=$sizeColor[0]?></span>
											<span class="product-size"><?=$sizeColor[1]?></span>
											<span class="product-Qty"><?=$retailerProduct['qty']?></span>
											<span class="product-multiplicator-sign">*</span>
											<span class="product-unit-price"><?=$retailerProduct['price']?></span>
										</div>
										<div>
											<span class="product-egal-sign"> = </span>
											<span class="product-total-price"><?=(int)$retailerProduct['qty']*(int)$retailerProduct['price']?></span>
											<span class="priceCurrency">&euro;</span>
										</div>
										<button type="button" class="removeRetailerProduct">
											<i class="fas fa-times"></i>
										</button>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				<?php
			}
			?>
			<?php //do_action('woocommerce_after_shop_loop_item', $show_in_list); ?>

		</div>
	<template id="productDetailsTemplate">
		<div class="productDetailsRow" style="display:flex;justify-content:space-between;align-items:center;width:100%;">
			<div style=display:flex;justify-content:space-between;align-items:center;width:100%;>
				<div>
					<span class="product-color bullet-color"></span>
					<span class="product-size"></span>
					<span class="product-Qty"></span>
					<span class="product-multiplicator-sign">*</span>
					<span class="product-unit-price"></span>
				</div>
				<div>
					<span class="product-egal-sign"> &equals; </span>
					<span class="product-total-price"></span>
					<span class="priceCurrency">&euro;</span>
				</div>
				<button type="button" class="removeRetailerProduct">
					<i class="fas fa-times"></i>
				</button>
			</div>
		</div>
	</template>
	<div id="retailer-recap-modal" class="modal-background" style="display:none;">
		<div class="modal-container modal-large <?=is_admin_bar_showing() ? 'modal-with-admin-bar' : ''?>">
			<div class="modal">
				<div class="nasa-loader-in-modal" id="retailerOrderLoader" style="display:none;">
					<div class="nasa-loader"></div>
				</div>
				<button type="button" class="button btn-primary modal-close">
					<i class="fas fa-times"></i>
				</button>
				<div class="modal-header">
					<h3>
						<?=__('Récapitulatif de la commande', 'elessi-theme')?>
					</h3>
				</div>
				<div class="modal-body">
					<div class="steps-container">
						<div class="step step1 step-active">
							<h5>
								<?=__('Selectionnez l\'adresse de livraison', 'elessi-theme')?>
							</h5>
							<div class="step-content">
								<div class="input-container">
									<div class="select-replacement">
										<span class="select-replacement-arrow">
											<i class="fas fa-sort-down"></i>
										</span>
										<select name="retailer_billing_address" id="billingAddressSelect">
											<?php foreach($addressesPrefix as $address): ?>
												<option value="<?=$address?>"><?=$address?></option>
											<?php endforeach; ?>
										</select>
										<ul class="select-replacement-options">
											<?php foreach($addressesPrefix as $address): ?>
												<li class="select-replacement-option" data-value="<?=$address?>">
													<?=$address?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>

								<ul class="addresses_list">
									<?php foreach($BillingAddresses as $addressID => $address): ?>
									<li id="<?=$addressID?>" class="address <?=$addressID == $addressesPrefix[0] ? 'selected-address' : ''?>">
										<?=$woocommerce->countries->get_formatted_address($address)?>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<div class="step step2">
							<h5>
								<?=__('Récapitulatif', 'elessi-theme')?>
							</h5>
							<table class="woocommerce-table woocommerce-table--order-details shop_table order_details table-responsive">
								<thead>
									<tr>
										<th>
											<?=__('Produits', 'elessi-theme')?>
										</th>
										<th class="text-center">
											<?=__('Quantité', 'elessi-theme')?>
										</th>
										<th class="text-center">
											<?=__('Prix', 'elessi-theme')?>
										</th>
										<th class="text-center">
											<?=__('Total', 'elessi-theme')?>
										</th>
									</tr>
								</thead>
								<tbody class="orderRecapContainer">
								</tbody>
								<tfoot>
									<tr>
										<th colspan="3">
											<span class="totalpriceTitle">
												<?=__('Total', 'elessi-theme')?>
											</span>
										</th>
										<td class="text-center" data-title="<?=__('Total', 'elessi-theme')?>">
											<span class="totalPriceRecap" data-currency="€"></span>
										</td>
									</tr>
								</tfoot>
							</table>
							<div class="addressesContainer">
								<div class="billingAddress">
									<h6>
										<?=__('Facturation', 'elessi-theme')?>
									</h6>
									<address class="shipping-address address-container">
										<?=$woocommerce->countries->get_formatted_address($shippingAddress)?>
									</address>
								</div>
								<div class="shippingAddress">
									<h6>
										<?=__('Livraison', 'elessi-theme')?>
									</h6>
									<address class="billing-address address-container"></address>
								</div>
							</div>

							<div class="input-container">
								<input type="checkbox" id="receiveOrderCopy" name="getOrderCopy" value="true">
								<label for="receiveOrderCopy">
									<span class="checkbox">
										<i class="fas fa-check"></i>
									</span>
									<?=__('Recevoir une copie de la commande', 'elessi-theme')?>
								</label>
							</div>
							<template id="recapRowTemplate">
								<tr>
									<td class="productInfos" data-title="<?=__('Produits', 'elessi-theme')?>"></td>
									<td class="productQuantity text-center" data-title="<?=__('Quantité',  'elessi-theme')?>"></td>
									<td class="productPrice text-center" data-title="<?=__('Prix',  'elessi-theme')?>">
										<span class="value-container" data-currency="€"></span>
									</td>
									<td class="productTotal text-center" data-title="<?=__('Total',  'elessi-theme')?>">
										<span class="value-container" data-currency="€"></span>
									</td>
								</tr>
							</template>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="steps-button-container">
						<div class="step step1 step-active">
							<button class="step-switcher" style="margin-left:auto;" type="button" data-target="step2" data-trigger="orderRecap">
								<?=__('Suivant', 'elessi-theme')?>
							</button>
						</div>
						<div class="step step2">
							<button class="button btn-primary step-switcher" type="button" data-target="step1">
								<?=__('Précédent', 'elessi-theme')?>
							</button>
							<button class="button btn-primary save-retailer-order" type="button" data-sec="<?=wp_create_nonce('saveRetailerOrder')?>">
								<i class="fas fa-save"></i>
								<?=__('Sauver la commande', 'elessi-theme')?>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
                
    <?php            
                
                
                
                
                
/******************************************************* ******************************/
/******************************************************* ******************************/
                
                
                
                
                
                
                
                            $_delay += $_delay_item;
                            $count++;
                        endwhile;
                    endif;
                    /* END MODIF BY EYECONE */
                    woocommerce_product_loop_end();
                else :
                    echo '<div class="row"><div class="large-12 columns">';
                    do_action('woocommerce_no_products_found');
                    echo '</div></div>';
                endif;
                ?>
            </div>

            <?php
            /**
             * Hook: woocommerce_after_shop_loop.
             *
             * @hooked woocommerce_pagination - 10
             */
            do_action('woocommerce_after_shop_loop');
            ?>

            <?php if ($nasa_recom_pos == 'bot' && defined('NASA_CORE_ACTIVED') && NASA_CORE_ACTIVED) :?>
                <span id="position-nasa-recommend-product" class="hidden-tag"></span>
                <?php do_action('nasa_recommend_product', $nasa_term_id); ?>
            <?php endif; ?>
        </div>

        <?php /* Sidebar LEFT | RIGHT */
        if ($hasSidebar && !$topSidebar && !$topSidebar2) :
            do_action('nasa_sidebar_shop', $nasa_sidebar);
        endif;
        
        do_action('woocommerce_after_main_content');
        ?>
    </div>
</div>

<?php
if ($nasa_ajax_product) : ?>
    <div class="nasa-has-filter-ajax hidden-tag">
        <div class="current-cat hidden-tag">
            <a data-id="<?php echo (int) $nasa_term_id; ?>" href="<?php echo esc_url($nasa_href_page); ?>" class="nasa-filter-by-cat" id="nasa-hidden-current-cat" data-taxonomy="<?php echo esc_attr($nasa_type_page); ?>" data-sidebar="<?php echo esc_attr($nasa_sidebar); ?>"></a>
        </div>
        <p><?php esc_html_e('No products were found matching your selection.', 'elessi-theme'); ?></p>
        <?php if ($s = get_search_query()): ?>
            <input type="hidden" name="nasa_hasSearch" id="nasa_hasSearch" value="<?php echo esc_attr($s); ?>" />
        <?php endif; ?>
        <?php if ($nasa_has_get_sidebar) : ?>
            <input type="hidden" name="nasa_getSidebar" id="nasa_getSidebar" value="<?php echo esc_attr($nasa_sidebar); ?>" />
        <?php endif; ?>
            
        <?php
        // <!-- Current URL -->
        $slug_nopaging = elessi_nopaging_url();
        echo $slug_nopaging ? '<input type="hidden" name="nasa_current-slug" id="nasa_current-slug" value="' . esc_url($slug_nopaging) . '" />' : '';
        ?>
    </div>
<?php endif;

get_footer('shop');
