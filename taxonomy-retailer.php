<?php

    get_header();
    $currentTaxSlug= get_queried_object()->slug;
    $currentTaxName = get_queried_object()->name;
    $currentTaxDescription = get_queried_object()->description;
    $currentTaxId = get_queried_object()->id;
    $currentTermId = get_queried_object()->term_id;

    echo"
        retailer template
    ";

            
get_footer();