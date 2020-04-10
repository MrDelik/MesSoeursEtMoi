<?php
/**
 * Template Name: wbWidth
 * The Template for displaying all single posts.
 *
 * @package nasatheme
 */

if (isset($nasa_opt['nasa_in_mobile']) && $nasa_opt['nasa_in_mobile']) :
    $attr .= ' nasa-blog-in-mobile';
endif;

get_header();
?>

<div class="wbWidth">


    <div class="row">
        <div id="content" class="">
            <div class="page-inner">
                <?php
                while (have_posts()) : the_post();
                   
                
                
                    /**
                     * @package nasatheme
                     */
                    global $nasa_opt;

                    $nasa_main_thumb = (!isset($nasa_opt['main_single_post_image']) || $nasa_opt['main_single_post_image']) && has_post_thumbnail() ? true : false;

                    $nasa_parallax = isset($nasa_opt['blog_parallax']) && $nasa_opt['blog_parallax'] ? true : false;

                    $categories = get_the_category_list(esc_html__(', ', 'elessi-theme'));
                    $tags = get_the_tag_list();
                    $shares = shortcode_exists('nasa_share') ? do_shortcode('[nasa_share el_class="text-right mobile-text-left rtl-mobile-text-right rtl-text-left"]') : '';

                    do_action('nasa_before_single_post');
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if ($nasa_main_thumb) : ?>
                            <div class="entry-image margin-bottom-40">
                                <?php if ($nasa_parallax) : ?>
                                    <div class="parallax_img" style="overflow:hidden">
                                        <div class="parallax_img_inner" data-velocity="0.15">
                                            <?php the_post_thumbnail(); ?>
                                            <div class="image-overlay"></div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <?php the_post_thumbnail(); ?>
                                    <div class="image-overlay"></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
<!--                        <header class="entry-header text-center">
                            <?php if ($categories) :
                                echo '<div class="nasa-meta-categories">' . $categories . '</div>';
                            endif; ?>
                            <h1 class="entry-title nasa-title-single-post"><?php the_title(); ?></h1>
                            <div class="entry-meta">
                                <?php elessi_posted_on(); ?>
                            </div>
                        </header>-->

                        <div class="entry-content">
                            <?php
                            the_content();
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'elessi-theme'),
                                'after' => '</div>',
                            ));
                            ?>
                        </div>
                    </article>

                    <?php
                endwhile;
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
