<?php

use \Polen\Includes\Cart\Polen_Cart_Item_Factory;

if (isset($_REQUEST['order_id']) && !empty($_REQUEST['order_id'])) {
    $min = get_assets_folder();
    wp_enqueue_script('polen-upload-video-tus', TEMPLATE_URI . '/assets/js/' . $min . 'tus.js', array(), _S_VERSION, true);
    wp_enqueue_script('polen-upload-video', TEMPLATE_URI . '/assets/js/' . $min . 'upload-video.js', array("jquery"), _S_VERSION, true);
    do_action('polen_before_upload_video');

    $order_id = filter_input(INPUT_GET, 'order_id');
    $order = wc_get_order($order_id);
    $polen_order = Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
    $instruction = $polen_order->get_instructions_to_video();
?>

    <script>
        let instruction = <?php echo json_encode($instruction); ?>;
        jQuery(document).ready(function ($) {
            $("#video-instructions").html(replaceLineBreakString(instruction.toString()));
        });
    </script>

    <main id="primary" class="site-main mt-4">
        <header class="entry-header">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            <button class="btn btn-outline-light btn-lg btn-block mt-4 btn-instruction" data-toggle="collapse" data-target="#collapseInstruction" aria-expanded="false" aria-controls="collapseExample">Ver instruções</button>
        </header>
        <div class="collapse mt-3" id="collapseInstruction">
            <!-- Início -->
            <div class="col-12 talent-order-modal">
                <div class="row d-flex align-items-center">
                    <?php
                    if (!empty($polen_order->get_offered_by())) : ?>
                        <div class="col-6">
                            <p class="p">Vídeo de</p>
                            <span class="value small"><?= $polen_order->get_offered_by(); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="col-6 mt-3">
                        <p class="p">Para</p>
                        <span class="value small"><?= $polen_order->get_name_to_video(); ?></span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <p class="p">Ocasião</p>
                        <span class="value small"><?= $polen_order->get_video_category(); ?></span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <p class="p">Instruções</p>
                        <p id="video-instructions" class="text"></p>
                    </div>
                </div>
                <!-- Fim -->
            </div>
        </div>
        <div class="row">
            <div class="col-12 my-4">
                <div class="pre-record-message p-3">
                    <div class="row">
                    <div class="col-md-12 d-flex align-items-center mb-3">
                        <div class="ico mr-2"><img src="<?php echo TEMPLATE_URI; ?>/assets/img/emoji/info.png" alt="Emoji Festa"></div>
                        <div class="text">
                            Regras importantes:
                        </div>
                    </div>
                    <div class="col-md-12">
                        <p class="p">
                            ● Use o celular na posição vertical (em pé) para gravar os vídeos.<br>
                            ● Não é permitido cantar/tocar músicas ou citar textos/poesias.
                        </p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <article class="box-round px-3 pb-2 pt-4">
            <div class="row">
                <div class="col-12">
                    <div class="py-3 text-center box-video">
                        <div id="content-info" class="content-info show">
                            <figure class="image wait show video-rec">
                                <img src="<?php echo TEMPLATE_URI ?>/assets/img/upload-info.png" alt="Gravar vídeo agora" class="correct-margin" />
                            </figure>
                            <figure class="image complete">
                                <img src="<?php echo TEMPLATE_URI ?>/assets/img/upload-complete.png" alt="Gravar vídeo agora" class="correct-margin" />
                            </figure>
                            <p id="info" class="info"></p>
                        </div>
                        <div id="content-upload" class="content-upload">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="my-4 progress-text"><strong id="progress-value">Enviando vídeo 0%</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form id="form-video-upload" method="post" enctype="multipart/form-data">
                        <div class="form-group text-center">
                            <button id="video-rec" class="btn btn-primary btn-lg btn-block video-rec show">Gravar vídeo</button>
                            <div id="video-file-name" class="text-truncate ml-2"></div>
                            <input type="file" class="form-control-file" id="file-video" name="file_data" accept="video/*">
                        </div>
                        <button type="submit" id="video-send" class="send-video btn btn-primary btn-lg btn-block">Enviar</button>
                        <button id="video-rec-again" class="btn btn-outline-light btn-lg btn-block mt-3 video-rec">Não gostei, gravar outro video</button>
                    </form>
                </div>
            </div>
        </article>
    </main><!-- #main -->
<?php
} else {
    echo '<h3 class="text-center">Você precisa selecionar um pedido para fazer o upload do vídeo.</h3>';
}
