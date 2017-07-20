<?php
if (!class_exists('GNA_SearchShortcode')) {
	class GNA_SearchShortcode {
		var $searchform_default_settings = null;
		var $plugin_url;

		public function init() {
			$class = __CLASS__;
			new $class;
		}
		
		public function __construct() {
			$this->define_constants();
			$this->define_variables();
			$this->setup_shortcodes();
			
			add_action('init', array(&$this, 'plugin_init'), 0);
			add_action('wp_print_styles', array(&$this, 'add_front_styles'));
			add_filter('plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2);
		}
		
		public function define_constants() {
			define('GNA_SEARCH_SHORTCODE_VERSION', '0.9.5');
			
			define('GNA_SEARCH_SHORTCODE_BASENAME', plugin_basename(__FILE__));
			define('GNA_SEARCH_SHORTCODE_URL', $this->plugin_url());
		}
		
		public function plugin_url() { 
			if ($this->plugin_url) return $this->plugin_url;
			return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		}

		public function filter_plugin_meta($links, $file) {
			if( strpos( GNA_SEARCH_SHORTCODE_BASENAME, str_replace('.php', '', $file) ) !== false ) { /* After other links */
				$links[] = '<a target="_blank" href="https://profiles.wordpress.org/chris_dev/" rel="external">' . __('Developer\'s Profile', 'gna-cate-list') . '</a>';
			}
			
			return $links;
		}
		
		public function define_variables() {
			$this->searchform_default_settings = array(
				'result_page'		=> get_bloginfo('wpurl'),
				'label'				=> __('Search for:', 'gna-search-shortcode'),
				'placeholder'		=> '',
				'post_type'			=> 'post',
				'category_name'		=> '',
				'taxonomy'			=> '',
				'sentence'			=> 1,
				'order'				=> 'date',
				'orderby'			=> 'DESC',
				'button_text'		=> __('Search', 'gna-search-shortcode')
			);
		}
		
		public function install() {
		}
		
		public function uninstall() {
		}
		
		public function activate_handler() {
		}
		
		public function deactivate_handler() {
		}
		
		public function setup_shortcodes() {
			add_filter('widget_text', 'do_shortcode');

			add_shortcode( 'gna_searchform', array($this, 'searchform_shortcode') );
			add_shortcode( 'searchform', array($this, 'searchform_shortcode') );
		}

		public function plugin_init() {
			load_plugin_textdomain('gna-search-shortcode', false, dirname(plugin_basename(__FILE__ )) . '/languages/');
		}

		public function add_front_styles() {
			wp_enqueue_style('gna-search-shortcode-front-css', GNA_SEARCH_SHORTCODE_URL. '/assets/css/gna-search-shortcode-front-styles.css');
		}
		
		public function searchform_shortcode($atts) {
			$return = '';
			extract(shortcode_atts($this->searchform_default_settings, $atts));

			$searchform_args = array(
				'result_page'		=> $result_page,
				'label'				=> $label,
				'placeholder'		=> $placeholder,
				'post_type'			=> $post_type,
				'category_name'		=> $category_name,
				'taxonomy'			=> $taxonomy,
				'sentence'			=> $sentence,
				'order'				=> $order,
				'orderby'			=> $orderby,
				'button_text'		=> $button_text
			);
			
			$return = '
				<form role="search" method="get" id="searchform" action="' . $result_page . '" >
					<fieldset>
						<label class="screen-reader-text" for="s">' . $label . '</label>
						<input type="text" name="s" id="s" placeholder="'.$placeholder.'" value="' . get_search_query() . '" />
						<input type="hidden" name="post_type" value="'.$post_type.'" />
						<input type="hidden" name="sentence" value="'.$sentence.'" />
						<input type="hidden" name="order" value="'.$order.'" />
						<input type="hidden" name="orderby" value="'.$orderby.'" />';
			
			if(!empty($category_name)) {
				$return .= '<input type="hidden" name="category_name" value="'.$category_name.'" />';
			}
			
			if(!empty($taxonomy)) {
				$return .= '<input type="hidden" name="taxonomy" value="'.$taxonomy.'" />';
			}
			
			$return .= '
						<input type="submit" id="searchsubmit" value="'. $button_text .'" />
					</fieldset>
				</form>';
			
			return $return;
		}
	}
}
$GLOBALS['g_searchshortcode'] = new GNA_SearchShortcode();
