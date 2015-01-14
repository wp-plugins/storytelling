<?php
class Storytelling__metabox
{

    public function __construct()
    {
        load_textdomain('storytelling', STORY_DIR . 'lang/story-' . get_locale() . '.mo');
        add_action( 'add_meta_boxes', array($this, 'story__addMetaBox__Sidebar') );
    }

    public function story__addMetaBox__Sidebar(){
        $screens = array( 'post', 'page' );

        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $output = 'objects'; // names or objects

        $post_types = get_post_types( $args, $output );

        // print_r($post_types);


        foreach ( $post_types  as $post_type ) {

            $screens[] = $post_type->name;

        }

        foreach ( $screens as $screen ) {

            add_meta_box(
                'story__selector',
                __( 'Add a chapter', 'storytelling' ),
                array($this, 'story__addMetaBox__Sidebar__callback'),
                $screen,
                'side',
                'core'
            );

        }

    }

    public function story__addMetaBox__Sidebar__callback(){

        $story_structure = new Storytelling__structure();
        $templates = $story_structure->Storytelling__register__templates();

            // on parcourt les templates et on les affiche
        ?><ol><?php

            foreach ($templates as &$template) {

                $template = json_decode($template);

                $elements = [];
                foreach( $template->elements as $key => $element ):
                    $elements[] = $element->type;
                endforeach;
                $structure = implode(',', $elements);

                $elements = [];
                foreach( $template->elements as $key => $element ):
                    $elements[] = $element->slug;
                endforeach;
                $slugs = implode(',', $elements);

        ?>

                <li>
                    <a href="#" data-file="<?=$template->file?>" data-name="<?=$template->name?>" data-structure="<?=$structure?>" data-slugs="<?=$slugs?>">
                        <h4><?=$template->name?></h4>
                        <p>
                            <?=$template->description?>
                        </p>
                    </a>
                </li>

        <?php
            }

        ?></ol><?php

    }

}