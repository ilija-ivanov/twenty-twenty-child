<?php 
  get_header();
?>

<div class="single-product-container">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="product-main-image"><?php the_post_thumbnail(); ?></div>
    <h1><?php the_title(); ?></h1>
    <div class="product-description"><?php the_content(); ?></div>

    <h4>Product Details</h4>
    <?php $product_price = get_post_meta( get_the_ID(), 'meta_price', true ); ?>
    <p class="product-price">Price: <?php echo $product_price ?></p>
    <?php $product_sale_price = get_post_meta( get_the_ID(), 'meta_sale_price', true ); ?>
    <p class="product-sale-price"><?php 
    if ( $product_sale_price != NULL ) {
      echo "Sale price: " . $product_sale_price;
      echo "<style>.product-price { text-decoration: line-through; }</style>";
    }
    ?></p>
    <?php 
      $product_gallery_images = get_post_meta( get_the_ID(), 'meta_gallery_images', true );  
      $attachment_ids = json_decode($product_gallery_images);
      if ($attachment_ids != NULL) {
        echo "<p>Produt Gallery:</p>";
      } else {
        echo "<p>No images in product gallery.</p>";
      }
    ?>
    
    <div class="product-gallery-images">
      <?php 
        foreach ($attachment_ids as $attachment_id) {
          echo wp_get_attachment_image($attachment_id, 'full');
        }  
      ?>
    </div>
    <p>Product video:</p>
    <?php $product_youtube_iframe = get_post_meta( get_the_ID(), 'meta_youtube_iframe', true ); ?>
    <div class="product-youtube-video"><iframe width="560" height="315" src="<?php echo $product_youtube_iframe ?>" title="YouTube video player" frameborder="0"></iframe></div>
    
  <?php endwhile; endif; ?>
</div>

<?php
$categories = get_the_terms( get_the_ID(), 'product_category' );

if ( $categories ) {
    $category_ids = array();
    foreach ( $categories as $category ) {
        $category_ids[] = $category->term_id;
    }

    $args = array(
        'post_type' => 'product',
        'post__not_in' => array( get_the_ID() ),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_category',
                'field' => 'term_id',
                'terms' => $category_ids
            )
        )
    );

    $related_products = get_posts( $args );

    if ( $related_products ) {
      echo '<div class="related-products">';
      echo '<h3>Related Products &darr;</h3>';
      echo '<ul>';
      foreach ( $related_products as $related_product ) {
          echo '<li><a href="' . get_permalink( $related_product->ID ) . '">' . get_the_title( $related_product->ID ) . '</a></li>';
      }
      echo '</ul>';
      echo '</div>';
  }
}
?>