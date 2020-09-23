<?php
/*
 * Template Name: displayOrder
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
        padding:0.5rem 0;
        border-bottom:1px solid #bfbfbf ;  
    }  
    .productPrint > div{
        flex-basis: 14.28%;
        text-align: right;
                display: flex;
    align-items: center;
        flex-wrap: wrap;
    }    
    .productPrint .productPrintName {
        text-align: left;
        flex-grow: 1;
    }
    
    .productPrint .productColor{
        text-align: left;
    }
    
    .productPrint:last-of-type {
        border-bottom:none;  
    }
    @media print {
        div{
            page-break-inside: avoid;
        }
        
        body {
            margin: 6mm 6mm 6mm 6mm;
        }
        #header-content , footer ,#nasa-init-viewed , #nasa-back-to-top {
            display: none !important;
        }
    }

</style>


<?php
 $user = wp_get_current_user();
    if($user->roles && $user->roles[0]) {
       if (current_user_can( 'edit_posts' )) {
           $args = array(
            'post_type' => 'shop_order',
            'posts_per_page' => '-1',
            'post_status' => array('wc-on-hold',  'wc-processing' ,'wc-pending' , 'wc-completed' ,  'wc-cancelled' , 'wc-refunded' ,'wc-failed' )
        );
       }
    }

$query = new WP_Query( $args );
if( $query->have_posts() ) {
    echo "<div id='printOrder'>";
    echo "
    </div>
        <div class='productPrint'>
            <div class='productColor'>
               <strong>Date</strong>
            </div>
            <div class='productSize'>
               <strong>Status</strong>
            </div>
            <div class='productPrintName'>
                <strong>Nom client</strong>
            </div>
            <div class='productColor'>
               <strong>Société</strong>
            </div>
            <div class='productSize'>
               <strong>Pays</strong>
            </div>
            <div class='productSize'>
               <strong>Montant</strong>
            </div>
            <div class='productQty'>
                <strong>Lien</strong>
            </div>
        </div>
    ";
    while( $query->have_posts() ) {
        $query->the_post();
            $postID = $post->ID;
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
            $date_created  = $order->get_date_created()->format ('d-m-Y'); // Get date created (WC_DateTime object)
            $date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)
            $orderPrice = $order->get_formatted_order_total();
            $billing_country = $order->get_billing_country(); // Customer billing country
        
            $homeUrl = get_home_url();
            // Iterating through each WC_Order_Item_Product objects
                        echo "
                    <div class='productPrint'>
                        <div class='productColor'>
                            {$date_created}
                        </div>
                        <div class='productSize'>
                            {$order_status}
                        </div>
                        <div class='productPrintName'>
                            {$clientFirstName} {$clientLastName}
                        </div>
                        <div class='productColor'>
                            {$clientCompagny}
                        </div>
                        <div class='productSize'>
                            {$clientCountry}
                        </div>
                        <div class='productSize'>
                            {$orderPrice}
                        </div>
                        <div class='productQty'>
                            <a href='{$homeUrl}/printOrder?order={$postID}' target='_blank'><button>Voir</button></a>
                        </div>
                    </div>
                ";
            
    } 
        echo "</div>";
    echo "</div>";
}
get_footer();
?>