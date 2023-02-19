<div class="meta_box">
  <!-- Adding CSS to the meta box -->
  <style scoped>
      .meta_box{
          display: grid;
          grid-template-columns: max-content 1fr;
          grid-row-gap: 10px;
          grid-column-gap: 20px;
      }
      .meta_field{
          display: contents;
      }
      .gallery-image {
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
        position: relative;
      }
      .gallery-image img {
        display: block;
        max-width: 100%;
      }
      .remove-gallery-image {
        position: absolute;
        top: 0;
        right: 0;
        background-color: rgba(0,0,0,0.7);
        color: #fff;
        padding: 5px 10px;
        font-size: 14px;
        text-decoration: none;
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
      }
      .gallery-image:hover .remove-gallery-image {
        opacity: 1;
      }  
  </style>

  <!-- Adding custom field inputs -->
  <p class="meta-options meta_field">
      <label for="meta_price">Price</label>
      <input id="meta_price"
          type="number"
          name="meta_price"
          value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'meta_price', true ) ); ?>">
  </p>
  <p class="meta-options meta_field">
      <label for="meta_sale_price">Sale price</label>
      <input id="meta_sale_price"
          type="number"
          name="meta_sale_price"
          value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'meta_sale_price', true ) ); ?>">
  </p>
  <p class="meta-options meta_field">
      <label for="meta_youtube_iframe">YouTube embed link</label>
      <input id="meta_youtube_iframe"
          type="text"
          name="meta_youtube_iframe"
          value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'meta_youtube_iframe', true ) ); ?>">
  </p>
  <label for="meta-gallery-images">Product image gallery</label>
  <div id="meta-gallery-images-container">
  <?php
    $gallery_images = get_post_meta( get_the_ID(), 'meta_gallery_images', true );
    $gallery_images = json_decode( $gallery_images );

    if ( count($gallery_images) > 0 ) {
      foreach ( $gallery_images as $gallery_image ) {
        echo '<div class="gallery-image">';
        echo wp_get_attachment_image( $gallery_image, 'thumbnail' );
        echo '<a href="#" class="remove-gallery-image">Remove</a>';
        echo '<input type="hidden" name="meta_gallery_images[]" value="' . $gallery_image . '" />';
        echo '</div>';
      }
    }
  ?>
  </div>
  <p>
    <input type="button" id="meta-add-gallery-image-button" class="button" value="Add Gallery Image" />
  </p>

  <!-- Handling the image gallery uploader (using WP media library) -->
  <script>
  var custom_uploader;
  var attachment_ids =
  <?php
    if ( count($gallery_images) > 0 ) {
      echo json_encode($gallery_images);    
    } else { echo json_encode(array()); }
  ?> ;
  jQuery('#meta-add-gallery-image-button').click(function(e) {
    e.preventDefault();
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }

    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Images',
      button: {
        text: 'Choose Images'
      },
      multiple: true
    });

    custom_uploader.on('select', function() {
      var selection = custom_uploader.state().get('selection');
      selection.each(function(attachment) {
        if (attachment_ids.length >= 6) {
          alert('You can only select up to 6 images.');
          return false;
        }
        attachment_ids.push(attachment.attributes.id);
        var image_html = '<div class="gallery-image">';
        image_html += '<img src="' + attachment.attributes.sizes.thumbnail.url + '" />';
        image_html += '<a href="#" class="remove-gallery-image">Remove</a>';
        image_html += '<input type="hidden" name="meta_gallery_images[]" value="' + attachment.attributes.id + '" />';
        image_html += '</div>';
        jQuery('#meta-gallery-images-container').append(image_html);
      });
    });
    custom_uploader.open();
  });
  jQuery(document).on('click', '.remove-gallery-image', function(e) {
    e.preventDefault();
    var attachment_id = jQuery(this).closest("input").val();
    attachment_ids.splice( jQuery.inArray( attachment_id, attachment_ids ), 1 );
    jQuery(this).parent().remove();
  });
  </script>
</div>