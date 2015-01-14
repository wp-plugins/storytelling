<?php
class Storytelling__database
{


	public function total__story__content( $post_ID ){

		$args = array(
			'post_type'  	=> 'STORY__content'
			,'order_by'		=> 'ID'
			,'order'		=> 'ASC'
			,'post_parent'	=> $post_ID			
			,'posts_per_page'=>-1
			,'meta_key'		=> 'story__template'
		);
		$story_query = new WP_Query( $args );

		echo $story_query->found_posts;

		// return total content for attributing new editor ID
		// var_dump( wp_count_posts( $this->name ) );

	}


}