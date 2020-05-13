<?php

/**
 * Class WC_Retailer_Order_Email
 * Sent email when a retailer order is validated
 */
class WC_Retailer_Order_Email extends WC_Email {
	public function __construct(){
		// set ID, this simply needs to be a unique name
		$this->id = 'wc_retailer_order';

		// this is the title in WooCommerce Email settings
		$this->title = __('Commande retailer', 'elessi-theme');

		// this is the description in WooCommerce email settings
		$this->description = __('Email de notification quand une commande retailer est validée', 'elessi-theme');

		// these are the default heading and subject lines that can be overridden using the settings
		$this->heading = __('Commande retailer', 'elessi-theme');
		$this->subject = __('Commande retailer', 'elessi-theme');

		// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = 'emails/admin-new-retailer-order.php';
		$this->template_plain = 'emails/plain/admin-new-retailer-order.php';

		// Trigger on new paid orders
		add_action( 'send_messoeursetmoi_retailer_order_validated_notification',  [$this, 'trigger'], 10, 2 );

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();

		// this sets the recipient to the settings defined below in init_form_fields()
		$this->recipient = $this->get_option( 'recipient' );

		// if none was entered, just use the WP admin email as a fallback
		if ( ! $this->recipient )
			$this->recipient = get_option( 'admin_email' );
	}

	/**
	 * Trigger the email to send
	 * The function is not called "send" because the WC_Email class already use the send function and we do not want to override this method
	 * @param $order_id
	 * @param string $recipients
	 */
	public function trigger( int $order_id, string $recipients ) {
		// bail if no order ID is present
		if ( ! $order_id )
			return;

		// setup order object
		$this->object = get_post( $order_id );

		// replace variables in the subject/headings
		$this->placeholders = [
			'{order_date}' => date_i18n( wc_date_format(), strtotime( $this->object->post_date ) ),
			'{order_number}' => $this->object->ID
		];

		$this->recipient = $recipients;

		if ( ! $this->is_enabled() || ! $this->get_recipient() )
			return;

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * Initialise Settings Form Fields - these are generic email options most will use.
	 */
	public function init_form_fields() {
		/* translators: %s: list of placeholders */
		$placeholder_text  = sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>' . esc_html( implode( '</code>, <code>', array_keys( $this->placeholders ) ) ) . '</code>' );
		$this->form_fields = array(
			'enabled'            => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this email notification', 'woocommerce' ),
				'default' => 'yes',
			),
			'subject'            => array(
				'title'       => __( 'Subject', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_subject(),
				'default'     => '',
			),
			'heading'            => array(
				'title'       => __( 'Email heading', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_heading(),
				'default'     => '',
			),
			'additional_content' => array(
				'title'       => __( 'Additional content', 'woocommerce' ),
				'description' => __( 'Text to appear below the main email content.', 'woocommerce' ) . ' ' . $placeholder_text,
				'css'         => 'width:400px; height: 75px;',
				'placeholder' => __( 'N/A', 'woocommerce' ),
				'type'        => 'textarea',
				'default'     => $this->get_default_additional_content(),
				'desc_tip'    => true,
			),
			'email_type'         => array(
				'title'       => __( 'Email type', 'woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'     => 'html',
				'class'       => 'email_type wc-enhanced-select',
				'options'     => $this->get_email_type_options(),
				'desc_tip'    => true,
			),
		);
	}

	/**
	 * Get content html.
	 *
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html(
			$this->template_html,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => true,
				'plain_text'         => false,
				'email'              => $this,
			)
		);
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html(
			$this->template_plain,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => true,
				'plain_text'         => true,
				'email'              => $this,
			)
		);
	}
}