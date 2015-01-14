<?php
/**
 * @package Story Telling
 */
/*
Plugin Name: Story Telling
Plugin URI: http://storytelling.io/
Description: Storytelling enable macro template for developers.
Version: 1.1
Author: David Massiani
Author URI: http://davidmassiani.com
License: GPLv2 or later
Text Domain: storytelling
*/

// Folder name
define ( 'STORY_VERSION', '1.0' );
define ( 'STORY_FOLDER',  'storytelling' );

define ( 'STORY_URL', plugins_url('', __FILE__) );
define ( 'STORY_DIR', dirname(__FILE__) );



if(!function_exists('log_it')){
 function log_it( $message ) {
   if( WP_DEBUG === true ){
     if( is_array( $message ) || is_object( $message ) ){
       error_log( print_r( $message, true ) );
     } else {
       error_log( $message );
     }
   }
 }
}


if( !class_exists('Storytelling__kickstarter') ):

class Storytelling__kickstarter
{

	public $templates = [];
	
    public $story__ajax;
    public $story__metabox;
    public $story__database;
    public $story__post;
    public $story__edit;
    public $story__structure;
    public $story__utility;

	public $name = "STORY__content";
	public $story__templates;

	public function __construct(){

		load_textdomain('storytelling', STORY_DIR . 'core/lang/story-' . get_locale() . '.mo');

		add_action('init', array($this, 'init'), 1);

	}

    public function init(){

		register_post_type( $this->name ,
			array(
				'labels' => array(
				'name' => __( $this->name ),
				'singular_name' => __( $this->name )
			),
			'public' => false,
			'has_archive' => false,
			)
		);
		
	    remove_post_type_support( $this->name, 'title' );


    	$this->Storytelling__include__front__class();


    	if ( !is_admin() ) {

	        // init structure
	        $get__templates = new Storytelling__structure();
	        $this->story__structure = $get__templates->Storytelling__realTemplates();

    	}else{

    		$this->Storytelling__include__admin__class();

    	// ===================================================
    	// 
    	// add register plugins and styles
    	// 
    	// ===================================================

    		$this->Storytelling__register__plugins();
    		$this->Storytelling__register__styles();

    	// =================================================
    	//
    	// instanciation
    	//
    	// =================================================

    		// init ajax
        	$this->story__ajax = new Storytelling__ajax();
	    	// init metabox selector
	        $this->story__metabox = new Storytelling__metabox();
	        // init database
	        $this->story__database = new Storytelling__database();
	        // init post
	        $this->story__post = new Storytelling__post();
	        // init edit
	        $this->story__edit = new Storytelling__edit();

	        // action quand on post du contenu
	       	add_action( 'save_post', array( $this->story__post, 'Storytelling__save' ) );
	       	// action qui ajoute le javascript
	       	add_action( 'admin_footer', array( $this, 'Storytelling__removePageTemplate'), 10);
	       	// fonction appellé après l'installation d'un thème
	       	add_action( 'after_setup_theme', array( $this, 'Storytelling__afterThemeActivation' ) );
	       	add_action( 'after_switch_theme', array( $this, 'Storytelling__afterThemeActivation' ) );

	    }

    }

    // remove storytelling template from template selector.

	public function Storytelling__removePageTemplate() {
	    global $pagenow;
	    if ( in_array( $pagenow, array( 'post-new.php', 'post.php') ) && get_post_type() == 'page' ) { ?>
	        <script type="text/javascript">
	            (function($){
	                $(document).ready(function(){
	                    $('#page_template option[value^="storytelling"]').remove();
	                })
	            })(jQuery)
	        </script>
	    <?php 
	    }
	}

    public function Storytelling__include__admin__class(){
		include_once plugin_dir_path(__FILE__). '/core/inc/story__database.php';
		include_once plugin_dir_path(__FILE__). '/core/inc/story__metabox.php';
		include_once plugin_dir_path(__FILE__). '/core/inc/story__editors.php';
		include_once plugin_dir_path(__FILE__). '/core/inc/story__ajax.php';
		include_once plugin_dir_path(__FILE__). '/core/inc/story__post.php';
		include_once plugin_dir_path(__FILE__). '/core/inc/story__edit.php';
		include_once plugin_dir_path(__FILE__). '/core/inc/story__remover.php';
    }
    public function Storytelling__include__front__class(){
		include_once plugin_dir_path(__FILE__). '/core/inc/story__content.php';
		include_once plugin_dir_path(__FILE__). '/core/inc/story__utility.php';
		include_once plugin_dir_path(__FILE__). '/core/inc/story__structure.php';
    }

    static function Storytelling__activation(){
    	$story__folder = get_template_directory() . '/storytelling';
    	if( ! file_exists( $story__folder ) ){
    		mkdir( $story__folder , 775 );
    	}

    }

    public function Storytelling__afterThemeActivation(){
    	$story__folder = get_template_directory() . '/storytelling';
    	if( ! file_exists( $story__folder ) ){
    		mkdir( $story__folder , 775 );
    	}
    }

    // ============================================================
    // Register JS plugins
    // ============================================================

    public function Storytelling__register__plugins(){
		// register javascript 
		$scripts = array();

		$scripts[] = array(
			'handle'	=> 'Storytelling',
			'src'		=> STORY_URL . '/front/js/storytelling.js',
			'deps'		=> array('jquery')
		);

		foreach( $scripts as $script )
		{
			wp_enqueue_script( $script['handle'], $script['src'], $script['deps'] );
		}
    }

    // ============================================================
    // Register CSS Styles
    // ============================================================

    public function Storytelling__register__styles(){
		// register styles
		$styles = array();
		
		$styles[] = array(
			'handle'	=> 'storytelling',
			'src'		=> STORY_URL . '/front/css/storytelling.css'
		);
		
		foreach( $styles as $style )
		{
			wp_enqueue_style( $style['handle'], $style['src'] );
		}
    }

}
function storytelling()
{
	global $storytelling;
	
	if( !isset($storytelling) )
	{
		$storytelling = new Storytelling__kickstarter();
	}
	
	return $storytelling;
}


// initialize
storytelling();

// hook qui appelle la fonction à l'activation du theme
// log_it('not admin');
register_activation_hook( __FILE__, array( 'Storytelling__kickstarter' , 'Storytelling__activation' ) );


endif; // class_exists check