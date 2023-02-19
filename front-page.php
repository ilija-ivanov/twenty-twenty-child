<?php
get_header();

$args = array(
  'post_type' => 'product',
  'posts_per_page' => 6
);
$products_query = new WP_Query( $args );
?>

<?php if ( $products_query->have_posts() ) : ?>
  <div class="products-grid">
    <?php while ( $products_query->have_posts() ) : $products_query->the_post(); ?>
      <div class="product-item">
        <?php 
          $on_sale = get_post_meta( get_the_ID(), '_meta_on_sale', true );
          if( $on_sale == '1' ) {
            echo '<span class="on-sale">SALE</span>';
          }
        ?>
        <a href="<?php the_permalink(); ?>">
          <div class="product-image">
            <?php the_post_thumbnail(); ?>
          </div>
          <h2><?php the_title(); ?></h2>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>