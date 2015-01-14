<?php
/*
Template Name: Section Beautiful Product
Description: Section with 2 titles, 2 contents and 2 illustrations
---------------------------------------------------------------------
{"type": "title", "name": "Left title", "slug": "left_title"}
{"type": "editor", "name": "Left content", "slug": "left_content"}
{"type": "image", "name": "Left illustration", "slug": "left_illustration"}
{"type": "title", "name": "Right title", "slug": "right_title"}
{"type": "editor", "name": "Right content", "slug": "right_content"}
{"type": "image", "name": "Right illustration", "slug": "right_illustration"}
---------------------------------------------------------------------
*/
?>
<section> 
	<h1>		<?php the_chapter_title( 'left_title' ) ?></h1>
	<article>	<?php the_chapter( 'left_content' ) ?></article>
	<aside>		<?php the_illustration( 'left_illustration' ) ?></aside>
	<article>	<?php the_chapter( 'right_content' ) ?></article>
	<aside>		<?php the_illustration( 'right_illustration' ) ?></aside>
</section>