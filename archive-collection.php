<?php
var_dump('hello from collection taxonomy');
	wp_redirect( get_permalink( get_page_by_title('collections') ) );
	exit;
