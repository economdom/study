# Функции темы

Файл *functions.php* позволяет вызывать [хуки WordPress](../plugin/hooks.md) и писать кастомный PHP код для текущей темы, чтобы, например, добавить/изменить настройки или включить/расширить возможности темы. По сути, файл *functions.php* ведёт себя как WordPress плагин, который идёт вместе с темой.

> Если вы хотите создавать функционал, который должен работать независимо от внешнего оформления сайта, тогда этот программный код лучше вынести в плагин.

Дочерняя тема также может иметь свой собственный файл *functions.php* при этом вам не стоит беспокоится о том что ваш код будет затёрт когда обновится основная тема.

Хотя файл *functions.php* дочерней темы загружается WordPress прямо перед файлом *functions.php* родительской темы, он не отменяет, а дополняет его. Также файл *functions.php* загружается после загрузки всех плагинов.

Ваш файл *functions.php* может выглядеть таким образом:

*functions.php*

```
/**
 * Functions and definitions
 */

if ( ! function_exists( 'myfirsttheme_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function myfirsttheme_setup() {
     
        /**
         * Make theme available for translation. Translations can be placed in the /languages/ directory.
         */
        load_theme_textdomain( 'myfirsttheme', get_template_directory() . '/languages' );
     
        /**
         * Add default posts and comments RSS feed links to <head>.
         */
        add_theme_support( 'automatic-feed-links' );
     
        /**
         * Enable support for post thumbnails and featured images.
         */
        add_theme_support( 'post-thumbnails' );
     
        /**
         * Add support for two custom navigation menus.
         */
        register_nav_menus( array(
            'primary'   => __( 'Primary Menu', 'myfirsttheme' ),
            'secondary' => __('Secondary Menu', 'myfirsttheme' )
        ) );
     
        /**
         * Enable support for the following post formats: aside, gallery, quote, image, and video
         */
        add_theme_support( 'post-formats', array ( 'aside', 'gallery', 'quote', 'image', 'video' ) );
    }
endif;

add_action( 'after_setup_theme', 'myfirsttheme_setup' );
```

Данный файл может подключать дополнительные PHP файлы, на тот случай когда программного кода много и вам нужно сделать возможности темы более модульными.