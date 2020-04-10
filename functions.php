<?php
//
// Recommended way to include parent theme styles.
// (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//  
add_action('wp_enqueue_scripts', 'theme_enqueue_styles', 998);

function theme_enqueue_styles() {
    wp_enqueue_style('elessi-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('elessi-child-style', get_stylesheet_uri());
}

require __DIR__  . DIRECTORY_SEPARATOR . 'postTypes'  . DIRECTORY_SEPARATOR . 'collectionPostType.php';

/* adding informations to the user page */
function messoeursetmoi_extra_user_fields($user){
	if( current_user_can('edit_user') ):
		$isretailer = get_user_meta($user->ID, 'isRetailer', true);
		$disabledStyle = 'opacity:0.6;cursor:not-allowed;';
		?>
		<h3><?=__('Retailer', 'blank')?></h3>
		<p><?=__('If the user is a retailer or not')?></p>
		<table class="form-table" id="fieldset-retailer">
			<tbody>
			<tr>
				<th>
					<label for="isRetailerCheckbox" style="<?=get_current_user_id() == $user->ID ? $disabledStyle : ''?>">
						Is he a retailer ?
					</label>
				</th>
				<td>
					<input type="checkbox" id="isRetailerCheckbox" name="isRetailer" value="true" <?=!empty($isretailer) ? 'checked' : ''?> <?=get_current_user_id() == $user->ID ? 'disabled' : ''?> style="<?=get_current_user_id() == $user->ID ? $disabledStyle : ''?>">
					<label for="isRetailerCheckbox" style="<?=get_current_user_id() == $user->ID ? $disabledStyle : ''?>">
						<?=__('Checked if the user is a retailer', 'blank')?>
					</label>
				</td>
			</tr>
			</tbody>
		</table>
	<?php
	endif;
}
add_action( 'show_user_profile', 'messoeursetmoi_extra_user_fields' );
add_action( 'edit_user_profile', 'messoeursetmoi_extra_user_fields' );


function messoeursetmoi_save_extra_user_fields($user_id){
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	update_user_meta( $user_id, 'isRetailer', array_key_exists('isRetailer', $_POST) ? $_POST['isRetailer'] : false );
}
add_action( 'personal_options_update', 'messoeursetmoi_save_extra_user_fields' );
add_action( 'edit_user_profile_update', 'messoeursetmoi_save_extra_user_fields' );

function my_phpmailer_configuration( $phpmailer ) {
	$phpmailer->isSMTP();
//	$phpmailer->SMTPDebug = SMTP::DEBUG_SERVER;
//	$phpmailer->SMTPDebug = 2; //Alternative to above constant
//	$phpmailer->SMTPSecure = false;
//	$phpmailer->SMTPAutoTLS = false;
	$phpmailer->Host = 'mail.infomaniak.com';
	$phpmailer->SMTPAuth = true; // Indispensable pour forcer l'authentification
	$phpmailer->Port = 587;
	$phpmailer->Username = 'webmaster@eyecone.com';
	$phpmailer->Password = 'mN5F_KVnlYhf';

	// Configurations complémentaires
//	$phpmailer->SMTPSecure = "none"; // Sécurisation du serveur SMTP : ssl ou tls
	$phpmailer->From = "webmaster@eyecone.com"; // Adresse email d'envoi des mails
	$phpmailer->FromName = "Eyecone"; // Nom affiché lors de l'envoi du mail
}
add_action( 'phpmailer_init', 'my_phpmailer_configuration' );

function register_taxonomy_retailer(){
	register_taxonomy(
			'retailer',
		'product',
		[
			'labels' => [
				'name' => 'Retailers',
				'singular_name' => 'Retailer'
			]
		]
	);
}
add_action('init', 'register_taxonomy_retailer');