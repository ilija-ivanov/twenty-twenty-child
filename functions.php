<?php

//Enqueue parent theme's stylesheet

add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );
function child_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

//Create a new user when theme is first loaded, and disable the wp admin bar for this user

function create_user_disable_wp_admin_bar() {
	$user_data = array(
		'user_login' => 'wp-test',
		'user_email' => 'wptest@elementor.com',
		'user_pass' => '123456789',
		'role' => 'editor',
	);

	$user_id = wp_insert_user( $user_data );

	update_user_meta( $user_id, 'show_admin_bar_front', false );
}
add_action( 'after_setup_theme', 'create_user_disable_wp_admin_bar' );

//Creating products CPT

function create_custom_post_type_products() {
  $labels = array(
      'name' => 'Products',
      'singular_name' => 'Product',
      'add_new' => 'Add New',
      'add_new_item' => 'Add New Product',
      'edit_item' => 'Edit Product',
      'new_item' => 'New Product',
      'view_item' => 'View Product',
      'search_items' => 'Search Products',
      'not_found' => 'No products found',
      'not_found_in_trash' => 'No products found',
      'parent_item_colon' => '',
      'menu_name' => 'Products'
  );
  $args = array(
      'labels' => $labels,
      'public' => true,
      'has_archive' => true,
      'menu_position' => 4,
      'menu_icon' => 'dashicons-store',
      'supports' => array('title', 'editor', 'thumbnail'),
  );
  register_post_type('product', $args);
}
add_action('init', 'create_custom_post_type_products');

//Creating custom "category" taxonomy

function create_custom_taxonomy_product_category() {
  $labels = array(
      'name' => 'Category',
      'singular_name' => 'Category',
      'search_items' => 'Search Categories',
      'all_items' => 'All Categories',
      'parent_item' => 'Parent Category',
      'parent_item_colon' => 'Parent Category:',
      'edit_item' => 'Edit Category',
      'update_item' => 'Update Category',
      'add_new_item' => 'Add New Category',
      'new_item_name' => 'New Category Name',
      'menu_name' => 'Category'
  );
  $args = array(
      'labels' => $labels,
      'hierarchical' => true,
      'show_admin_column' => true,
      'rewrite' => array('slug' => 'product-category'),
  );
  register_taxonomy('product_category', 'product', $args);
}
add_action('init', 'create_custom_taxonomy_product_category');

//Custom fields for Products CPT

function add_custom_meta_box_products() {
  add_meta_box(
    'product_meta_box',
    'Product Custom Fields',
    'render_meta_box_products',
    'product',
    'normal',
  );
}
add_action('add_meta_boxes', 'add_custom_meta_box_products');

function render_meta_box_products( $post ) {

  include plugin_dir_path( __FILE__ ) . './meta-box-products.php';

}

//Saving custom fields to DB

function save_meta_box_products( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  if ( $parent_id = wp_is_post_revision( $post_id ) ) {
      $post_id = $parent_id;
  }

  $fields = [
      'meta_price',
      'meta_sale_price',
      'meta_youtube_iframe',
  ];
  foreach ( $fields as $field ) {
      if ( array_key_exists( $field, $_POST ) ) {
          update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
      }
   }

  if ( get_post_meta( $post_id, 'meta_sale_price', true ) != NULL ) {
    update_post_meta( $post_id, '_meta_on_sale', true );
  } else {
    update_post_meta( $post_id, '_meta_on_sale', false );
  }

  $gallery_images = isset( $_POST['meta_gallery_images'] ) ? array_map( 'absint', $_POST['meta_gallery_images'] ) : array();

  update_post_meta( $post_id, 'meta_gallery_images', json_encode( $gallery_images ) );


}
add_action( 'save_post', 'save_meta_box_products' );

//Creating 3 categories

function create_product_categories() {
  $categories = array(
    'Category 1',
    'Category 2',
    'Category 3'
  );
  
  foreach ( $categories as $category ) {
    wp_insert_term(
      $category,
      'product_category'
    );
  }
}
add_action( 'init', 'create_product_categories' );

//Creating 6 products

function create_products() {

  if (get_option('products_created')) {
    return;
  }

  $categories = get_terms( array(
    'taxonomy' => 'product_category',
    'hide_empty' => false,
  ) );

  // Create 6 products

  for ($i = 1; $i <= 6; $i++) {
    $product_title = "Product $i";
    $product_content = "This is the content for Product $i";
    $product_image = "product-$i.jpg";
    $product_image_url = get_stylesheet_directory() . "/"."img/" . $product_image;
    if ( $i <= 2 ) {
      $product_category = $categories[0]->term_id; 
    } elseif ( $i > 2 && $i <= 4 ) {
      $product_category = $categories[1]->term_id;
    } else {
      $product_category = $categories[2]->term_id;
    }
    $product_price = '100';
    if ( $i <= 3 ) {
      $product_sale_price = '50';
    }  else {
      $product_sale_price = NULL;
    }
    $product_youtube_iframe = 'https://www.youtube.com/embed/MLpWrANjFbI';

    // Creating the product
    $new_product = array(
      'post_title' => $product_title,
      'post_content' => $product_content,
      'post_status' => 'publish',
      'post_type' => 'product',
      'meta_input'    => array(
        'meta_price'    => $product_price,
        'meta_sale_price'    => $product_sale_price,
        'meta_youtube_iframe'    => $product_youtube_iframe,
    ),
    );

    // Inserting the product into the DB
    $product_id = wp_insert_post($new_product);

    wp_set_object_terms( $product_id, array( $product_category ), 'product_category' );

    $upload = wp_upload_bits( $product_image, null, file_get_contents( $product_image_url ) );
    $attachment_id = wp_insert_attachment( array(
      'post_mime_type' => $upload['type'],
      'post_title'     => $product_image,
      'post_content'   => '',
      'post_status'    => 'inherit'
  ), $upload['file'] );
  set_post_thumbnail( $product_id, $attachment_id );
  }
  update_option('products_created', true);
}

add_action('init', 'create_products');

?>