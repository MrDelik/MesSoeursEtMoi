<?php
if( !current_user_can('administrator') || !is_user_logged_in() || get_user_meta(get_current_user_id(), 'isRetailer', true) == 'false'){
	wp_redirect( get_permalink( get_page_by_title('shop') ) );
	exit;
}

/**
 * Reemove the choice color and sizeee button
 * To place them to some other place
 */
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

if ( ! function_exists( 'wc_get_products' ) ) {
	return;
}

get_header();
$ordering          = WC()->query->get_catalog_ordering_args();
$products_per_page = 99;

$featured_products = wc_get_products( array( 'status' => 'publish', 'limit' => - 1, 'return' => 'ids' ) );

if ( $featured_products ) {
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

	do_action( 'woocommerce_before_shop_loop' );
	woocommerce_product_loop_start();
	foreach ( $featured_products as $featured_product ) {
		$post_object = get_post( $featured_product );
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

		<div <?php wc_product_class( '', $product ); echo $attributes; ?> data-product-id="<?=get_the_ID()?>">

			<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

			<div class="product-img-wrap">
				<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
			</div>

<!--			<div id='retailer-noPointerEvent' class="product-info-wrap info" style='pointer-events:none;'>-->
			<div class="product-info-wrap info">
				<?php do_action( 'woocommerce_shop_loop_item_title', $cat_info ); ?>
				<?php Nasa_WC_Attr_UX::getInstance()->product_content_variations_color_label() ?>
				<?php do_action( 'woocommerce_after_shop_loop_item_title', $description_info ); ?>
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

				<div class='retailer-product-command' style='padding:30px;display: flex; flex-wrap: wrap;'>
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
						<input name='quantity' type='number' class="quantity" style='width:100px; margin:0;'>
					</div>
					<button class="save-retailer" type="button" disabled>
						<?=__('Ajouter au panier', 'woocommerce')?>
					</button>
					<div class='retailer-product-recap' style='display:flex;flex-wrap:wrap;width:100%;'>
						<div class='retailer-product-detail' style='width:100%;margin-top:1rem;border-top:1px solid grey;'>
							Product details :
						</div>
						<?php if( array_key_exists(get_the_ID(), $productsSaved) ): ?>
							<?php foreach($productsSaved[get_the_ID()] as $sizeColor => $retailerProduct): ?>
							<?php $sizeColor = explode('-', $sizeColor); ?>
								<div id="<?=$sizeColor[0]?>-<?=$sizeColor[1]?>-<?=get_the_id()?>" class="productDetailsRow" style="display:flex;justify-content:space-between;align-items;center;width:100%;">
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

		<?php
		echo ( ! isset( $wrapper ) || $wrapper == 'li' ) ? '</li>' : '';
	}
	?>
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
	wp_reset_postdata();
	woocommerce_product_loop_end();
	do_action( 'woocommerce_after_shop_loop' );
} else {
	do_action( 'woocommerce_no_products_found' );
}

get_footer();