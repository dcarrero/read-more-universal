<?php
/**
 * Plugin Name: Read More Universal
 * Plugin URI: https://github.com/dcarrero/read-more-universal
 * Description: Universal "Read More" system that automatically adapts to Twenty Twenty-Five, Astra, Elementor and other popular themes.
 * Version: 1.1.2
 * Author: David Carrero Fernández-Baillo
 * Author URI: https://carrero.es
 * License: GPL v2 or later
 * Text Domain: read-more-universal
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.7
 * Requires PHP: 7.4
 */

// Prevenir acceso directo
if (!defined('ABSPATH')) {
    exit;
}

class ReadMoreUniversal {
    
    private $min_characters = 250;
    private $theme_name = '';
    private $theme_selectors = array();
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_footer', array($this, 'add_read_more_functionality'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('read-more-universal', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function init() {
        // Detectar tema actual
        $this->detect_theme();
        // Cargar configuración
        $this->min_characters = get_option('rmu_min_characters', 250);
    }
    
    private function detect_theme() {
        $theme = wp_get_theme();
        $theme_name = strtolower($theme->get('Name'));
        $template = get_template();
        
        // Detectar si Elementor está activo
        $elementor_active = is_plugin_active('elementor/elementor.php') || defined('ELEMENTOR_VERSION');
        
        // Detectar tema y configurar selectores específicos
        if (strpos($theme_name, 'astra') !== false || $template === 'astra') {
            $this->theme_name = 'astra';
            if ($elementor_active) {
                $this->theme_name = 'astra-elementor';
                $this->theme_selectors = array(
                    '.elementor-widget-theme-post-content .elementor-widget-container',
                    '.elementor-post-content',
                    '.elementor-widget-text-editor',
                    '.elementor-text-editor',
                    '.elementor .entry-content',
                    '.ast-article-post .entry-content',
                    '.entry-content',
                    'article .entry-content'
                );
            } else {
                $this->theme_selectors = array(
                    '.ast-article-post .entry-content',
                    '.single-post .entry-content',
                    '.ast-article-single .entry-content',
                    '.entry-content',
                    'article .entry-content',
                    '.post-content',
                    '.ast-container .entry-content'
                );
            }
        } elseif (strpos($theme_name, 'twenty twenty-five') !== false || $template === 'twentytwentyfive') {
            $this->theme_name = 'twentytwentyfive';
            $this->theme_selectors = array(
                '.wp-block-post-content',
                '.entry-content',
                '.post-content'
            );
        } elseif (strpos($theme_name, 'twenty twenty-four') !== false || $template === 'twentytwentyfour') {
            $this->theme_name = 'twentytwentyfour';
            $this->theme_selectors = array(
                '.wp-block-post-content',
                '.entry-content'
            );
        } elseif (strpos($theme_name, 'twenty twenty-three') !== false || $template === 'twentytwentythree') {
            $this->theme_name = 'twentytwentythree';
            $this->theme_selectors = array(
                '.wp-block-post-content',
                '.entry-content'
            );
        } elseif (strpos($theme_name, 'twenty') !== false) {
            $this->theme_name = 'twenty-default';
            $this->theme_selectors = array(
                '.entry-content',
                '.post-content',
                'article .entry-content'
            );
        } elseif (strpos($theme_name, 'elementor') !== false || $template === 'hello-elementor' || $elementor_active) {
            $this->theme_name = 'elementor';
            $this->theme_selectors = array(
                '.elementor-widget-theme-post-content .elementor-widget-container',
                '.elementor-post-content',
                '.elementor-widget-text-editor',
                '.elementor-text-editor .elementor-widget-container',
                '.elementor .entry-content',
                '.entry-content',
                '.post-content'
            );
        } elseif (strpos($theme_name, 'generatepress') !== false || $template === 'generatepress') {
            $this->theme_name = 'generatepress';
            $this->theme_selectors = array(
                '.entry-content',
                'article .entry-content'
            );
        } elseif (strpos($theme_name, 'oceanwp') !== false || $template === 'oceanwp') {
            $this->theme_name = 'oceanwp';
            $this->theme_selectors = array(
                '.entry-content',
                '.single-post .entry-content'
            );
        } else {
            // Tema genérico - usar selectores universales MÁS AMPLIOS
            $this->theme_name = 'generic';
            $this->theme_selectors = array(
                // Elementor selectors
                '.elementor-widget-theme-post-content .elementor-widget-container',
                '.elementor-post-content',
                '.elementor-widget-text-editor',
                '.elementor-text-editor',
                // WordPress selectors
                '.entry-content',
                '.post-content',
                '.wp-block-post-content',
                'article .entry-content',
                '.content-area .entry-content',
                // Generic selectors
                'main .content',
                '.main-content',
                '.post-body',
                '.article-content',
                // Fallback selectors
                '[class*="entry-content"]',
                '[class*="post-content"]',
                '[class*="content"]'
            );
        }
    }
    
    public function add_read_more_functionality() {
        if (!is_single()) return;
        
        global $post;
        
        // Verificar longitud del contenido
        $content = get_the_content();
        $text_content = wp_strip_all_tags($content);
        $text_length = strlen($text_content);
        
        if ($text_length < $this->min_characters) {
            return;
        }
        
        $this->output_styles();
        $this->output_script();
        
        // Agregar funcionalidad específica para Astra
        if ($this->theme_name === 'astra' || $this->theme_name === 'astra-elementor') {
            $this->add_astra_specific_functionality();
        }
    }
    
    private function add_astra_specific_functionality() {
        // Eliminar funcionalidad específica de Astra que causa problemas
        // El script principal ya debería manejar Astra correctamente
        ?>
        <style>
        /* Asegurar que solo haya un wrapper visible */
        .rmu-wrapper:not([data-rmu-id="main-content"]) {
            display: none !important;
        }
        </style>
        <?php
    }
    
    private function output_styles() {
        $theme_colors = $this->get_theme_colors();
        ?>
        <style id="read-more-universal-styles">
        .rmu-wrapper {
            position: relative;
        }

        .rmu-content.truncated {
            max-height: <?php echo esc_attr(get_option('rmu_max_height', '250')); ?>px;
            overflow: hidden;
            position: relative;
        }

        .rmu-content.truncated::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(transparent, <?php echo esc_attr($theme_colors['background']); ?>);
            pointer-events: none;
        }

        .rmu-button-container {
            text-align: center;
            margin: 1.5rem 0;
        }

        .rmu-button {
            background: <?php echo esc_attr($theme_colors['primary']); ?>;
            color: <?php echo esc_attr($theme_colors['text']); ?>;
            border: none;
            padding: 1rem 2rem;
            border-radius: <?php echo esc_attr(get_option('rmu_border_radius', '25')); ?>px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,123,186,0.3);
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .rmu-button:hover {
            background: <?php echo esc_attr($theme_colors['secondary']); ?>;
            transform: translateY(-2px);
        }

        .rmu-content.expanded {
            max-height: none !important;
            overflow: visible !important;
        }

        .rmu-content.expanded::after {
            display: none !important;
        }

        .rmu-button-container.hidden {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .rmu-content.truncated {
                max-height: 200px;
            }
            
            .rmu-button {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        /* Específico para cada tema */
        <?php if ($this->theme_name === 'astra'): ?>
        .ast-article-single .rmu-wrapper,
        .ast-separate-container .rmu-wrapper {
            margin-bottom: 1.5em;
        }
        <?php elseif ($this->theme_name === 'elementor'): ?>
        .elementor-widget-theme-post-content .rmu-wrapper {
            margin-bottom: 1.5em;
        }
        <?php endif; ?>
        </style>
        <?php
    }
    
    private function get_theme_colors() {
        $defaults = array(
            'primary' => '#007cba',
            'secondary' => '#005a87',
            'text' => 'white',
            'background' => '#ffffff'
        );
        
        // Intentar obtener colores del tema
        if ($this->theme_name === 'astra' && function_exists('astra_get_option')) {
            $primary = astra_get_option('theme-color');
            if ($primary) $defaults['primary'] = $primary;
        }
        
        // Permitir personalización desde configuración
        $defaults['primary'] = get_option('rmu_button_color', $defaults['primary']);
        $defaults['text'] = get_option('rmu_text_color', $defaults['text']);
        
        return $defaults;
    }
    
    private function output_script() {
        $selectors_json = wp_json_encode($this->theme_selectors);
        ?>
        <script id="read-more-universal-script">
        (function() {
            var selectors = <?php echo $selectors_json; // Already escaped by wp_json_encode ?>;
            var debugMode = <?php echo get_option('rmu_debug_mode', 0) ? 'true' : 'false'; ?>;
            
            function log(message) {
                if (debugMode) console.log('[Read More Universal] ' + message);
            }
            
            function findPostContent() {
                log('Buscando contenido del post...');
                log('Tema detectado: <?php echo esc_js($this->theme_name); ?>');
                
                // Primera pasada: selectores específicos del tema
                for (var i = 0; i < selectors.length; i++) {
                    var elements = document.querySelectorAll(selectors[i]);
                    log('Probando selector: ' + selectors[i] + ' - Encontrados: ' + elements.length);
                    
                    for (var j = 0; j < elements.length; j++) {
                        var element = elements[j];
                        if (isValidContent(element)) {
                            log('✅ Contenido válido encontrado con: ' + selectors[i]);
                            return element;
                        }
                    }
                }
                
                log('🔄 Búsqueda ampliada para Elementor/Page Builders...');
                
                // Segunda pasada: Selectores específicos para Elementor y page builders
                var advancedSelectors = [
                    // Elementor específicos
                    '.elementor-widget-theme-post-content .elementor-widget-container',
                    '.elementor-post-content',
                    '.elementor-widget-text-editor .elementor-widget-container',
                    '.elementor-text-editor .elementor-widget-container div',
                    '.elementor .elementor-widget-container p',
                    '.elementor-section .elementor-widget-container',
                    
                    // Gutenberg y WordPress
                    '.wp-block-post-content p',
                    '.entry-content > p',
                    '.post-content > p',
                    
                    // Genéricos amplios
                    'main .content',
                    '.main-content',
                    '.post-body',
                    '.article-content',
                    '.content-wrapper',
                    
                    // Selectores de texto directo
                    'article p',
                    '.post p',
                    'main p',
                    '.content p'
                ];
                
                for (var k = 0; k < advancedSelectors.length; k++) {
                    var elements = document.querySelectorAll(advancedSelectors[k]);
                    log('Probando selector avanzado: ' + advancedSelectors[k] + ' - Encontrados: ' + elements.length);
                    
                    // Para selectores de párrafos, buscar el contenedor padre
                    for (var l = 0; l < elements.length; l++) {
                        var element = elements[l];
                        var container = element.tagName === 'P' ? element.parentElement : element;
                        
                        if (isValidContent(container)) {
                            log('✅ Contenido válido encontrado con selector avanzado: ' + advancedSelectors[k]);
                            return container;
                        }
                    }
                }
                
                log('🔍 Última búsqueda: elementos con mucho texto...');
                
                // Tercera pasada: buscar cualquier elemento con suficiente texto
                var allElements = document.querySelectorAll('div, section, article, main');
                for (var m = 0; m < allElements.length; m++) {
                    var element = allElements[m];
                    if (element.textContent.length > 300 && 
                        !element.classList.contains('rmu-processed') &&
                        !element.querySelector('.rmu-wrapper')) {
                        log('✅ Contenido encontrado por longitud de texto: ' + element.tagName + '.' + element.className);
                        return element;
                    }
                }
                
                log('❌ No se encontró contenido del post');
                return null;
            }
            
            function isValidContent(element) {
                if (!element) return false;
                
                var textLength = element.textContent.trim().length;
                var hasEnoughText = textLength > 100;
                var notProcessed = !element.classList.contains('rmu-processed');
                var notWrapper = !element.querySelector('.rmu-wrapper');
                var notButton = !element.classList.contains('rmu-button-container');
                var notNavigation = !element.closest('nav, .nav, .navigation, .menu');
                var notSidebar = !element.closest('aside, .sidebar, .widget');
                var notFooter = !element.closest('footer');
                var notHeader = !element.closest('header');
                
                var isValid = hasEnoughText && notProcessed && notWrapper && notButton && 
                             notNavigation && notSidebar && notFooter && notHeader;
                
                if (debugMode && textLength > 50) {
                    log('Validando elemento: ' + element.tagName + '.' + element.className + 
                        ' - Texto: ' + textLength + ' chars - Válido: ' + isValid);
                }
                
                return isValid;
            }

            function initReadMore() {
                log('🚀 Iniciando Read More Universal');
                
                var postContent = findPostContent();
                
                if (!postContent) {
                    log('⏳ Primera búsqueda falló, esperando carga de Elementor...');
                    // Para Elementor y page builders que cargan contenido dinámicamente
                    setTimeout(function() {
                        log('🔄 Segundo intento después de 1s...');
                        var delayedContent = findPostContent();
                        if (delayedContent) {
                            processContent(delayedContent);
                        } else {
                            // Último intento después de 3 segundos
                            setTimeout(function() {
                                log('🔄 Último intento después de 3s...');
                                var finalContent = findPostContent();
                                if (finalContent) {
                                    processContent(finalContent);
                                } else {
                                    log('❌ No se pudo encontrar contenido después de todos los intentos');
                                }
                            }, 2000);
                        }
                    }, 1000);
                    return;
                }
                
                processContent(postContent);
            }
            
            function processContent(postContent) {
                if (postContent.classList.contains('rmu-processed')) {
                    log('⚠️ Ya procesado anteriormente');
                    return;
                }
                
                log('⚙️ Procesando contenido...');
                log('📏 Contenido encontrado: ' + postContent.textContent.length + ' caracteres');
                
                postContent.classList.add('rmu-processed');
                
                var wrapper = document.createElement('div');
                wrapper.className = 'rmu-wrapper';
                
                postContent.parentNode.insertBefore(wrapper, postContent);
                wrapper.appendChild(postContent);
                
                postContent.classList.add('rmu-content', 'truncated');
                
                var buttonContainer = document.createElement('div');
                buttonContainer.className = 'rmu-button-container';
                buttonContainer.innerHTML = '<button class="rmu-button" onclick="RMU_expandContent()"><?php echo esc_js(get_option('rmu_button_text', $this->get_default_button_text())); ?></button>';
                
                wrapper.appendChild(buttonContainer);
                
                log('✅ Read More Universal aplicado correctamente');
            }
            
            // Detectar si Elementor está activo
            function isElementorActive() {
                return document.querySelector('.elementor') !== null || 
                       document.querySelector('[class*="elementor"]') !== null ||
                       window.elementorFrontend !== undefined;
            }
            
            // Esperar a que Elementor termine de cargar
            function waitForElementor(callback) {
                if (window.elementorFrontend) {
                    window.elementorFrontend.hooks.addAction('frontend/element_ready/global', callback);
                } else {
                    callback();
                }
            }
            
            // Función global para expandir
            window.RMU_expandContent = function() {
                log('Expandiendo contenido...');
                var content = document.querySelector('.rmu-content');
                var button = document.querySelector('.rmu-button-container');
                
                if (content && button) {
                    content.classList.remove('truncated');
                    content.classList.add('expanded');
                    button.classList.add('hidden');
                    
                    log('Contenido expandido');
                    
                    // Analytics
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'engagement', {
                            event_category: 'Content',
                            event_label: 'Read More Clicked',
                            custom_map: {metric1: 1}
                        });
                    }
                    
                    if (typeof ga !== 'undefined') {
                        ga('send', 'event', 'Content', 'Read More', 'Article Expanded');
                    }
                    
                    if (typeof fbq !== 'undefined') {
                        fbq('trackCustom', 'ReadMore', {
                            content_type: 'article',
                            theme: '<?php echo esc_js($this->theme_name); ?>'
                        });
                    }
                } else {
                    log('No se pudo expandir - elementos no encontrados');
                }
            };
            
            // Múltiples estrategias de inicialización
            log('🎬 Iniciando sistema de detección...');
            
            // Estrategia 1: DOM básico cargado
            document.addEventListener('DOMContentLoaded', function() {
                log('📄 DOM cargado');
                if (isElementorActive()) {
                    log('🎨 Elementor detectado, esperando...');
                    waitForElementor(initReadMore);
                } else {
                    initReadMore();
                }
            });
            
            // Estrategia 2: Intentos con delay (para page builders)
            setTimeout(function() {
                log('⏰ Intento 500ms');
                initReadMore();
            }, 500);
            
            setTimeout(function() {
                log('⏰ Intento 1500ms');
                initReadMore();
            }, 1500);
            
            setTimeout(function() {
                log('⏰ Intento 3000ms');
                initReadMore();
            }, 3000);
            
            // Estrategia 3: Window completamente cargado
            window.addEventListener('load', function() {
                log('🏁 Window load completo');
                setTimeout(initReadMore, 500);
            });
            
            // Estrategia 4: Observer para cambios dinámicos (Elementor, etc.)
            if (window.MutationObserver) {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length > 0) {
                            // Esperar un poco y luego intentar
                            setTimeout(initReadMore, 100);
                        }
                    });
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
                
                // Desactivar observer después de 10 segundos
                setTimeout(function() {
                    observer.disconnect();
                }, 10000);
            }
        })();
        </script>
        <?php
    }
    
    // Panel de administración
    public function add_admin_menu() {
        add_options_page(
            __('Read More Universal', 'read-more-universal'),
            __('Read More Universal', 'read-more-universal'),
            'manage_options',
            'read-more-universal',
            array($this, 'admin_page')
        );
    }
    
    public function settings_init() {
        register_setting('rmu_settings', 'rmu_min_characters', array(
            'sanitize_callback' => 'absint',
            'default' => 250
        ));
        register_setting('rmu_settings', 'rmu_max_height', array(
            'sanitize_callback' => 'absint',
            'default' => 250
        ));
        register_setting('rmu_settings', 'rmu_button_text', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->get_default_button_text()
        ));
        register_setting('rmu_settings', 'rmu_button_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#007cba'
        ));
        register_setting('rmu_settings', 'rmu_text_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff'
        ));
        register_setting('rmu_settings', 'rmu_border_radius', array(
            'sanitize_callback' => 'absint',
            'default' => 25
        ));
        register_setting('rmu_settings', 'rmu_debug_mode', array(
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
            'default' => 0
        ));
    }
    
    public function sanitize_checkbox($input) {
        return $input ? 1 : 0;
    }
    
    public function admin_page() {
        // Verify nonce for security
        if (isset($_POST['submit'])) {
            if (!isset($_POST['rmu_settings_nonce']) || !wp_verify_nonce($_POST['rmu_settings_nonce'], 'rmu_settings_action')) {
                wp_die(__('Security check failed.', 'read-more-universal'));
            }
            
            if (current_user_can('manage_options')) {
                update_option('rmu_min_characters', isset($_POST['rmu_min_characters']) ? absint($_POST['rmu_min_characters']) : 250);
                update_option('rmu_max_height', isset($_POST['rmu_max_height']) ? absint($_POST['rmu_max_height']) : 250);
                update_option('rmu_button_text', isset($_POST['rmu_button_text']) ? sanitize_text_field(wp_unslash($_POST['rmu_button_text'])) : $this->get_default_button_text());
                update_option('rmu_button_color', isset($_POST['rmu_button_color']) ? sanitize_hex_color(wp_unslash($_POST['rmu_button_color'])) : '#007cba');
                update_option('rmu_text_color', isset($_POST['rmu_text_color']) ? sanitize_hex_color(wp_unslash($_POST['rmu_text_color'])) : '#ffffff');
                update_option('rmu_border_radius', isset($_POST['rmu_border_radius']) ? absint($_POST['rmu_border_radius']) : 25);
                update_option('rmu_debug_mode', isset($_POST['rmu_debug_mode']) ? 1 : 0);
                echo '<div class="notice notice-success"><p>' . esc_html__('Settings saved.', 'read-more-universal') . '</p></div>';
            }
        }
        
        $min_chars = get_option('rmu_min_characters', 250);
        $max_height = get_option('rmu_max_height', 250);
        $button_text = get_option('rmu_button_text', $this->get_default_button_text());
        $button_color = get_option('rmu_button_color', '#007cba');
        $text_color = get_option('rmu_text_color', '#ffffff');
        $border_radius = get_option('rmu_border_radius', 25);
        $debug_mode = get_option('rmu_debug_mode', 0);
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Read More Universal - Settings', 'read-more-universal'); ?></h1>
            
            <div style="background: #f1f1f1; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <!-- translators: %s is the theme name -->
                <h3><?php printf(esc_html__('🎯 Detected theme: %s', 'read-more-universal'), '<strong>' . esc_html(ucfirst($this->theme_name)) . '</strong>'); ?></h3>
                <p><?php esc_html_e('The plugin has been automatically configured for your theme.', 'read-more-universal'); ?></p>
            </div>
            
            <form method="post">
                <?php wp_nonce_field('rmu_settings_action', 'rmu_settings_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e('Minimum characters', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_min_characters" value="<?php echo esc_attr($min_chars); ?>" min="50" max="1000" />
                            <p class="description"><?php esc_html_e('Minimum number of characters to show the "Read more" button', 'read-more-universal'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Max height (px)', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_max_height" value="<?php echo esc_attr($max_height); ?>" min="100" max="500" />
                            <p class="description"><?php esc_html_e('Height in pixels of the truncated content', 'read-more-universal'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Button text', 'read-more-universal'); ?></th>
                        <td>
                            <input type="text" name="rmu_button_text" value="<?php echo esc_attr($button_text); ?>" style="width: 300px;" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Button color', 'read-more-universal'); ?></th>
                        <td>
                            <input type="color" name="rmu_button_color" value="<?php echo esc_attr($button_color); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Text color', 'read-more-universal'); ?></th>
                        <td>
                            <input type="color" name="rmu_text_color" value="<?php echo esc_attr($text_color); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Border radius (px)', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_border_radius" value="<?php echo esc_attr($border_radius); ?>" min="0" max="50" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Debug mode', 'read-more-universal'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="rmu_debug_mode" value="1" <?php checked($debug_mode, 1); ?> />
                                <?php esc_html_e('Enable debug messages in browser console', 'read-more-universal'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <div style="background: #fff; padding: 20px; border: 1px solid #ccc; margin-top: 30px;">
                <h3><?php esc_html_e('📋 Theme information', 'read-more-universal'); ?></h3>
                <p><strong><?php esc_html_e('Current theme:', 'read-more-universal'); ?></strong> <?php echo esc_html(wp_get_theme()->get('Name')); ?></p>
                <p><strong><?php esc_html_e('Template:', 'read-more-universal'); ?></strong> <?php echo esc_html(get_template()); ?></p>
                <p><strong><?php esc_html_e('CSS selectors used:', 'read-more-universal'); ?></strong></p>
                <ul>
                    <?php foreach ($this->theme_selectors as $selector): ?>
                        <li><code><?php echo esc_html($selector); ?></code></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }
    
    private function get_default_button_text() {
        // Default English text - translations will be handled by WordPress i18n
        return __('📖 Read full article', 'read-more-universal');
    }
}

// Inicializar el plugin
new ReadMoreUniversal();
?>
