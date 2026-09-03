<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
    <div class="container">
        
        <div class="header_grid">
            <div class="header_logo">
                <?php if (function_exists('the_custom_logo')) the_custom_logo(); ?>
            </div>

            <div class="header_messengers">
                <?php 
                $messengers = get_field('theme_messengers', 'option');
                if ($messengers) : 
                    foreach (['tg', 'max'] as $key) :
                        if (!empty($messengers[$key])) : 
                            $icon = $messengers[$key . '_icon'];
                ?>
                        <a href="<?php echo esc_url($messengers[$key]); ?>" target="_blank">
                            <?php if ($icon) : ?>
                                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($key); ?>">
                            <?php endif; ?>
                        </a>
                <?php 
                        endif;
                    endforeach;
                endif; 
                ?>
            </div>

            <div class="header_contacts">
                <?php 
                $contacts = get_field('theme_contacts', 'option');
                if ($contacts) : 
                ?>
                    <a href="tel:<?php echo esc_attr($contacts['phone']); ?>">
                        <?php echo esc_html($contacts['phone']); ?>
                    </a>
                    <span><?php echo esc_html($contacts['hours']); ?></span>
                <?php endif; ?>
            </div>

            <div class="header_consult">
                <a href="#" class="consult_btn">Консультация</a>
            </div>

            <nav class="header_nav">
                <?php wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'header_menu',
                    'container' => false,
                    'fallback_cb' => true,
                    'walker' => new Custom_Walker_Nav_Menu()
                )); ?>
            </nav>

            <div class="header_rating">
                <?php 
                $rating = get_field('theme_rating', 'option');
                if ($rating) : 
                ?>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?php echo $i <= $rating['stars'] ? 'active' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
                <span><?php echo esc_html($rating['text']); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Мобильная версия -->
        <div class="header_mobile">
            <div class="header_logo">
                <?php if (function_exists('the_custom_logo')) the_custom_logo(); ?>
            </div>
            <div class="header_mobile_buttons">
                <a href="tel:<?php echo esc_attr($contacts['phone'] ?? ''); ?>" class="mobile_phone">📞</a>
                <button class="burger_menu" aria-label="Меню">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>

        <!-- Мобильное меню (вынесено отдельно) -->
        <div class="header_nav_mobile">
            <?php wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class' => 'header_menu_mobile',
                'container' => false,
                'fallback_cb' => true,
                'walker' => new Custom_Walker_Nav_Menu()
            )); ?>
        </div>

    </div>
</header>