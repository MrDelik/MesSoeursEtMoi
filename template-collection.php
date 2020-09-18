<?php
/*
 * Template Name: Display Collection
 */

get_header();


 $user = wp_get_current_user();
    if($user->roles && $user->roles[0]) {
       if ($user->roles[0] == 'retailer' || current_user_can( 'edit_posts' )) {
           $args = array(
            'post_type' => 'collection',
            'posts_per_page' => '4',
        );
       }
    }
    else {
           $args = array(
            'post_type' => 'collection',
            'posts_per_page' => '4',
            'tax_query' => array(
                array(
                    'taxonomy' => 'collection_visible',
                    'field'    => 'slug',
                    'terms'    => array( 'retailers'),
                    'operator' => 'NOT IN',
                ),
            ),
        );
    }

if(isset($_GET["lang"])) {
                if($_GET["lang"] == 'en') {
                }}


$query = new WP_Query( $args );
if( $query->have_posts() ) {
    
    if(isset($_GET["lang"])) {
                if($_GET["lang"] == 'en') {
                $seeCollection = "See collection";
            }
        }
            else {
                $seeCollection = "Voir collection";
            }
       
    
    echo "<div id='collectionContainer'>";
    while( $query->have_posts() ) {
        
        $query->the_post();
        $postID = $post->ID;
        $title = get_the_title();
        $thumbnail = get_the_post_thumbnail_url( $postID, 'large' );
        $content = do_blocks( get_the_content());
        $permalink = get_the_permalink();
        
        $getTerms = get_the_terms( $postID, 'collection_visible');
        
        
            echo "
                <div class='collectionArticle' style='background-image:url({$thumbnail})'>
                    <a href='{$permalink}'>
                        <div class='collectionContent'>
                            <h2>{$title}</h2>
                            <div class='button'>$seeCollection</div>
                        </div>
                    </a>
                </div>
            ";
    } 
    echo "</div>";
}
?>
<style>
    #collectionContainer {
        min-height: 38vh;
        display:flex;
        align-items: center;
    }

</style>


<?php

get_footer();
?>