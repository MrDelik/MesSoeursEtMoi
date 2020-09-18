<?php 
$topbar_left = !isset($topbar_left) ? '' : $topbar_left;
$class_topbar = !isset($class_topbar) ? '' : $class_topbar;
$class_topbar .= trim($topbar_left) != '' ? '' : ' hide-for-mobile';
$user = wp_get_current_user();
?>
<div class="nasa-topbar-wrap<?php echo esc_attr($class_topbar); ?>">
    <div id="top-bar" class="top-bar">
        <?php if (!$mobile) : ?>
            <!-- Desktop | Responsive Top-bar -->
            <div class="row">
                <div class="large-12 columns">
                    <div class="left-text left rtl-right">
                        <div class="inner-block">
							<?php 
                            if( is_user_logged_in() && $user->roles[0] == 'retailer'  || current_user_can('administrator') ) {
                                echo $topbar_left;
                            }
                            else {
                                echo "
                                    <style>
                                    .espacePro {
                                        display:flex;
                                        align-items:center;
                                    }
                                    
                                    /* HIDE REGISTER CREATE NEW ACCOUNT BUTN */
                                    .nasa-switch-form {
                                        display:none !important;
                                    }
                                    
                                    /* SLIDER SHOP BTN */
                                    #slider-12-slide-32-layer-5 {
                                        display:none !important;
                                    }
                                    #slider-10-slide-26-layer-5 {
                                      display:none !important;
                                    }
                                    /* HIDE FOR NOW ELEMENT */
                                    footer .hideForNow {
                                        display:none !important;
                                    }
                                    /* HIDE BTN SHOP NOW*/
                                    #slider-10-slide-26-layer-5 {
                                        display:none !important;
                                    }
                                    /* HIDE ACCOUNT MENU */
                                    .nasa-menus-account {
                                        display:none !important;
                                    }
                                    /* ICON TOP MENU */
                                    .nasa-right-main-header {
                                     display:none !important;
                                    }
                                    /* ARTICLE RECENTLY VIEWED */
                                    #nasa-init-viewed {
                                    display:none !important;
                                    }
                                    /* ICON CARTE BANCAIRE FOOTER*/
                                    .ccIcon {
                                        display:none !important;
                                    }
                                    </style>
                                ";
                            }
                            ?>
						</div>
                    </div>
                    <div class="right-text nasa-hide-for-mobile right rtl-left">
                        <div class="topbar-menu-container">
                            <?php do_action('wcml_currency_switcher', array('format' => '%name% (%symbol%)'));?>
                            <?php do_action('nasa_support_multi_languages'); ?>
                            <?php elessi_get_menu('topbar-menu', 'nasa-topbar-menu', 1); ?>
                            <?php echo elessi_tiny_account(true); ?>
                        </div>
                    </div>
                    <?php
                        if( is_user_logged_in() && $user->roles[0] == 'retailer'  || current_user_can('administrator') ) {
                        }
                        else {
                            if(isset($_GET["lang"])) {
                                if($_GET["lang"] == 'en') {
                                     $loginLink = "<strong><a href='https://www.messoeursetmoi.be/my-account/?lang=en'>Login PRO</a></strong>";
                                }
                            } 
                            else {
                                 $loginLink = "<strong><a href='https://www.messoeursetmoi.be/mon-compte'>Login PRO</a></strong>";
                            }
                            echo "
                            <div class='espacePro'>
                                <div class='inner-block'>
                                    {$loginLink}
                                </div>
                            ";
                        }
                    ?>
                </div>
            </div>
        <?php else : ?>
            <!-- Mobile Top-bar -->
            <div class="topbar-mobile-text">
	            <?php if( $user->roles[0] == 'retailer'  || current_user_can('administrator') ): ?>
                <?php echo $topbar_left; ?>
	            <?php endif; ?>
            </div>
            <div id='noFlag' class="topbar-menu-container hidden-tag">
                <?php do_action('nasa_support_multi_languages'); ?>
                <?php elessi_get_menu('topbar-menu', 'nasa-topbar-menu', 1); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (!$mobile) : ?>
        <div class="nasa-hide-for-mobile">
            <a class="nasa-icon-toggle" href="javascript:void(0);">
                <i class="nasa-topbar-up pe-7s-angle-up"></i>
                <i class="nasa-topbar-down pe-7s-angle-down"></i>
            </a>
        </div>
    <?php endif; ?>
</div>
