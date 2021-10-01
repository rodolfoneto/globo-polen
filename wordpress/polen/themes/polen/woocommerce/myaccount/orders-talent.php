<?php

use Polen\Includes\Polen_Talent;

$polen_talent = new Polen_Talent();

$talent_orders = '';
$logged_user = wp_get_current_user();
if (in_array('user_talent',  $logged_user->roles)) {
	$talent_id = $logged_user->ID;
	$talent_orders = $polen_talent->get_talent_orders($talent_id);
	$video_time = $polen_talent->video_time;
	$count_total = $polen_talent->get_talent_orders($talent_id, false, true);
}

$talent_is_social = social_user_is_social($logged_user->ID);
if (!$talent_is_social) {
	$days_expires = 7;
} else {
	$days_expires = 15;
}

?>
<section class="mt-2">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e('Meus Pedidos', 'polen'); ?></h1>
	</header><!-- .page-header -->
	<div class="page-content">
		<?php
		if (empty($talent_orders)) {
		?>
			<div class="row">
				<div class="col-12 text-center mt-3">
					<?php polen_box_image_message(TEMPLATE_URI . "/assets/img/empty_box.png", "Você ainda não tem pedidos<br />de Vídeos"); ?>
				</div>
			</div>
			<?php
		} else {
			echo "<p class='mt-2 mb-4'>Você tem <strong><span id='order-count'>" . $count_total['qtd'] . "</span> pedido(s) de vídeo</strong>, seus pedidos expiram em até {$days_expires} dias.</p>";
			if (count($talent_orders) > 0) {
				foreach ($talent_orders as $order) :
					$order_obj = new \WC_Order($order['order_id']);
					$is_social = social_order_is_social($order_obj);

					$total_order_value = $order['total_raw'];
					$discounted_value_order = polen_apply_polen_part_price($total_order_value, $is_social);
			?>
					<div class="row mb-3" box-id="<?php echo $order['order_id']; ?>">
						<div class="col md-12">
							<div class="box-round p-3">
								<div class="row py-2">
									<div class="col-12 col-md-12">
										<div class="row">
											<?php
											if (!empty($order['from'])) : ?>
												<div class="col-12 col-md-12">
													<p class="p">Vídeo de</p>
													<p class="value small"><?php echo $order['from']; ?></p>
												</div>
											<?php endif; ?>
											<div class="col-6 col-md-6">
												<p class="p">Para <?php echo (defined('ENV_DEV') && (ENV_DEV === true)) ? '(ID: ' . $order['order_id'] . ')' : ''; ?></p>
												<p class="value small"><?php echo $order['name']; ?></p>
											</div>
											<div class="col-6 col-md-6">
												<p class="p">E-mail</p>
												<p class="value small"><?php echo $order['email']; ?></p>
											</div>
										</div>
										<?php
										if (event_promotional_order_is_event_promotional($order_obj)) {
										?>
											<div class="row mt-2">
												<div class="col-12">
													<p class="p">Ocasião</p>
													<p class="value small">Vídeo-autógrafo</p>
												</div>
											</div>
										<?php
										} else {
										?>
											<div class="row mt-2">
												<div class="col-6 col-md-6">
													<p class="p">Ocasião</p>
													<p class="value small"><?php echo $order['category']; ?></p>
												</div>
												<div class="col-6 col-md-6">
													<p class="p">Valor</p>
													<p class="value small"><?php echo wc_price($discounted_value_order); ?></p>
												</div>
											</div>
										<?php
										}
										?>
									</div>
									<div class="col-12 col-md-12">
										<div class="row">
											<div class="col-md-12">
												<div class="row mt-2">
													<div class="col-6 col-md-6">
														<p class="p">Tempo estimado</p>
														<p class="value small"><?php echo $video_time . ' segundos'; ?></p>
													</div>
													<div class="col-6 col-md-6">
														<p class="p">Válido por</p>
														<p class="value small">
															<?php
															echo $polen_talent->video_expiration_time($logged_user, $order['order_id'], $is_social);
															?>
														</p>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="row mt-2">
													<div class="col-12 col-md-12">
														<p class="p">Instruções</p>
														<p class="value small"><?php echo $order['instructions']; ?></p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-md-12 mt-4">
										<div class="row">

											<?php
											if ($order['status'] == 'talent-accepted') {
											?>
												<div class="col-12 col-md-12">
													<button class="btn btn-outline-light btn-lg btn-block btn-enviar-video" data-toggle="" data-target="" onclick="window.location.href = '/my-account/send-video/?order_id=<?php echo $order['order_id']; ?>'">Enviar vídeo</button>
												</div>
											<?php
											}

											if ($order['status'] == 'payment-approved') {
												$order_nonce = wp_create_nonce('polen-order-data-nonce');
												$accept_reject_nonce = wp_create_nonce('polen-order-accept-nonce');
											?>
												<div class="col-6" button-nonce="<?php echo $accept_reject_nonce; ?>">
													<button type="button" class="btn btn-primary btn-lg btn-block order-check accept" action-type="accept" order-id="<?php echo $order['order_id']; ?>">Aceitar</button>
												</div>
												<div class="col-6">
													<button class="btn btn-outline-light btn-lg btn-block btn-visualizar-pedido" order-id="<?php echo $order['order_id']; ?>" data-toggle="modal" data-target="#OrderActions">Declinar</button>
												</div>
											<?php
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
		<?php
				endforeach;
			}
		}
		?>

		<!-- Modal -->
		<div class="modal fade" id="OrderActions" tabindex="-1" role="dialog" aria-labelledby="OrderActionsTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="row modal-body">
						<!-- Início -->
						<div class="col-12 background talent-order-modal">
							<button type="button" class="modal-close" data-dismiss="modal" aria-label="Fechar">
								<?php Icon_Class::polen_icon_close(); ?>
							</button>
							<div class="row body">
								<div class="col-12 background">
									<h1 class="page-title">Olá, poderia nos explicar por quê você decidiu rejeitar esse pedido de vídeo?</h1>
								</div>
								<div class="col-12 mt-3">
									<select id="reason" class="form-control form-control-lg custom-select" required="required">
										<option value="">Selecione o motivo *</option>
										<option value="linguagem-impropria">Linguagem Imprópria</option>
										<option value="direitos-autorais">Direitos Autorais</option>
										<option value="pedido-complexo">Não consegui entender o pedido</option>
										<option value="outro">Outro</option>
									</select>
								</div>
								<div class="col-12 mt-3">
									<textarea id="description" rows="4" class="background-grey form-control" placeholder="Descreva o motivo"></textarea>
								</div>
								<div class="col-12 mt-3 mb-4" button-nonce="<?php echo $accept_reject_nonce; ?>">
									<button type="button" class="btn btn-primary btn-lg btn-block order-check" order-id="<?php echo $order['order_id']; ?>" action-type="reject">Declinar pedido</button>
								</div>
							</div>
							<!-- Fim -->
						</div>
					</div>
				</div>
			</div><!-- /Modal -->
		</div><!-- .page-content -->
</section><!-- .no-results -->

<script>
	(function($) {
		'use strict';
		$(document).ready(function() {

			// Removendo border red quando o user selecionar um motivo
			$("#reason").change(function() {
				if (reason !== "") {
					$('#reason').removeClass("border-danger");
					return;
				}
			});

			$('button.order-check').on('click', function() {
				let wnonce = $(this).parent().attr('button-nonce');
				let order_id = $(this).attr('order-id');
				let type = $(this).attr('action-type');

				if (type == 'reject') {
					let reason = $('#reason').val();
					let description = $('#description').val();

					// Obrigando o usuário selecionar a razão
					if (reason === "") {
						$('#reason').toggleClass("border-danger");
						return;
					}
					// Gerando o informações pra rejeição
					var data = {
						action: 'get_talent_acceptance',
						order: order_id,
						type: type,
						security: wnonce,
						reason: reason,
						description: description
					}
				} else {
					// Gerando as informações para aceita
					var data = {
						action: 'get_talent_acceptance',
						order: order_id,
						type: type,
						security: wnonce
					}
				}

				console.log('asdasd2');
				$.ajax({
					type: 'POST',
					url: woocommerce_params.ajax_url,
					data: data,
					success: function(response) {
						let obj = response;
						if (obj.success == true) {
							if (obj.data.code == 1) {
								location.href = '/my-account/send-video/?order_id=' + order_id;
							}
							if (obj.data.code == 2) {
								$('#OrderActions').modal('toggle');
								setSessionMessage(CONSTANTS.SUCCESS, "Sucesso", "Você recusou o pedido com sucesso");
								location.reload();
							}
						}
					},
					error: function() {
						setSessionMessage(CONSTANTS.ERROR, null, "Algo não saiu como esperado, tente novamente");
					}
				});
			});
		});
	})(jQuery);
</script>