# Elementor Test

## Part 1 - Git

GitHub repository created and can be found here: https://github.com/ilija-ivanov/twenty-twenty-child
Commits can be observed on GitHub

## Part 2 - WP preparation

Child theme created.

**IMPORTANT:** This is a child theme for the default WordPress theme "twentytwenty". Before activating the theme, please make sure that "twentytwenty" is installed.

## Part 3 - Users

Created a user in functions.php and disabled their admin bar

## Part 4 - Post Types

-Created CPT "products" in functions.php

-Data items:
--Added the title, description, and main image through 'supports' arguments via register_post_type (description can be the standard editor field)
--Created custom "category" taxonomy for "products" CPT in functions.php
--For the data items price, sale price, youtube video, and product image gallery, I created a custom field meta box
--Youtube videos will be handled by inputing an iframe that we can later display on the frontend
--On Sale data item is checked and set if the "sale price" field has a value

-Creating products:
--Created 3 categories for our products
--Created 6 products in functions.php, 3 of them are on sale, and added 2 products to each category

-Creating products grid on the front page:
--Created front-page.php template file for the front page
--Added loop to display the products, their featured image, and their title, as well as linking them to the single product page
--Created single-product.php template file for the single product CPT
--Pulled data for the single product including custom fields from the meta box to display it on the frontend
--Added related products according to category at the bottom of the single product template
--Added styling in the style.css file for both the products grid on the front page and the single product
