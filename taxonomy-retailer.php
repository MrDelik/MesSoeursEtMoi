<?php


get_header();
    $currentTaxSlug= get_queried_object()->slug;
    $currentTaxName = get_queried_object()->name;
    $currentTaxDescription = get_queried_object()->description;
    $currentTaxId = get_queried_object()->id;
    $currentTermId = get_queried_object()->term_id;

 if(!function_exists('wc_get_products')) {
    return;
  }

  $ordering                = WC()->query->get_catalog_ordering_args();
  $products_per_page       = 99;

  $featured_products       = wc_get_products( array( 'status' => 'publish', 'limit' => -1,'return' => 'ids'  ));

  if($featured_products) {
    do_action('woocommerce_before_shop_loop');
    woocommerce_product_loop_start();
      foreach($featured_products as $featured_product) {
        $post_object = get_post($featured_product);
            setup_postdata($GLOBALS['post'] =& $post_object);
            //wc_get_template_part('content', 'product');
          
            global $product, $nasa_opt;
            if (empty($product) || !$product->is_visible()) :
                return;
            endif;

            $show_in_list = isset($show_in_list) ? $show_in_list : true;
            if (!isset($_delay)) {
                $_delay = 0;
            }

            /**
             * Show Categories info
             */
            $cat_info = isset($cat_info) ? $cat_info : true;

            /**
             * Show Short Description info
             */
            $description_info = isset($description_info) ? $description_info : true;

            $attributes = ' data-wow="fadeInUp" data-wow-duration="1s" data-wow-delay="' . esc_attr($_delay) . 'ms"';

            echo (!isset($wrapper) || $wrapper == 'li') ? '<li class="product-warp-item">' : '';
            ?>

            <div <?php wc_product_class('', $product); echo $attributes; ?>>

                <?php do_action('woocommerce_before_shop_loop_item'); ?>

                <div class="product-img-wrap">
                    <?php do_action('woocommerce_before_shop_loop_item_title'); ?>
                </div>

                <div id='retailer-noPointerEvent' class="product-info-wrap info" style='pointer-events:none;'>
                    <?php do_action('woocommerce_shop_loop_item_title', $cat_info); ?>
                    <?php do_action('woocommerce_after_shop_loop_item_title', $description_info); ?>
                </div>
                <?php
                    global $product, $nasa_opt;

                    if ($show_in_list && (!isset($nasa_opt['nasa_in_mobile']) || !$nasa_opt['nasa_in_mobile'])) {
                        $stock_status = $product->get_stock_status();
                        $stock_label = $stock_status == 'outofstock' ?
                            esc_html__('Out of stock', 'elessi-theme') : esc_html__('In stock', 'elessi-theme');
                        ?>

                        <!-- Clone Group btns for layout List -->
<!--                        <div class="hidden-tag nasa-list-stock-wrap">
                            <p class="nasa-list-stock-status <?php echo esc_attr($stock_status); ?>">
                                <?php echo esc_html__('AVAILABILITY: ', 'elessi-theme') . '<span>' . $stock_label . '</span>'; ?>
                            </p>
                        </div>

                        <div class="group-btn-in-list-wrap hidden-tag">
                            <div class="group-btn-in-list"></div>
                        </div>-->
                        
                        <div class='retailer-product-command' style='padding:30px;display: flex; flex-wrap: wrap;'>
                          <div class='retailer-product-price' style='width:100%;'>
                              <span>Prix Retailer :  </span><span class='priceNumber'>50</span><span class='priceCurrency'> €</span>
                          </div>
                           <div style='display:flex; flex-wrap:no-wrap; align-items:center;justify-content:space-between;width:100%;'>
                                <label for='quantity'>Quantité :  </label>
                                <input name='quantity' id='quantity' type='number' style='width:100px; margin:0;'>
                           </div>
                           <div class='retailer-product-recap' style='display:flex;flex-wrap:wrap;width:100%;'>
                               <div class='retailer-product-detail' style='width:100%;margin-top:1rem;border-top:1px solid grey;'>Product details :</div>
                               <div style=display:flex;justify-content:space-between;align-items:center;width:100%; >
                                   <div><div class='product-ID'>Product ID</div></div><div><span class='product-Qty'>50</span><span class='product-multiplicator-sign'>*</span></div><div><span class='product-egal-sign'>=  </span><span class='product-total-price'>2500</span><span class='priceCurrency'> €</span></div>
                               </div>
                           </div>
                        </div>
                    <?php
                    }
                ?>
                <?php //do_action('woocommerce_after_shop_loop_item', $show_in_list); ?>

            </div>

            <?php
            echo (!isset($wrapper) || $wrapper == 'li') ? '</li>' : '';
      }
      wp_reset_postdata();
    woocommerce_product_loop_end();
    do_action('woocommerce_after_shop_loop');
  } else {
    do_action('woocommerce_no_products_found');
  }
            
get_footer();