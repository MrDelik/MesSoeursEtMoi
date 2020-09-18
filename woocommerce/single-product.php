<?php
/**
 * 
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
defined('ABSPATH') or exit;

get_header('shop'); ?>

<div class="product-page">
    <?php 
    do_action('woocommerce_before_main_content');
    while (have_posts()) :
        the_post();
        wc_get_template_part('content', 'single-product');
    endwhile;
    do_action('woocommerce_after_main_content');
    ?>
</div>

<script>
window.addEventListener("DOMContentLoaded", (event) => {
    
    var colorBullets = document.querySelectorAll('.nasa-product-content-color-wrap-child');
    colorBullets.forEach(function(e){
        let colors = e.querySelectorAll('a');
        let colorNum = '';
        colors.forEach(function(color){
            color.style.width = "0px";
            color.style.height = "0px";
            color.style.overflow = "hidden";
            color.style.opacity = "0";
            color.style.display = "none";
        });

        if(colors.length < 2){
            colorNum = '<div class="customTxtLabel"><strong>' + colors.length + '</strong>' +  '&nbspcolor</div>';
        }
        else {
            colorNum = '<div class="customTxtLabel"><strong>' + colors.length + '</strong>' +  '&nbspcolors</div>';
        }

        let checkCustomTxtLabel =  e.querySelector('.customTxtLabel');
        if(checkCustomTxtLabel) {
            checkCustomTxtLabel.innerHTML = colorNum;
        }
        else {
            e.innerHTML += colorNum;
        }

    });
});
</script>

<?php
get_footer('shop');
