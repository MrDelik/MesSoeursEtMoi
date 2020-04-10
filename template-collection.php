<?php
/*
 * Template Name: Display Collection
 */

get_header();
$args = array(
    'post_type' => 'collection',
/*    'tax_query' => array(
        array(
            'taxonomy' => 'people',
            'field'    => 'slug',
            'terms'    => 'bob',
        ),
    ),*/
);
$query = new WP_Query( $args );
if( $query->have_posts() ) {
    echo "<div id='collectionContainer'>";
    while( $query->have_posts() ) {
        $query->the_post();
        $postID = $post->ID;
        $title = get_the_title();
        $thumbnail = get_the_post_thumbnail_url( $postID, 'large' );
        $content = do_blocks( get_the_content());
        $permalink = get_the_permalink();
            echo "
                <div class='collectionArticle' style='background-image:url({$thumbnail})'>
                    <a href='{$permalink}'>
                        <div class='collectionContent'>
                            <h2>{$title}</h2>
                            <div class='button'>Voir collection</div>
                        </div>
                    </a>
                </div>
            ";
    } 
    echo "</div>";
}


get_footer();
?>