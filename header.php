<?php
if( is_front_page() ) {
	get_template_part('parts/header', 'front');
}
else {
	get_template_part('parts/header');
}
