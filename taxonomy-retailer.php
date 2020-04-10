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
            wc_get_template_part('content', 'product');
      }
      wp_reset_postdata();
    woocommerce_product_loop_end();
    do_action('woocommerce_after_shop_loop');
  } else {
    do_action('woocommerce_no_products_found');
  }
            
get_footer();