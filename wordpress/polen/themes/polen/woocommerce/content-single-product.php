<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

use Polen\Includes\Module\Polen_Product_Module;

defined('ABSPATH') || exit;

global $product;
global $Polen_Plugin_Settings;
global $post;

$polen_product = new Polen_Product_Module( $product );

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

use Polen\Includes\Polen_Update_Fields;
use Polen\Social_Base\Social_Base_Product;
use Polen\Includes\Polen_Order_Review;

$Talent_Fields = new Polen_Update_Fields();
$Talent_Fields = $Talent_Fields->get_vendor_data($post->post_author);
$terms = wp_get_object_terms(get_the_ID(), 'product_tag');
$categories = wp_get_object_terms(get_the_ID(), 'product_cat');
$videos = polen_get_videos_by_talent($Talent_Fields);

$bg_image = wp_get_attachment_image_src($Talent_Fields->cover_image_id, "large")[0];
$image_data = polen_get_thumbnail(get_the_ID());

$donate = get_post_meta(get_the_ID(), '_is_charity', true);
$donate_name = get_post_meta(get_the_ID(), '_charity_name', true);
$donate_image =  get_post_meta(get_the_ID(), '_url_charity_logo', true);
$donate_text = stripslashes(get_post_meta(get_the_ID(), '_description_charity', true));

$social = Social_Base_Product::product_is_social_base( $product );
$inputs = new Material_Inputs();


$product_post = get_post($product->get_id());
$talent = get_user_by('id', $product_post->post_author);
$reviews = Polen_Order_Review::get_order_reviews_by_talent_id($talent->ID);

// outofstock
// instock
if( 'instock' == $product->get_stock_status() ) {
	$has_stock = true;
} else {
	$has_stock = false;
}

?>

<?php if ($bg_image) : ?>
	<figure class="image-bg">
		<img src="<?php echo $bg_image; ?>" alt="<?php echo $Talent_Fields->nome; ?>">
	</figure>
<?php endif; ?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

	<!-- Tags -->
	<div class="row">
		<div class="col-12 d-flex align-items-center">
			<?php if(sizeof($videos) > 0) : ?>
				<div>
					<h1 class="talent-name" title="<?= get_the_title(); ?>"><?= get_the_title(); ?></h1>
				</div>
			<?php endif; ?>
			<?php polen_get_share_button(); ?>
		</div>
			<?php if($videos && sizeof($videos) > 0): ?>
				<div class="col-12 mt-3">
					<?php polen_front_get_videos_single($Talent_Fields, $videos, $image_data); ?>
				</div>
			<?php else: ?>
				<div class="col-12 col-md-6 m-md-auto">
					<?php polen_front_get_talent_mini_bio($image_data, get_the_title(), $categories[0]->name); ?>
				</div>
			<?php endif; ?>
	</div>

  <!-- Botão de adicionar ao carrinho -->
	<div class="row mt-3 mb-1 talent-page-footer">
		<div class="col-12 col-md-6 m-md-auto pb-3">
			<?php if($has_stock) : ?>
        <?php $inputs->pol_combo_advanced(
        "select_type",
        "select_type",
        array(
          $inputs->pol_combo_advanced_item("Vídeo para uso pessoal", $product->get_price_html(), "Compre um vídeo personalizado para você ou para presentar outra pessoa", "check-pessoal", "pessoal", true, false),
          $inputs->pol_combo_advanced_item("Vídeo para meu negócio", "Valor sob consulta", "Compre um Vídeo Polen para usar no seu negócio", "check-b2b", "b2b", false, !polen_b2b_product_is_enabled($product))
          )); ?>
				<div class="btn-buy-personal">
          <?php echo woocommerce_template_single_add_to_cart(); ?>
        </div>
        <div class="btn-buy-b2b d-none">
          <?php $inputs->material_button_link("btn-b2b", "Pedir vídeo", enterprise_url_home() . '?talent='.get_the_title().'#bus-form-wrapper', false, "", array(), $donate ? "donate" : ""); ?>
        </div>
			<?php else: ?>
        <?php $inputs->material_button_link("todos", "Escolher outro artista", home_url( "shop" ), false, "", array(), $donate ? "donate" : ""); ?>
			<?php endif; ?>
		</div>
    <script>
      const btn_personal = document.querySelector(".btn-buy-personal");
      const btn_b2b = document.querySelector(".btn-buy-b2b");
      const pol_select = document.querySelector("#select_type");
      pol_select && pol_select
        .addEventListener("polcombochange",
          function(e) {
            if(e.detail == "b2b") {
              btn_b2b.classList.remove("d-none");
              btn_personal.classList.add("d-none");
            } else {
              btn_b2b.classList.add("d-none");
              btn_personal.classList.remove("d-none");
            }
          });
    </script>
	</div>
  <!-- --------------------------------------------- -->

	<div class="row">
		<div class="col-12 col-md-6 m-md-auto d-flex justify-content-center">
			<!-- Se for doação -->
			<?php if ($donate) : ?>
				<div class="row">
					<div class="col-md-12 mb-4">
						<?php polen_donate_badge("100% DO CACHÊ DOADO PARA " . $donate_name, false); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- Card dos Reviews -->
  <?php $social || polen_talent_deadline($post, $Talent_Fields); ?>

		<div class="row mt-4">
			<div class="col-12 col-md-6 m-md-auto">
				<?php if (count($terms) > 0) : ?>
					<?php foreach ($terms as $k => $term) : ?>
						<a href="<?= get_tag_link($term); ?>" class="tag-link mb-2"><?= $term->name; ?></a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>

	<!-- Bio -->
	<div class="row mt-4">
		<div class="col-12 col-md-6 m-md-auto">
			<?php echo $product->get_description(); ?>
		</div>
	</div>

	<!-- Share -->
	<?php polen_get_share_icons(); ?>

	<!-- Doação -->
	<?php
	$donate && !$social ?
		polen_front_get_donation_box($donate_image, $donate_text) :
		null;
	$video_depoimento = $product->get_meta( Social_Base_Product::PRODUCT_META_VIDEO_TESTEMONIAL_URL, true );
	$social && sa_get_about($video_depoimento);
	?>

  <!-- Avaliações -->
	<?php $social || polen_talent_review($reviews); ?>

	<!-- Como funciona? -->
	<?php $social || polen_front_get_tutorial(); ?>

	<!-- Produtos Relacionados -->
	<?php polen_box_related_product_by_product_id(get_the_ID());
	?>

</div>

<?php

//TODO botar numa função no local correto --------------------------------------------------
$array_social = array();
$array_sites = array("facebook", "twitter", "instagram", "linkedin", "youtube");

foreach ($array_sites as $key => $site) {
	if(!empty($Talent_Fields->$site)) {
		$array_social[] = urlencode($Talent_Fields->$site);
	}
}

$logo_dark = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' );

// Novo conteúdo Schema.org
$total_reviews = Polen_Order_Review::get_number_total_reviews_by_talent_id($talent->ID);
$sum_reviews = Polen_Order_Review::get_sum_rate_by_talent($talent->ID);
pol_print_schema_data_extended($Talent_Fields, $reviews, $total_reviews, $sum_reviews, $product);

?>

<?php do_action('woocommerce_after_single_product'); ?>
