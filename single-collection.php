<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
  
get_header(); ?>
  
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
  
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post();
  
            /*
             * Include the post format-specific template for the content. If you want to
             * use this in a child theme, then include a file called called content-___.php
             * (where ___ is the post format) and that will be used instead.
             */
                $postID = $post->ID;
                $title = get_the_title();
                $thumbnail = get_the_post_thumbnail_url( $postID, 'large' );
                $content = do_blocks( get_the_content());
                $permalink = get_the_permalink();
                    echo "
                        $content
                    ";
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
  
            echo "<div id='collectionNav'>";
            // Previous/next post navigation.
            the_post_navigation( array(
                'next_text' => 
                    '<span class="button nav-post-title">%title</span>',
                'prev_text' => 
                    '<span class="button nav-post-title">%title</span>',
            ) );
            echo "<div>";
  
        // End the loop.
        endwhile;
        ?>
  
        </main><!-- .site-main -->
    </div><!-- .content-area -->
  
<?php get_footer(); ?>