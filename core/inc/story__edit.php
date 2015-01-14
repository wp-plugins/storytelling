<?php


// ******************************************************
//
// Generation des metabox à l'ouverture d'un update de post
//
// ******************************************************


class Storytelling__edit
{

	public function __construct(){
		add_action( 'edit_form_after_editor', array( $this, 'Storytelling__getdata' ) );
	}

	public function Storytelling__getdata( $post ) {


		// metabox represente les metabox par groupe de template
		$metabox = [];
		$metabox_contenu = [];
		$metabox__structure = []; // structure ID des elements pour la suppression


		// ====================================================================
		//
		// on récupère les templates disponibles
		//
		// ====================================================================
		$story__structure = new Storytelling__structure();

        // log_it( $templates );

        // on instancie les editeurs et on le passe en mode update
		$editeur = new Storytelling__editors();
		$editeur->update = true;

		// on récupère les metas
		$metas = get_post_meta( $post->ID, '_story_content', true );

// ===================================================================
// V 0.2 - refactorisation
// ===================================================================

		if( ! empty( $metas ) ):


			foreach ($metas as $key => $metabox):

				$metaSlugs = [];

				$template = $metabox['template'];
				$file = $metabox['file'];
				$container = $metabox['container'];
				$contents = $metabox['content'];
				
				$fileSlugs = $story__structure->Storytelling__getFileSlugs( $file ); 
				$structure = $story__structure->Storytelling__getFileStructure( $file );

				// variable pour la metabox
				$editeur->template = $template;
				$editeur->file = $file;
				$editeur->postID = $post->ID;


					$editeur->openMetaBox( $key );
					
						// on parcours tout les slug de la structure
						foreach( $fileSlugs as $keyS => $slug ):

							$name__editor = "story__editor__" . ( $container + $keyS +1 );

							$editeur->slug = $slug;
							$editeur->container__id = $name__editor;
							$editeur->name = $story__structure->Storytelling__getNameFileSlug( $editeur->file, $editeur->slug );

							// log_it('je regarde le slug N' . $keyS . ' de la structure ' . $key);

							// on récupère la data correspondant au slug :
							// on parcours les datas à la recherche du slug :
							$dataID = false;
							$dataType = false;
							$dataI = false;

							// log_it($contents);

							foreach ($contents as $i => $content):
								$currentSlug = $content['slug'];
								if( $currentSlug === $slug ):
									$dataID = $content['ID'];
									$dataType = $content['type'];
									$dataI = $i;
								endif;
							endforeach;

							if( is_numeric( $dataID ) ):
								
								$diffType = $story__structure->Storytelling__slugType( $editeur->file, $editeur->slug );
								if( $diffType === $dataType ):

									$story__post = get_post( $dataID );

									$editeur->ID = $story__post->ID;
									$editeur->content = $story__post->post_content;

									$metabox__structure[] = $story__post->ID;


								else:

									$editeur->ID = '';
									$editeur->content = '';
				

								endif;
							
							else:

									$editeur->ID = '';
									$editeur->content = '';


							endif;

							switch ( $dataType ) {
								case 'image':
									$editeur->images__id = $editeur->content;
									$editeur->getNewImage();
									break;

								case 'editeur':
									$editeur->getNewEditor();
									break;

								case 'title':
									$editeur->getNewTitle();
									break;

								default:
									$editeur->getNewEditor();
							}

						endforeach;

					$editeur->elementsRemove = implode(',', $metabox__structure);
					$metabox__structure = [];
					$editeur->closeMetaBox();
					
				// endif;



			endforeach;

		endif;

// ===================================================================
// V 0.2 - refactorisation
// ===================================================================

	}

}