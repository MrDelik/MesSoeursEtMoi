<?php
/*
 * Template Name: Display Collection
 */

get_header();
$args = array(
    'post_type' => 'post',
    'tax_query' => array(
        array(
            'taxonomy' => 'people',
            'field'    => 'slug',
            'terms'    => 'bob',
        ),
    ),
);
$query = new WP_Query( $args );
if( $my_query->have_posts() ) {
    echo "<div id='collectionContainer'>";
    while( $my_query->have_posts() ) {
        $my_query->the_post();

        $title = get_the_title();
        $thumbnail = get_the_post_thumbnail_url('');
        $content = do_blocks( get_the_content());

            echo "
                <div class='collection' style='background:url({$thumbnail})'>
                    <h2>{$title}</h2>
                </div>
            ";
    } 
    echo "</div>";
}


get_footer();
?>