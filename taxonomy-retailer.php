<?php


get_header();
$currentTaxSlug        = get_queried_object()->slug;
$currentTaxName        = get_queried_object()->name;
$currentTaxDescription = get_queried_object()->description;
$currentTaxId          = get_queried_object()->id;
$currentTermId         = get_queried_object()->term_id;

if ( ! function_exists( 'wc_get_products' ) ) {
	return;
}

$ordering          = WC()->query->get_catalog_ordering_args();
$products_per_page = 99;

$featured_products = wc_get_products( array( 'status' => 'publish', 'limit' => - 1, 'return' => 'ids' ) );

if ( $featured_products ) {
	$productsSaved = [];
	if( array_key_exists('retailerProducts', $_COOKIE) ){
		$productsSaved = json_decode(stripslashes($_COOKIE['retailerProducts']), true);
	}

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

			<div id='retailer-noPointerEvent' class="product-info-wrap info" style='pointer-events:none;'>
				<?php do_action( 'woocommerce_shop_loop_item_title', $cat_info ); ?>
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
						<span>Prix Retailer : </span><span class='retailer-price-container'>50</span> <span class='priceCurrency'>€</span>
					</div>
					<ul class="retailer-product-selected">
						<li class="product-size">
							<span>
								<?=__('Size', 'woocommerce')?> :
							</span>
							<span class="selected-size">

							</span>
						</li>
						<li class="product-color">
							<span>
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
											<span class="product-color bullet-color" style="background-color:<?=$sizeColor[0]?>;"></span>
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
	<?php
	wp_reset_postdata();
	woocommerce_product_loop_end();
	do_action( 'woocommerce_after_shop_loop' );
} else {
	do_action( 'woocommerce_no_products_found' );
}

get_footer();