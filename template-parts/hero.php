<?php
$image   = get_field( 'hero_image' );
$block_1 = get_field( 'hero_block_1' );
$block_2 = get_field( 'hero_block_2' );
$corner  = get_field( 'hero_block_add' );
?>
<section class="hero">

    <?php if( $image ) { ?>
        <div class="hero_image_wrapper">
            <img src="<?= esc_url( $image['url'] ); ?>" 
                 alt="<?= esc_attr( $image['alt'] ); ?>" 
                 class="hero_image">
        </div>
    <?php } ?>

    <div class="hero_content">
        <h1 class="hero_title"><?php the_field('hero_title'); ?></h1>

        <div class="hero_text">
            <?php the_field('hero_text'); ?>
        </div>

        <div class="hero_blocks">
            <?php foreach( [$block_1, $block_2] as $block ) { 
                if( empty( $block['title'] ) ) continue; ?>
                <div class="hero_block">
                    <span class="hero_block_label"><?= esc_html( $block['label'] ); ?></span>
                    <h3 class="hero_block_title"><?= esc_html( $block['title'] ); ?></h3>
                    <div class="hero_block_text"><?= wp_kses_post( $block['text'] ); ?></div>
                    
                    <div class="hero_block_buttons">
                        <?php foreach( ['button_primary', 'button_secondary'] as $btn_key ) {
                            if( empty( $block[$btn_key]['url'] ) ) continue; ?>
                            <a href="<?= esc_url( $block[$btn_key]['url'] ); ?>" 
                               class="hero_btn <?= $btn_key === 'button_primary' ? 'hero_btn_primary' : 'hero_btn_secondary'; ?>"
                               <?= $block[ $btn_key ]['target'] ? 'target="_blank"' : ''; ?>>
                                <?= esc_html( $block[ $btn_key ]['title'] ); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if( $corner ) { ?>
            <div class="hero_corner">
                <?= wp_kses_post( $corner ); ?>
            </div>
        <?php } ?>
    </div>
</section>