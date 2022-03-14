<?php
/**
 * SKU product search 
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_sku_search_controller') ) {
	
	class Raven_sku_search_controller {
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
				add_filter('posts_clauses', array( $this, 'product_search_sku'), 11, 1);
		}
		
		/**
         * Allow to remove method for an hook when, it's a class method used and class don't have variable, but you know the class name :)
         * 
         * @since 1.0.0
         */
        public function remove_filters_for_anonymous_class( $hook_name = '', $class_name ='', $method_name = '', $priority = 0 ) {
            global $wp_filter;
           
            // Take only filters on right hook name and priority
            if ( !isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority]) ) {
                return false;
            }
            
            // Loop on filters registered
            foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
                // Test if filter is an array ! (always for class/method)
                if ( isset($filter_array['function']) && is_array($filter_array['function']) ) {
                    // Test if object is a class, class and method is equal to param !
                    if ( is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && get_class($filter_array['function'][0]) == $class_name && $filter_array['function'][1] == $method_name ) {
                        unset($wp_filter[$hook_name][$priority][$unique_id]);
                    }
                }
                
            }
            
            return false;
        }

		/**
		 * A drop in replacement of WC_Admin_Post_Types::product_search()
         * 
         * @since 1.0.0
		 */
		public function product_search_sku($args) {
			$where = $args['where'];
			global $pagenow, $wpdb, $wp;

			if ((is_admin() && 'edit.php' != $pagenow)
				|| !is_search()
				|| !isset($wp->query_vars['s'])
				//post_types can also be arrays..
				|| (isset($wp->query_vars['post_type']) && 'product' != $wp->query_vars['post_type'])
				|| (isset($wp->query_vars['post_type']) && is_array($wp->query_vars['post_type']) && !in_array('product', $wp->query_vars['post_type']))
			) {
				return $args;
			}

			// Remove SKU from search string
			
			$preg_pattern = array('/sku/', '/SKU/');
			$cleaned_search_query = preg_replace( $preg_pattern,'',$wp->query_vars['s']);
			$search_ids = array();
			$terms = explode(',', $cleaned_search_query);

			foreach ($terms as $term) {
				$term = trim($term);
				//Include the search by id if admin area.
				if (is_admin() && is_numeric($term)) {
					$search_ids[] = $term;
				}
				// search for variations with a matching sku and return the parent.

				$sku_to_parent_id = $wpdb->get_col($wpdb->prepare("SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean($term)));

				//Search for a regular product that matches the sku.
				$sku_to_id = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';", wc_clean($term)));

				$search_ids = array_merge($search_ids, $sku_to_id, $sku_to_parent_id);
			}

			$search_ids = array_unique(array_filter(array_map('absint', $search_ids)));
			
            if (sizeof($search_ids) > 0) {
				$where = str_replace('))', ") OR ({$wpdb->posts}.ID IN (" . implode(',', $search_ids) . ")))", $where);
			}
			$args['where'] = $where;
			$this->remove_filters_for_anonymous_class('posts_search', 'WC_Admin_Post_Types', 'product_search', 10);
			
            return $args;
		}

	}
	
}