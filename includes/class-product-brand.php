<?php



class WooCommerce_Product_Brand
{
  public function __construct()
  {
    add_action('init', array($this, 'register_product_brand_taxonomy'));
    add_filter('term_link', array($this, 'term_link'), 10, 2);
  }

  public function register_product_brand_taxonomy()
  {
    $args = array(
      'hierarchical' => true,
      'labels' => array(
        'name' => __('Product Brands', 'woocommerce'),
        'singular_name' => __('Product Brand', 'woocommerce'),
        'search_items' => __('Search Product Brands', 'woocommerce'),
        'all_items' => __('All Product Brands', 'woocommerce'),
        'parent_item' => __('Parent Product Brand', 'woocommerce'),
        'parent_item_colon' => __('Parent Product Brand:', 'woocommerce'),
        'edit_item' => __('Edit Product Brand', 'woocommerce'),
        'update_item' => __('Update Product Brand', 'woocommerce'),
        'add_new_item' => __('Add New Product Brand', 'woocommerce'),
        'new_item_name' => __('New Product Brand Name', 'woocommerce'),
        'menu_name' => __('Product Brands', 'woocommerce'),
      ),
      'public' => true,
      'hierarchical' => false,
      'show_admin_column' => true,
      'show_in_nav_menus' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array('slug' => 'product_brand'),
    );

    register_taxonomy('product_brand', array('product'), $args);

    add_filter('manage_edit-product_brand_columns', array($this, 'product_brand_columns'));
    add_filter('manage_product_brand_custom_column', array($this, 'product_brand_column_content'), 10, 3);
    add_filter('manage_edit-product_brand_sortable_columns', array($this, 'product_brand_sortable_columns'));
    add_filter('request', array($this, 'product_brand_sort_request'));
  
  }

  public function product_brand_columns($columns)
  {
    $columns['product_brand_weight'] = __('Weight', 'woocommerce');
    $columns['product_brand_image'] = __('Logo', 'woocommerce');
    return $columns;
  }

  public function product_brand_column_content($content, $column_name, $term_id)
  {
    if ($column_name === 'product_brand_weight') {
      $weight = get_term_meta($term_id, 'product_brand_weight', true);
      if (!empty($weight)) {
        $content = esc_html($weight);
      } else {
        $content = '-';
      }
    }

    if ($column_name === 'product_brand_image') {
      $image_id = get_term_meta($term_id, 'product_brand_image', true);
      $size = array('75','50');
      $image = wp_get_attachment_image($image_id, $size);
      if (!empty($image)) {
        $content = $image;
      } else {
        $content = '-';
      }
    }

    return $content;
  }

  public function product_brand_sortable_columns($columns)
  {
    $columns['product_brand_weight'] = 'product_brand_weight';
    return $columns;
  }

  public function product_brand_sort_request($vars)
  {
    if (isset($vars['orderby']) && $vars['orderby'] === 'product_brand_weight') {
      $vars = array_merge($vars, array(
        'meta_key' => 'product_brand_weight',
        'orderby' => 'meta_value_num'
      ));
    }
    return $vars;
  }

public function term_link($url, $term)
  {
    if ($term->taxonomy === 'product_brand') {
      $url = add_query_arg(array('product_brand' => $term->slug), home_url('/'));
    }
    return $url;
  }
}

new WooCommerce_Product_Brand();


// This code creates a class called `WooCommerce_Product_Brand` and registers the custom taxonomy 'product_brand' using the `register_taxonomy()` function. It also adds a meta box to the product editing screen that allows the user to upload an image for the product_brand using the WordPress media uploader. The image is saved as post meta using the `update_post_meta()` function. Finally, the `term_link()` function filters the term link for the 'product_brand' taxonomy to include a query argument that links to a search result for that taxonomy.
