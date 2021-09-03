<?php

function sa_get_about($video_depoimento = "")
{
?>
	<section class="row donation-box custom-donation-box mt-4 mb-4">
		<div class="col-md-12">
			<header class="row mb-3">
				<div class="col">
					<h2>Setembro Amarelo</h2>
				</div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="box-round py-4 px-4">
				<div class="row">
					<div class="col-md-12 mt-3">
						<p><strong>Vídeo Depoimento</strong></p>
						<div class="video-slider d-flex justify-content-center">
							<video id="sa-video-about" playsinline controls>
								<source src="<?php echo $video_depoimento; ?>" type="video/mp4">
							</video>
						</div>
					</div>
					<div class="col-md-12 mt-4">
						<p><strong>Sobre o Setembro Amarelo</strong></p>
						<p>O setembro amarelo é uma campanha de conscientização da população ao redor do tema do suicídio, prática geralmente associada a depressão. É divulgada pela Associação Brasileira de Psiquiatria (ABP), pelo Conselho Federal de Medicina (CFM) e pelo Centro de Valorização da Vida (CVV).</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		const video = document.getElementById("sa-video-about");
		video.currentTime = 0.1;
	</script>
<?php
}

function sa_get_modal() {
?>
	<div id="sa-modal">
		<div class="container">
			<div class="row">
				<div class="col-12 d-flex justify-content-between align-items-center">
					<h3>
						<b>Setembro Amarelo</b><br>
						Depoimentos
					</h3>
					<a href="javascript:closeModalSa()" class="btn-close">
						<img src="<?php echo TEMPLATE_URI . '/assets/img/close-black.png'; ?>" alt="Fechar"></img>
					</a>
				</div>
				<div class="col-12">
					<div class="sa-home-video">
						<video id="sa-video" playsinline poster="<?php echo TEMPLATE_URI; ?>/assets/img/cover-video-sa.png?v=2">
							<source src="https://player.vimeo.com/external/595532426.sd.mp4?s=ab2b9eebb3b1c17cd060ebe49d31ed2949472cea&profile_id=164" type="video/mp4">
						</video>
					</div>
				</div>
				<div class="col-12 mt-4">
					<p>
						<b>Setembro Amarelo:</b><br><br>
						A Polen apoia a campanha de prevenção ao suicídio. E não se esqueça: depressão é coisa séria.
						Busque apoio médico. Compartilhe esse vídeo com quem precisa de ajuda.
					</p>
				</div>
				<div class="col-12">
					<button onclick="copyToClipboard('<?php echo get_home_url(); ?>/social/setembro-amarelo')" class="btn btn-outline-light btn-lg btn-block share-link mb-4">Copiar Link</button>
					<button onclick="shareVideo('Setembro Amarelo', '<?php echo get_home_url(); ?>/social/setembro-amarelo')" class="btn btn-outline-light btn-lg btn-block share-link mb-4">Compartilhar</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		polVideoTag("#sa-video");

		function closeModalSa() {
			const video = document.getElementById("sa-video");
			video.pause();
			video.currentTime = 0;
			document.getElementById("sa-modal").classList.remove("d-block");
		}
	</script>
<?php
}

function sa_get_home_banner($title, $description, $link, $images = array("mobile" => "", "desktop" => ""))
{
?>
	<div class="row mt-4">
		<div class="col-12">
			<div class="va-banner">
				<img class="image mobile-img" src="<?php echo $images['mobile']; ?>" alt="<?php echo $title; ?>" />
				<img class="image desktop-img" src="<?php echo $images['desktop']; ?>" alt="<?php echo $title; ?>" />
				<div class="content">
					<div class="row">
						<div class="col-12 col-md-6">
							<h2><?php echo $title; ?></h2>
							<p class="mt-3"><?php echo $description; ?><br></p>
							<a href="/social/setembro-amarelo" class="btn btn-primary btn-md button-yellow">
								<span class="mr-1">Conheça</span>
								<?php Icon_Class::polen_icon_chevron_right(); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
