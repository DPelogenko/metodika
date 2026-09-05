<?php
// Подключение стилей и скриптов(?)
function metodika_enqueue_scripts() {
    wp_enqueue_style( 'main', get_stylesheet_uri(), [], filemtime( get_stylesheet_directory() . '/style.css' ) );
    wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'metodika_enqueue_scripts' );

// Настройка темы
function metodika_setup_theme() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['script', 'style']);

    add_theme_support('custom-logo', [
        'height'      => 168,
        'width'       => 36,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus( array(
        'primary' => __( 'Главное меню', 'metodika' ),
    ) );

    add_image_size( 'mobile', 767, 0, false ); // до 767
    add_image_size( 'tablet', 1439, 0, false ); // 768-1439
    add_image_size( 'desktop', 1920, 0, false ); // 1440+
}
add_action( 'after_setup_theme', 'metodika_setup_theme' );

// Проверка ACF
function metodika_require_acf_notice() {
    $acf_free = 'advanced-custom-fields/acf.php';
    $acf_pro  = 'advanced-custom-fields-pro/acf.php';

    if ( ! function_exists( 'is_plugin_active' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $is_acf_active = is_plugin_active( $acf_free ) || is_plugin_active( $acf_pro );

    if ( ! $is_acf_active ) {
        ?>
        <div class="notice notice-error">
            <p><?php _e( 'Необходимо установить плагин', 'metodika' ); ?>
                <a href="<?= self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=advanced-custom-fields' ); ?>">Advanced Custom Fields</a>
            </p>
        </div>
        <?php
    }
}
add_action( 'admin_init', 'metodika_require_acf_notice' );

// Добавляем Настройки темы с бесплатной версией ACF
// Добавить страницу в меню админки
add_action( 'admin_menu', function () {
    if ( !function_exists( 'acf_form' ) ) {
        return;
    }

    global $menu;
    $menu['2.1'] = ['', 'read', 'separator-custom-1', '', 'wp-menu-separator'];
  
    $hook = add_menu_page(
        __( 'Настройки темы', 'metodika' ),
        __( 'Настройки темы', 'metodika' ),
        'edit_theme_options',
        'metodika-theme-options',
        'metodika_render_theme_options_page',
        'dashicons-hammer',
        2.2
    );

    add_action( "load-{$hook}", function () {
        acf_form_head();
    } );
} );
// Рендер страницы Настроек темы - используем acf_form()
function metodika_render_theme_options_page() {
    echo '<div class="wrap"><h1>' . __( 'Настройки темы', 'metodika' ) . '</h1>';
    acf_form( [
        'post_id'        => 'options',
        'html_after_fields'  => '<br>',
        'submit_value'   => __( 'Сохранить', 'metodika' ),
        'updated_message'=> __( 'Сохранено', 'metodika' ),
        'uploader'       => 'wp',
    ] );
    echo '</div><br>';
}
// Добавить правило локаций для ACF, тип
add_filter( 'acf/location/rule_types', function ( $types ) {
    $types['Тема']['metodika_options'] = __( 'Страница настроек', 'metodika' );
    return $types;
} );
// Добавить правило локаций для ACF, значение
add_filter( 'acf/location/rule_values/metodika_options', function () {
    return ['metodika_options' => __( 'Настройки темы', 'metodika' )];
} );
// Добавить правило локаций для ACF, проверка
add_filter( 'acf/location/rule_match/metodika_options', function ( $match, $rule, $screen ) {
    $on = ( !empty( $screen['post_id'] ) && (string) $screen['post_id'] === 'options' )
        || ( ( $_GET['page'] ?? '' ) === 'metodika-theme-options' );

    $result = $on && $rule['value'] === 'metodika_options';
    return $rule['operator'] === '!=' ? !$result : $result;
}, 10, 3 );

// Кастомный walker для меню с выпадающими подменю и описаниями
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub_menu">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = !empty($args->walker->has_children);
        
        $output .= '<li class="menu_item' . ($has_children ? ' menu_item_has_children' : '') . ' ' . ( count( $item->classes ) ? implode( ' ', $item->classes ) : '' ) . '">';
        
        if ($has_children) {
            $output .= '<button class="menu_link menu_link_has_children">';
            $output .= esc_html($item->title);
            if (!empty($item->description)) {
                $output .= '<span class="menu_description">' . esc_html($item->description) . '</span>';
            }
            $output .= '</button>';
        } else {
            $output .= '<a href="' . esc_url($item->url) . '" class="menu_link" ' . ($item->target ? 'target="' . $item->target . '"' : '') . '>';
            $output .= esc_html($item->title);
            if (!empty($item->description)) {
                $output .= '<span class="menu_description">' . esc_html($item->description) . '</span>';
            }
            $output .= '</a>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}