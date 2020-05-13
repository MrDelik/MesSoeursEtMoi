<?php
/**
 * Admin new retailer order
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails/HTML
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php
	global $woocommerce;

	$orderAuthor = get_user_by('ID', $order->post_author);
	$orderDate = new DateTime($order->post_date);

	$productsJSON = get_post_meta($order->ID, '_order', true);
	$products = wc_get_products([
		'include' => array_keys($productsJSON),
		'ignore_sticky_posts' => true
	]);
	$currency = '€';

	$billingAddress = get_post_meta($order->ID, '_billing_address', true);
	$shippingAddress = get_post_meta($order->ID, '_shipping_address', true);

	$total = 0;
	$itemsTotal = 0;
?>

<p>
	<?=__('Order placed by:', 'woocommerce')?> <?=ucfirst($orderAuthor->first_name)?> <?=ucfirst($orderAuthor->last_name)?>(<?=$orderAuthor->display_name?>)
</p>

<h2>
	<?=__('Order', 'woocommerce')?> &quot;<?=$order->ID?>&quot;
	<?=$orderDate->format('d/m/Y à h:i')?>
</h2>

<!-- Order details in a table -->
	<div style="margin-bottom: 40px;">
		<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
			<thead>
				<tr>
					<th class="td" scope="col" style="text-align:left>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
					<th class="td" scope="col" style="text-align:left>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
					<th class="td" scope="col" style="text-align:left>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($products as $product): ?>
				<?php $infos = $productsJSON[$product->get_id()]; ?>
				<?php foreach($infos as $colorSize => $info): ?>
					<?php $total += ((float)$info['price']  *  (int)$info['qty']); ?>
					<?php list($color, $size) = explode('-',  $colorSize); ?>
					<tr class="order_item">
						<td class="td" style="text-align:left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
							<?=$product->get_name()?> - <?=$size?>  - <?=$color?>
						</td>
						<td class="td" style="text-align:center; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
							<?=$info['qty']?>
						</td>
						<td class="td" style="text-align:center; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
							<?=$info['price']?><?=$currency?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:left; border-top-width: 4px;">
						<?=__('Total', 'woocommerce')?>
					</th>
					<td class="td" style="text-align:center; border-top-width: 4px;">
						<?=$total?><?=$currency?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
<?php
$address = $woocommerce->countries->get_formatted_address($billingAddress);
$shipping = $woocommerce->countries->get_formatted_address($shippingAddress);
?>
<table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<h2><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>

			<address class="address">
				<?=$address?>
			</address>
		</td>
		<td style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
			<h2><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>

			<address class="address">
				<?php echo wp_kses_post( $shipping ); ?>
			</address>
		</td>
	</tr>
</table>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
