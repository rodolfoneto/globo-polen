<?php

/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

function get_form_login()
{
?>
	<div class="row">
		<div class="col-12 col-md-5 mx-md-auto tab-login">

			<div class="col-12 text-center">
				<h2><?php esc_html_e('Login', 'woocommerce'); ?></h2>
			</div>

			<form class="woocommerce-form woocommerce-form-login login" method="post">
				<div class="row text-center">
					<div class="col-12 col-md-12">
						<?php do_action('woocommerce_login_form_start'); ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<?php /*<label for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label> */ ?>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="username" id="username" autocomplete="username" placeholder="<?php esc_html_e('Username or email address', 'woocommerce'); ?>" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
																																																																																													?>
						</p>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<?php /*<label for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label> */ ?>
							<input class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php esc_html_e('Password', 'woocommerce'); ?>" />
						</p>

						<?php do_action('woocommerce_login_form'); ?>

						<p class="form-row text-left">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
							</label>
							<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
							<button type="submit" class="woocommerce-button woocommerce-form-login__submit btn btn-primary btn-lg btn-block btn-login" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
						</p>
						<p class="woocommerce-LostPassword lost_password">
							<a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
						</p>

						<?php do_action('woocommerce_login_form_end'); ?>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php
}
?>
<div class="row mt-4 justify-content-md-center talent-login">
	<?php do_action('woocommerce_before_customer_login_form'); ?>

	<div class="col-12 col-md-12" id="customer_login">
		<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
			<div class="row">
				<div class="col-12">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true"><?php esc_html_e('Login', 'woocommerce'); ?></a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="create-tab" data-toggle="tab" href="#create" role="tab" aria-controls="create" aria-selected="false"><?php esc_html_e('Register', 'woocommerce'); ?></a>
						</li>
					</ul>
					<div class="tab-content mt-4" id="myTabContent">
						<div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
							<?php get_form_login(); ?>
						</div>
						<div class="tab-pane fade" id="create" role="tabpanel" aria-labelledby="create-tab">
							<div class="row">
								<div class="col-12 tab-create-account">

									<h2 class="text-center"><?php esc_html_e('Register', 'woocommerce'); ?></h2>

									<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

										<?php do_action('woocommerce_register_form_start'); ?>

										<?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

											<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
												<input type="text" placeholder="<?php esc_html_e('Username', 'woocommerce'); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
																																																																																															?>
											</p>

										<?php endif; ?>

										<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
											<input type="email" placeholder="<?php esc_html_e('Email address', 'woocommerce'); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
																																																																																												?>
										</p>

										<?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

											<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
												<input type="password" placeholder="<?php esc_html_e('Password', 'woocommerce'); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="password" id="reg_password" autocomplete="new-password" />
											</p>

										<?php else : ?>

											<p><?php esc_html_e('A password will be sent to your email address.', 'woocommerce'); ?></p>

										<?php endif; ?>

										<?php do_action('woocommerce_register_form'); ?>

										<p class="woocommerce-form-row form-row">
											<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
											<button type="submit" class="woocommerce-Button woocommerce-button woocommerce-form-register__submit btn btn-primary btn-lg btn-block" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
										</p>

										<?php do_action('woocommerce_register_form_end'); ?>

									</form>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php else : ?>
			<?php get_form_login(); ?>
		<?php endif; ?>
	</div>

	<?php do_action('woocommerce_after_customer_login_form'); ?>
</div>

<?php //polen_front_get_tutorial();
?>
