(function($){

	$rapper 	= 'story__rapper',
	$selector 	= 'story__selector';

	var n__element 		= 0;
	var n__post 		= 0;

	var n__metabox 		= 0;
	var n__element 		= 0;

	var file_frame;

	// ================================
	// get total post of story
	// ================================


	function getMetaboxs(){
		// retourne le nombre d'elements disponibles dans la page
		n__metabox = jQuery('.story-container').length;
	}
	function getElements(){
		// retourne le nombre d'elements disponibles dans la page
		n__element = jQuery('.story__element').length;
	}

	// ================================
	// requete template
	// ================================


	function getTemplate( file, structure ){

		structure.replace(/ /g,'');
		var structureArray = structure.split(',');
		var contentLength = structureArray.length;

		var data = {
			'action': 'Storytelling__getNewBox',
			// 'action': 'Storytelling__getNewMacro',
			'file' : file,
			'n__metabox': n__metabox
		};


	    $.post('/wp-admin/admin-ajax.php', data, function(response) {


	    	$( '#post-body-content' ).append( response );

	    	window.setTimeout(function() {


				if( n__element === 0 )$('.story-container').first().addClass('story-first');

	    		// pour chaque structure de type content, on init un tinymce
	    		for (index = 0; index < contentLength; ++index) {


	    			var new__editor = "story__editor__" + parseInt( parseInt( n__metabox * 1000 ) + parseInt( index + 1 ) );
	    			// console.log('je vais créer un nouvel éditeur n ommé ' + new__editor);

					if( $.trim(structureArray[ index ]) === "editor" ){

			    		// on test si l'editeur a déja été instancié
			    		instance = false;
			    		$.each( tinymce.editors, function(e){

			    			if( this.id === new__editor ){
			    				// deja instancié 
			    				instance = true;
			    			}
			    		});

			    		if( instance === true ){
			    			// console.log('instance exist ' + new__editor);
			    			tinymce.EditorManager.execCommand('mceAddEditor',true, new__editor);
			    		}else{		    			
			    			// console.log('instance exist pas init ' + new__editor);
							tinyMCEPreInit.mceInit[ 'content' ].selector = '#' + new__editor;
							tinyMCEPreInit.qtInit[ 'content' ].id = new__editor;
							tinymce.init(tinyMCEPreInit.mceInit[ 'content' ]);
							quicktags( tinyMCEPreInit.qtInit[ 'content' ] );
			    		}
			    		instance = false;


					}

					
				}


	    	},100);

			n__metabox++;

	    });

	}

	// ================================
	// instanciate image uploader
	// ================================
	var selectedButton, imageRemover;

	$(document).on('click','.story__element__image .upload_image_button', function(e) {
 
        e.preventDefault();
 
 		selectedButton = $(this);
 		imageRemover = $(this).closest('.story__element').find('.story__imageRemover');


        //If the uploader object has already been created, reopen the dialog
        if ( file_frame ) {
	      file_frame.open();
	      return;
	    }
 
	    // Create the media frame.
	    file_frame = wp.media.frames.file_frame = wp.media({
	      title: jQuery( this ).data( 'upload_image' ),
	      button: {
	        text: jQuery( this ).data( 'upload_image_button' ),
	      },
	      multiple: false  // Set to true to allow multiple files to be selected
	    });


	    // When an image is selected, run a callback.
	    file_frame.on( 'select', function() {

			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();

			$('<img>', {
			    src: attachment.sizes.medium.url
			}).insertBefore( selectedButton );

			selectedButton.hide();
			imageRemover.show();

			selectedButton.closest('.story__element')
			.find('.story__image__id').attr('value', attachment.id );


	    });

	    // Finally, open the modal
	    file_frame.open();
 
    });


	// ================================
	// Remove story Element
	// ================================
	// delete
	$(document).on('click','.story__remove__element .remover a', function(e) {
		e.preventDefault();
		$(this).closest('.story__remove__element').find('.confirm').show();
		$(this).hide();
	});
	// confirm
	$(document).on('click','.story__remove__element .confirm .delete', function(e) {
		e.preventDefault();

		var buttonRemove = $(this);
		var elements = buttonRemove.closest('.story__remove__element').data('elements')

		var data = {
			'action': 'Storytellling__deleteElements',
			'elements': encodeURIComponent(elements),
			'parent': jQuery('#post_ID').val()
		};

		if( elements != '' ){

			$.post('/wp-admin/admin-ajax.php', data, function(response) {


					buttonRemove.closest('.story-container').remove();
					// on supprimer aussi tout les editeurs tiny mce
					$.each( buttonRemove.closest('.story-container').find('input[name="story__post__[]"]'), function(e){

						// console.log(' tinymce : suppression de lediteur ' + $(this).val());

						tinymce.EditorManager.execCommand('mceRemoveEditor',true, $(this).val() );
			
					});

					getElements();

			});

		}else{
			buttonRemove.closest('.story-container').remove();
			// n__metabox--;
		}


	});
	// cancel
	$(document).on('click','.story__remove__element .confirm .cancel', function(e) {
		e.preventDefault();
		$(this).closest('.story__remove__element').find('.confirm').hide();
		$(this).closest('.story__remove__element').find('.remover a').show();
	});


	// ================================
	// instanciate image remover
	// ================================

	$(document).on('click','.story__imageRemover', function(e) {

		e.preventDefault();

		$(this).closest('.story__element').find('img').remove();
		$(this).closest('.story__element').find('.upload_image_button').show();
		$(this).closest('.story__element').find('.story__image__id').attr('value','');
		$(this).hide();

	});

	// ================================
	// selector click
	// ================================

	$(document).on('click', '#story__selector a', function( e ){

		e.preventDefault();

		var file = $(this).data('file');
		// structure permet de charger tout les tinyMCE
		var structure = $(this).data('structure');

		getTemplate( file, structure );

		return false;

	});


	$(document).on('click', '.postbox .handlediv.story', function(){

		var postbox = $(this).closest('.postbox');
		
		if( postbox.hasClass('closed') )
		{
			postbox.removeClass('closed');
		}
		else
		{
			postbox.addClass('closed');
		}
		
	});
    
    $(document).ready(function(){
		getMetaboxs();
		getElements();
    });

})(jQuery);