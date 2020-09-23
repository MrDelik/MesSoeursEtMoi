<?php
/*
 * Template Name: PrintOrder
 */

get_header();


?>

<style>
    .orderPage {
        padding-bottom:5rem;
    }
    .orderPage:last-of-type {
        padding-bottom:1rem;
    }
    .orderInfos   {
        display:flex;
        flex-wrap: wrap;
        justify-content: space-between;
        max-width: 1200px;
        margin: auto;
        padding: 0.5rem 0 1.5rem 0;
        border-bottom: 1px solid #bfbfbf;
    }    
    .orderInfos > div  {
        display:flex;
        flex-wrap: wrap;
        justify-content: space-between;
        flex-basis: 250px;
    }
    .clientDetails > div , .orderDetails > div {
        display:flex;
        flex-wrap: wrap;
        justify-content: space-between;
        flex-basis: 100%;
        padding:0.25rem 0;
    }
    .productPrint {
        display:flex;
        flex-wrap: wrap;
        justify-content: space-between;
        max-width: 1200px;
        margin:auto;
        padding:0.25rem 0;
        border-bottom:1px solid #bfbfbf ;  
    }  
    .productPrint > div{
        flex-basis: 100px;
        text-align: right;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
    }    
    .productPrint .productPrintName {
        justify-content: flex-start;
        text-align: left;
        flex-grow: 1;
    }
    
    .productPrint .productColor{
        text-align: left;
        justify-content: flex-start;
        flex-basis: 150px;
    }    
    .productPrint .productSku{
        text-align: left;
        justify-content: flex-start;
        flex-basis: 200px;
    }
    
    .productPrint:last-of-type {
        border-bottom:none;  
    }
    
    .productColorBullet {
        width:30px;
        height:30px;
        border-radius: 100%;
        border:1px solid black;
        margin:0.5rem;
        -webkit-print-color-adjust: exact;
    }
    
    @media print {
        
        .grecaptcha-badge {
            display:none !important;
        }
        
        div{
            page-break-inside: avoid;
        }
        
        body {
            margin: 6mm 6mm 6mm 6mm;
        }
        #header-content , footer , #nasa-init-viewed , #nasa-back-to-top{
            display: none !important;
        }
    }

</style>


<?php
$user = wp_get_current_user();
if (current_user_can( 'edit_posts' )) {
        $postId = $_GET['order'];
    
        $post = get_post($postId);
        $order_id = $post->ID;
        $order = wc_get_order( $order_id );
        $order_id  = $order->get_id(); // Get the order ID
        $parent_id = $order->get_parent_id(); // Get the parent order ID (for subscriptions…)
        $user_id   = $order->get_user_id(); // Get the costumer ID
        $user      = $order->get_user(); // Get the WP_User object
        $clientFirstName = get_user_meta( $user_id, 'billing_first_name', true );
        $clientLastName = get_user_meta( $user_id, 'billing_last_name', true );
        $clientCompagny = get_user_meta( $user_id, 'billing_company', true );
        $clientAddress = get_user_meta( $user_id, 'billing_address_1', true );
        $clientAddress2 = get_user_meta( $user_id, 'billing_address_2', true );
        $clientCity = get_user_meta( $user_id, 'billing_city', true );
        $clientState = get_user_meta( $user_id, 'billing_state', true );
        $clientPostCode = get_user_meta( $user_id, 'billing_postcode', true );
        $clientCountry = get_user_meta( $user_id, 'billing_country', true );

        $order_status  = $order->get_status(); // Get the order status (see the conditional method has_status() below)
        $currency      = $order->get_currency(); // Get the currency used  
        $payment_method = $order->get_payment_method(); // Get the payment method ID
        $payment_title = $order->get_payment_method_title(); // Get the payment method title
        $date_created  = $order->get_date_created(); // Get date created (WC_DateTime object)
        $date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)
        $orderPrice = $order->get_formatted_order_total();
        $billing_country = $order->get_billing_country(); // Customer billing country

        echo "
            <div id='printOrder'>";
            echo "
                    <div class='orderPage'>
                        <div class='orderInfos'>
                        <div class='clientDetails'>
                            <div class='orderClientName'>
                                <div><strong>Nom et Prénom :</strong></div>
                                <div>{$clientFirstName} {$clientLastName}</div>
                            </div>
                            <div class='orderClientCompagny'>
                                <div><strong>Société :</strong></div>
                                <div>{$clientCompagny}</div>
                            </div>
                            <div class='orderClientShippingAddress'>
                                <div><strong>Livraison :</strong></div>
                                <div>{$clientAddress} {$clientAddress2} </div>
                            </div>
                            <div class='orderClientCountry'>
                                <div><strong>Pays :</strong></div>
                                <div>{$clientCity} {$clientPostCode} {$clientCountry} {$clientState}</div>
                            </div>
                        </div>
                        <div class='orderDetails'>
                            <div class='orderId'>
                                <div><strong>Num commande :</strong></div>
                                <div>{$order_id}</div>
                            </div>
                            <div class='orderStatus'>
                                <div><strong>Status :</strong></div>
                                <div>{$order_status}</div>

                            </div>
                            <div class='orderPayement'>
                                <div><strong>Payement :</strong></div>
                                <div>{$payment_title}</div>
                            </div>
                            <div class='orderPrice'>
                                <div><strong>Prix total :</strong></div>
                                <div>{$orderPrice}</div>

                            </div>
                        </div>
                    </div>
                        <div class='productPrint'>
                            <div class='productSku'>
                                <strong>SKU</strong>
                            </div>
                            <div class='productPrintName'>
                                <strong>Nom du produit</strong>
                            </div>
                            <div class='productColor'>
                               <strong>Couleur</strong>
                            </div>
                            <div class='productSize'>
                               <strong>Taille</strong>
                            </div>
                            <div class='productQty'>
                                <strong>Quantité</strong>
                            </div>
                        </div>
                    ";
    
    
                    $orderItemsToSort = $order->get_items();

                    usort($orderItemsToSort , function($item1 , $item2){
                        $item1Name = $item1->get_name();
                        $item2Name = $item2->get_name();
                        if($item1Name == $item2Name){
                            $formatted_meta_data_item1 = $item1->get_formatted_meta_data( '_', true );
                            foreach($formatted_meta_data_item1 as $productMeta_item1) {
                                if($productMeta_item1->key == 'pa_size') {
                                    $matches = '';
                                    $product_size_item1 = $productMeta_item1->value;
                                    $product_size_item1 = preg_replace('/-en$/', '', $product_size_item1 );
                                }
                            } 
                            $formatted_meta_data_item2 = $item2->get_formatted_meta_data( '_', true );
                            foreach($formatted_meta_data_item2 as $productMeta_item2) {
                                if($productMeta_item2->key == 'pa_size') {
                                    $matches = '';
                                    $product_size_item2 = $productMeta_item2->value;
                                    $product_size_item2 = preg_replace('/-en$/', '', $product_size_item2 );
                                }
                            }
                            $item1Size = $product_size_item1;
                            $item2Size = $product_size_item2;
                            
                            return ($item1Size < $item2Size) ? -1 : 1;
                        }
                        return ($item1Name < $item2Name) ? -1 : 1;
                    });
    
                // Iterating through each WC_Order_Item_Product objects
                foreach ($orderItemsToSort as $item_key => $item ) {
                    ## Using WC_Order_Item methods ##
                    // Item ID is directly accessible from the $item_key in the foreach loop or
                    $item_id = $item->get_id();
                    ## Using WC_Order_Item_Product methods ##
                    $product      = $item->get_product(); // Get the WC_Product object
                    $product_id   = $item->get_product_id(); // the Product id
                    $variation_id = $item->get_variation_id(); // the Variation id
                    $item_type    = $item->get_type(); // Type of the order item ("line_item")
                    $item_name    = $item->get_name(); // Name of the product
                    $quantity     = $item->get_quantity();  
                    $tax_class    = $item->get_tax_class();
                    $line_subtotal     = $item->get_subtotal(); // Line subtotal (non discounted)
                    $line_subtotal_tax = $item->get_subtotal_tax(); // Line subtotal tax (non discounted)
                    $line_total        = $item->get_total(); // Line total (discounted)
                    $line_total_tax    = $item->get_total_tax(); // Line total tax (discounted)
                    ## Access Order Items data properties (in an array of values) ##
                    $item_data    = $item->get_data();
                    $item_meta_data = $item->get_meta_data();
                    $formatted_meta_data = $item->get_formatted_meta_data( '_', true );
                    $product_name = $item_data['name'];
                    $product_id   = $item_data['product_id'];
                    $variation_id = $item_data['variation_id'];
                    $quantity     = $item_data['quantity'];
                    $tax_class    = $item_data['tax_class'];
                    $line_subtotal     = $item_data['subtotal'];
                    $line_subtotal_tax = $item_data['subtotal_tax'];
                    $line_total        = $item_data['total'];
                    $line_total_tax    = $item_data['total_tax'];

                    
                
                    
                    // Get data from The WC_product object using methods (examples)
                    $product        = $item->get_product(); // Get the WC_Product object
                    $product_type   = $product->get_type();
                    $product_sku    = $product->get_sku();
                    $product_price  = $product->get_price();
                    $stock_quantity = $product->get_stock_quantity();

                   
                    
                    foreach($formatted_meta_data as $productMeta) {
                        if($productMeta->key == 'pa_color') {
                            $product_color = $productMeta->value;
                            $product_color = preg_replace('/-en$/', '', $product_color );
                            $myColorTerm = get_term_by('name' , $product_color , 'pa_color');
                            $myColorHex = get_term_meta($myColorTerm->term_id , 'nasa_color' , true);
                        }
                        if($productMeta->key == 'pa_size') {
                            $matches = '';
                            $product_size = $productMeta->value;
                            $product_size = preg_replace('/-en$/', '', $product_size );
                        }
                    }
                    
                    
                                        
                    $exploded_product_sku = explode("-", $product_sku);
                    $exploded_product_name = explode("-", $product_name);
                    echo "
                        <div class='productPrint'>
                            <div class='productSku'>
                                {$exploded_product_sku[0]}
                            </div>
                            <div class='productPrintName'>
                                {$exploded_product_name[0]}
                            </div>
                            <div class='productColor'>
                                <div class='productColorBullet' style='background:{$myColorHex} !important'></div>
                                {$product_color}
                            </div>
                            <div class='productSize'>
                                {$product_size}
                            </div>
                            <div class='productQty'>
                                <strong>{$quantity}</strong>
                            </div>
                        </div>
                    ";
            }
            echo "</div>";
        echo "</div>";
}
get_footer();
?>