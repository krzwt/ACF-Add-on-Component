<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.zealousweb.com/
 * @since      1.0.0
 *
 * @package    Acf_Component_Addon
 * @subpackage Acf_Component_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acf_Component_Addon
 * @subpackage Acf_Component_Addon/admin
 * @author     ZealousWeb
 */
class Acf_Component_Addon_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name=$plugin_name;
		$this->version=$version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Acf_Component_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Acf_Component_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__ ) . 'css/acf-component-addon-admin.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Acf_Component_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Acf_Component_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__ ) . 'js/acf-component-addon-admin.js', array('jquery'), $this->version, false);

	}

	public function register_acf_theme_options() {
		if(function_exists('acf_add_options_page')) {
			acf_add_options_page(array(
				'page_title' => __('ACF Options', 'acf-component-addon'),
				'menu_title' => __('ACF Options', 'acf-component-addon'),
				'menu_slug' => 'acf-options',
				'icon_url' => 'dashicons-admin-settings'
			));
			acf_add_options_sub_page(array(
				'page_title' => __('ACF Component Layout ', 'acf-component-addon'),
				'menu_title' => __('ACF Options', 'acf-component-addon'),
				'parent_slug' => 'acf-options',
			));
		}
	}

	public function filter_acf_load_field($field) {
		$postid = get_the_ID();
		$component_directory = THEME_DIRECTORY_COMPONENTS_PATH;
		if ($field['name'] == 'page_component' && $field['parent'] == $postid) {
			$field_groups = acf_get_field_groups();
			$id = array_column($field_groups, 'ID');
			$key = array_column($field_groups, 'key');
			$field_key_array = array_combine($id, $key);
			$option_name = 'page_component_key';
			$new_value = $field_key_array[$postid];
			$deprecated = null;
			$autoload = 'no';
			if ( get_option($option_name) !== false ) {
				update_option($option_name, $new_value, $deprecated, $autoload);
			} else {
				add_option($option_name, $new_value, $deprecated, $autoload);
			}
		}
		return $field;
	}

	public function acf_add_local_field_groups() {
		// if(function_exists('acf_add_local_field_group')):
			$fieldArry = array(
				array(
					'key' => 'field_my_accordion',
					'label' => __('Components List','acf-component-addon'),
					'name' => 'my_accordion',
					'type' => 'accordion',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'open' => 0,
					'multi_expand' => 0,
				),
				array(
					'key' => 'field_select_component',
					'label' => __('Select Components','acf-component-addon'),
					'name' => 'my_select_page_components',
					'type' => 'checkbox',
					'layout' => 'horizontal',
					'instructions' => '',
					'required' => 1,
					'choices' => array(),
					'default_value' => [],
					'allow_null' => 0,
					'multiple' => 1,
					'ui' => 1,
					"toggle" => 1,
					'return_format' => 'value',
					'ajax' => 0,
					'placeholder' => '',
					'wrapper' => array(
						'width' => '',
						'class' => 'acf-page-custom-checkbox',
						'id' => ''
					),
				)
			);
			$fieldArrWarning = array (
				array (
					"key" => "field_warning_message",
					"label" => "Warning:",
					"name" => "",
					"aria-label" => "",
					"type" => "message",
					"instructions" => "",
					"required" => 0,
					"conditional_logic" => 0,
					"message" => __("ACF Page Component not found or not in sync.","acf-component-addon"),
					"new_lines" => "wpautop",
					"esc_html" => 0
				)
			);
			$page_component_field_key = get_option('page_component_key');
			$field_type = isset($page_component_field_key) && !empty($page_component_field_key) ? $fieldArry : $fieldArrWarning;
			acf_add_local_field_group(array (
					'key' => 'my_page_component_selection',
					'title' => 'Page Component Selection',
					'fields' => array (
						array (
							'key' => 'field_page_selection',
							'label' => __('Page Component Selection','acf-component-addon'),
							'name' => 'my_page_component_selection',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'collapsed' => '',
							'min' => 1,
							'max' => 0,
							'layout' => 'table',
							'button_label' => __('Add More','acf-component-addon'),
							'sub_fields' => array (
								array ('key' => 'field_page_template',
									'label' => __('Select Page Template and Post','acf-component-addon'),
									'name' => 'my_select_page_template',
									'type' => 'select',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'choices' => array(),
									'default_value' => false,
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'return_format' => 'value',
									'ajax' => 0,
									'placeholder' => ''
								),
								array (
									'key' => 'group_page_components',
									'label' => __('Select Page Components','acf-component-addon'),
									'name' => 'select_page_components',
									'type' => 'group',
									'layout' => 'block', // Use 'block' layout for accordion effect
									'instructions' => '',
									'conditional_logic' => 0,
									'sub_fields' => $field_type,
								),
							),
						),
						array (
							'key' => 'field_comp_btn_text',
							'label' => __('Add Component Button Text','acf-component-addon'),
							'name' => 'add_component_button_text',
							'aria-label' => '',
							'type' => 'text',
							'instructions' => __('The button text will be displayed on the add component.','acf-component-addon'),
							'required' => 0,
							'conditional_logic' => 0,
							'default_value' => __('Add Selected Component','acf-component-addon'),
							'maxlength' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => ''
						),
					),
					'location' => array (
						array (
							array ('param' => 'options_page',
								'operator' => '==',
								'value' => 'acf-options-acf-options',
							),
						),
					),
				));
		// endif;
	}

	public function acf_load_my_select_page_template_field_choices($field) {
		$templateArr = array();
		$postArr = array();
		$pagesArr = array();
		$field['choices'] = array();
		$templates = array_merge(get_page_templates(), array('Default' => 'default'));
		if( is_array($templates) ) {
			foreach($templates as $key => $template) {
				$templateArr[$template] = $key;
			}
		}
		ksort($templateArr);
		$args = array(
			'public' => true,
			'_builtin' => false // Only retrieve custom post types, exclude built-in post types like 'post' and 'page'.
		);
		$postArr = array_merge(array('post' => 'Post'));
		$post_types = get_post_types($args, 'objects');
		foreach ($post_types as $post_type) {
			$postArr[$post_type->name] = $post_type->label;
		}
		ksort($postArr);
		$pages = get_pages(); // Retrieve a list of pages

		// Add choices to the ACF field
		if (!empty($pages)) {
			foreach ($pages as $page) {
				$pagesArr[$page->ID] = $page->post_title;
			}
		}
		$field['choices'] = array(
			'Templates' => $templateArr,
			'Posts' => $postArr,
			'Pages' => $pagesArr,
		);
		return $field;
	}

	public function acf_load_my_select_page_components_field_choices($field) {
		$field['choices'] = array();
		$page_component_field_key = get_option('page_component_key');
		if (isset($page_component_field_key) && !empty($page_component_field_key)) {
			$page_component_field_key = str_replace('field_', 'group_', $page_component_field_key);
			$jsonURL = THEME_DIRECTORY_PATH.'/includes/acf/'.$page_component_field_key.'.json';
			$contents = file_get_contents($jsonURL);
			$data = (array) json_decode($contents);

			foreach($data['fields'] as $fields) {
				$fields = (array) $fields;

				foreach($fields['layouts'] as $layout) {
					$layout_name = $layout->name;
					$layout_label = $layout->label;
					$field['choices'][$layout_name] = $layout_label;
				}
			}
		}
		return $field;
	}

	public function acf_admin_head_layout() {
		$availableOptions = [];
		$page_component_field_key = get_option('page_component_key');
		if (isset($page_component_field_key) && !empty($page_component_field_key)) {
			$page_component_field_key = str_replace('field_', 'group_', $page_component_field_key);
			$jsonURL = THEME_DIRECTORY_PATH.'/includes/acf/'.$page_component_field_key.'.json';
			$contents = file_get_contents($jsonURL);
			$data = json_decode($contents);

			foreach( $data->fields as $field ) {
				foreach($field->layouts as $layout) {
					array_push($availableOptions, $layout->name);
				}
			}

			$postId = get_the_ID();
			$post_type_slug = get_post_type($postId);
			$page = get_post($postId);
			$post_type_Arr = array();
			if ($page) {
				$page_name = $page->post_name;     // Get the page slug
				$page_title = $page->post_title;   // Get the page title
				$page_Id = $page->ID;   // Get the page Id
				array_push($post_type_Arr,$page->ID);
			}
			if( $post_type_slug == 'page' ) {
				$post_slug = get_post_meta($postId, '_wp_page_template', true);
				$post_slug = ($post_slug === 'default') ? 'default' : $post_slug;
				array_push($post_type_Arr,$post_slug);
			} elseif( $post_type_slug == 'post' ) {
				$post_slug = 'post';
				array_push($post_type_Arr,$post_slug);
			} else {
				$post_slug = $post_type_slug;
				array_push($post_type_Arr,$post_slug);
			}
			$add_component_button_text = get_field( 'add_component_button_text','option' );
			if( have_rows('my_page_component_selection', 'option') ):
				while( have_rows('my_page_component_selection', 'option') ) : the_row();
					$select_page_template = get_sub_field('my_select_page_template');
					$select_page_components_group = get_sub_field( 'select_page_components' );
					$select_page_components = $select_page_components_group['my_select_page_components'];

					if( in_array($select_page_template,$post_type_Arr) ) {
						$disabledFields=array_values(array_diff($availableOptions, $select_page_components));
						$acf_json_directory=plugin_dir_path(__DIR__).'admin/acf-json';

						if ( !file_exists($acf_json_directory)) {
							mkdir($acf_json_directory, 0775, true);
						}

						$acf_json_file_url = $acf_json_directory .'/disabledFields-'.$postId.'.json';
						$fp=fopen($acf_json_file_url, "wb");
						fwrite($fp, json_encode($disabledFields));
						fclose($fp);
						$acf_json_file_url = plugin_dir_url(__FILE__ ).'acf-json/disabledFields-'.$postId.'.json';
						?><script type="text/javascript">(function($) {
								$(document).ready(function() {
										<?php if (!empty($add_component_button_text)) { ?>
											$('.acf-actions .acf-button[data-name="add-layout"]').text("<?php echo $add_component_button_text; ?>");
										<?php } ?>
										$.get('<?php echo $acf_json_file_url; ?>', function(data) {
												$.each(data, function(i, item) {
														var tmpl=$('.tmpl-popup').html();
														//Create jQuery object
														var tmplDiv=$('<div>', {
																html : tmpl
															}
														);
														//Target element and remove it
														tmplDiv.find('a[data-layout="'+item+'"]').closest('li').remove();
														tmpl=tmplDiv.html();
														$('.tmpl-popup').replaceWith('<script type="text-html" class="tmpl-popup">'+tmpl+'</sc'+'ript>');
													}
												);
											}
										);
									}
								);
							}
						)(jQuery);
						</script><?php
						if( $post_type_Arr[0] == $select_page_template ){break;}
					}
				endwhile;
			endif;
		}
	}
}