<?php

class KlasikEditor_Ayar_Sayfasi {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
	}

	public function wph_create_settings() {
		$page_title = 'Klasik Editor Aktif Pasif';
		$menu_title = 'Klasik Editör';
		$capability = 'manage_options';
		$slug = 'KlasikEditor';
		$callback = array($this, 'wph_settings_content');
                add_options_page($page_title, $menu_title, $capability, $slug, $callback);
		
	}
    
	public function wph_settings_content() { ?>
		<div class="wrap">
			<h1>Klasik Editör</h1>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'KlasikEditor' );
					do_settings_sections( 'KlasikEditor' );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	public function wph_setup_sections() {
		add_settings_section( 'KlasikEditor_section', 'Wordpress klasik editör aktif / pasif', array(), 'KlasikEditor' );
	}

	public function wph_setup_fields() {
		$fields = array(
                    array(
                        'section' => 'KlasikEditor_section',
                        'label' => 'Aktifleştirmek için tıklayın',
                        'id' => 'klasik_editor_ayar',
                        'type' => 'checkbox',
                    )
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'wph_field_callback' ), 'KlasikEditor', $field['section'], $field );
			register_setting( 'KlasikEditor', $field['id'] );
		}
	}
	public function wph_field_callback( $field ) {
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset($field['placeholder']) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
            
            
                        case 'checkbox':
                            printf('<input %s id="%s" name="%s" type="checkbox" value="1">',
                                $value === '1' ? 'checked' : '',
                                $field['id'],
                                $field['id']
                        );
                            break;

			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}
    
}
new KlasikEditor_Ayar_Sayfasi();

if(get_option( 'klasik_editor_ayar' )=='1'){ //Ayar Check
    function klasik_editor() {
        if (version_compare($GLOBALS['wp_version'], '5.0-beta', '>')) {
        
        // WP > 5 beta
        add_filter('use_block_editor_for_post_type', '__return_false', 100);
        
        } else {
        
        // WP < 5 beta
        add_filter('gutenberg_can_edit_post_type', '__return_false');
        
        }
        }
        add_action('admin_init', 'klasik_editor' );
      }