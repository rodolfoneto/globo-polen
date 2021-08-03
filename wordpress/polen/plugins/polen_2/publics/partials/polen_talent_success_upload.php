<?php
// $order vem do controller Polen_Talent::my_account_success_upload_content()
// $order é um Automattic\WooCommerce\Admin\Overrides\Order
$order_total = $order->get_subtotal();
$order_partial = polen_apply_polen_part_price( $order_total );
$is_social = social_order_is_social( $order );

if( ! $is_social ) :
?>
<main id="primary" class="site-main mt-5">
	<div class="row box-video-success">
		<div class="col-12 text-center">
			<img src="<?php echo TEMPLATE_URI; ?>/assets/img/video-sucesso.png" alt="Imagem de sucesso de video" />
			<p class="text mt-5"><strong>Pronto!<br />Seu vídeo foi enviado.</strong><br />Você ganhou:</p>
            <p class="value">R$<?= number_format( $order_partial, 2, ',', '' ); ?></p>
		</div>
		<div class="col-12 text-center mt-3">
			<a href="/my-account/orders/" class="btn btn-outline-light btn-lg">Ver mais pedidos</a>
		</div>
	</div>
</main>
<?php else: ?>
<?php // QUANDO É SOCIAL NO CASO CRIESP SE FOR REMOVER É SO TIRAR O IF_ELSE E DEIXAR O CONTEUDO DO IF (O DE CIMA) ?>
<main id="primary" class="site-main mt-5">
	<div class="row box-video-success">
		<div class="col-12 text-center">
			<img src="<?php echo TEMPLATE_URI; ?>/assets/img/video-sucesso.png" alt="Imagem de sucesso de video" />
			<p class="text mt-5"><strong>Pronto!<br />Seu vídeo foi enviado.</strong><br />Obrigado pela sua doação:</p>
            <p class="value">R$<?= number_format( $order_total, 2, ',', '' ); ?></p>
		</div>
		<div class="col-12 text-center mt-3">
			<a href="/my-account/orders/" class="btn btn-outline-light btn-lg">Ver mais pedidos</a>
		</div>
	</div>
</main>
<?php endif; ?>