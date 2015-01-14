<?php
// ******************************************************
//
// Gestion des contenus
//
// ******************************************************



function get_illustration( $slug, $size ){

	global $post;
	global $story__stories;

	if( empty( $story__stories ) ) define_stories();
	if( empty( $story__stories ) ) return;


	return;
}

function the_illustration( $slug = false, $size = false ){

	global $post;
	global $story__stories;
	global $story__current__story;

	if( empty( $story__stories ) ) define_stories();
	if( empty( $story__stories ) ) return;

	if( $slug === false ) return;
	if( $size === false ) $size = 'full';


	foreach( $story__current__story['contents'] as $key => $element ){

		if( $element['slug'] === $slug ){

			$the_chapter_ID = $element['ID'];

		}

	}

	if( !empty( $the_chapter_ID ) ):
		
		$the__chapter = get_post( $the_chapter_ID );
		echo wp_get_attachment_image( $the__chapter->post_content, $size );

	endif;

}

function the_marker( $slug = false ){

	global $post;
	global $story__stories;
	global $story__current__story;

	if( empty( $story__stories ) ) define_stories();
	if( empty( $story__stories ) ) return;

	if( $slug === false ) return;

	foreach( $story__current__story['contents'] as $key => $element ){

		if( $element['slug'] === $slug ){

			$the_chapter_ID = $element['ID'];

		}

	}

	if( !empty( $the_chapter_ID ) ):
		$the__chapter = get_post( $the_chapter_ID );
		echo $the__chapter->post_content;
	endif;

}
function the_chapter_title( $slug = false ){

	the_marker( $slug );

}
function once_upon_a_time( $slug = false ){

	the_marker( $slug );

}

function get_chapter( $slug = false ){

	global $post;
	global $story__stories;

	if( empty( $story__stories ) ) define_stories();
	if( empty( $story__stories ) ) return;

	if( $slug === false ) return;

	return;
}

function the_chapter( $slug = false ){

	global $post;
	global $story__stories;
	global $story__current__story;

	if( empty( $story__stories ) ) define_stories();
	if( empty( $story__stories ) ) return;

	if( $slug === false ) return;

	foreach( $story__current__story['contents'] as $key => $element ){

		if( $element['slug'] === $slug ){

			$the_chapter_ID = $element['ID'];

		}

	}

	if( !empty( $the_chapter_ID ) ):
		$the__chapter = get_post( $the_chapter_ID );
		echo apply_filters('the_content', $the__chapter->post_content );
	endif;

}

function define_stories(){

	global $post;
	global $story__stories;

	if( empty( $post ) || !is_numeric( $post->ID ) || is_admin() ) return;

	$metas = get_post_meta( $post->ID, '_story_content', true );

	if( ! empty( $metas ) ):

		$story__stories = [];

		foreach ($metas as $key => $metabox):

			$file	  = $metas[ $key ]['file'];
			$contents = $metas[ $key ]['content'];

			$story__stories[] = array(
				'file' 		=> $file,
				'contents' 	=> $contents
			);

		endforeach;

	endif;

}


// fonction public d'affichage des histoires complètes
function the_story() {

	global $post;
	global $story__stories;
	global $story__current__story;


	if( empty( $story__stories ) ) define_stories();


	if( empty( $story__stories ) ) return;

	// log_it($story__stories);

	foreach ($story__stories as $key => $story):


		$story__current__story = $story;

		// remove php extension
		$info = pathinfo( $story['file'] );
		$name = $info['filename'];


		echo get_story_template( $name );

	endforeach;

}

function get_story_template( $story__name ){

	global $post;
	global $story__stories;
	global $story__current__story;

	if( empty( $story__stories ) ) define_stories();
	if( empty( $story__stories ) ) return;
	if( empty( $story__name ) ) return;

	return get_template_part( STORY_FOLDER . '/' . $story__name );

}