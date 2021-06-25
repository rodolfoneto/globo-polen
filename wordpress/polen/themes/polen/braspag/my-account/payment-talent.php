<?php

use Polen\Includes\Polen_Update_Fields;

$polen_fields = new Polen_Update_Fields();

use Polen\Includes\Polen_Talent;

$polen_talent = new Polen_Talent();
$current_user = wp_get_current_user();

if ($polen_talent->is_user_talent($current_user)) {
	$bank_data = $polen_fields->get_vendor_data($current_user->ID);

	$total_alredy_gain = $polen_talent->get_total_by_order_status_return_raw($current_user->ID, 'wc-completed');
	$discounted_alredy_gain =  polen_apply_polen_part_price( $total_alredy_gain );

	$total_will_gain = $polen_talent->get_total_by_order_status_return_raw($current_user->ID);
	$discounted_will_gain = polen_apply_polen_part_price( $total_will_gain );
?>
	<section>
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e('Pagamento', 'polen'); ?></h1>
		</header>
	</section>
	<section class="talent-dashboard-start">
		<div class="page-content">
			<div class="row mt-3">
				<div class="col-md-12">
					<div class="talent-order box-round px-3 py-4">
						<div class="row">
							<div class="col-md-12">
								<p class="p">Valor pago até agora</p>
								<span class="value small"><?php echo wc_price( $discounted_alredy_gain ); ?></span>
							</div>
							<div class="col-md-12 mt-3">
								<p class="p">Saldo a liberar</p>
								<span class="value small"><?php echo wc_price( $discounted_will_gain ); ?></span>
							</div>
						</div>
						<?php
						if (!empty($bank_data)) : ?>
							<div class="row">
								<div class="col-md-12 mt-3">
									<p class="p">Banco</p>
									<span class="value small"><?php echo $bank_data->banco; ?></span>
								</div>
								<div class="col-md-12 mt-3">
									<p class="p">Agencia</p>
									<span class="value small"><?php echo $bank_data->agencia; ?></span>
								</div>
								<div class="col-md-12 mt-3">
									<p class="p">Conta</p>
									<span class="value small"><?php echo $bank_data->conta; ?></span>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div><!-- .page-content -->
	</section>
<?php
}
?>
