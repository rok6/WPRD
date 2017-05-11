<?php get_header(); ?>

<main id="main" role="main">

	<div id="branding">

	<?php component('post', 'list'); ?>

	</div>

	<?php

	// if( !class_exists(SQLite3) ) print_r('no SQLite3');
	//
	// $dir = __DIR__ . '/modules';
	// $iterator = new RecursiveDirectoryIterator($dir);
	// $iterator = new RecursiveIteratorIterator($iterator);
	//
	// _dump($iterator);
	//
	// $dirs = $files = $other = [];
	//
	// foreach ($iterator as $fileinfo) {
	// 	if( $fileinfo->isFile() ) {
	// 		$files[] = $fileinfo->getPathname();
	// 	}
	// 	else {
	// 		$dirs[] = $fileinfo->getPathname();
	// 	}
	// }

	?>

</main><!--#main-->

<?php get_footer();
