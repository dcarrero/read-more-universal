<?php
/**
 * Plugin Name: Read More Universal
 * Plugin URI: https://github.com/dcarrero/read-more-universal
 * Description: Universal "Read More" system that automatically adapts to Twenty Twenty-Five, Astra, Elementor and other popular themes.
 * Version: 1.0.0
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
        
        // Detectar tema y configurar selectores específicos
        if (strpos($theme_name, 'astra') !== false || $template === 'astra') {
            $this->theme_name = 'astra';
            $this->theme_selectors = array(
                '.entry-content',
                '.ast-article-single .entry-content',
                '.single-post .entry-content',
                'article .entry-content'
            );
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
        } elseif (strpos($theme_name, 'elementor') !== false || $template === 'hello-elementor') {
            $this->theme_name = 'elementor';
            $this->theme_selectors = array(
                '.entry-content',
                '.elementor-widget-theme-post-content .entry-content',
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
            // Tema genérico - usar selectores universales
            $this->theme_name = 'generic';
            $this->theme_selectors = array(
                '.entry-content',
                '.post-content',
                '.wp-block-post-content',
                'article .entry-content',
                '.content-area .entry-content',
                '[class*="entry-content"]',
                '[class*="post-content"]'
            );
        }
    }
    
    public function add_read_more_functionality() {
        if (!is_single()) return;
        
        global $post;
        
        // Verificar longitud del contenido
        $content = get_the_content();
        $text_content = strip_tags($content);
        $text_length = strlen($text_content);
        
        if ($text_length < $this->min_characters) {
            return;
        }
        
        $this->output_styles();
        $this->output_script();
    }
    
    private function output_styles() {
        $theme_colors = $this->get_theme_colors();
        ?>
        <style id="read-more-universal-styles">
        .rmu-wrapper {
            position: relative;
        }

        .rmu-content.truncated {
            max-height: <?php echo get_option('rmu_max_height', '250'); ?>px;
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
            background: linear-gradient(transparent, <?php echo $theme_colors['background']; ?>);
            pointer-events: none;
        }

        .rmu-button-container {
            text-align: center;
            margin: 1.5rem 0;
        }

        .rmu-button {
            background: <?php echo $theme_colors['primary']; ?>;
            color: <?php echo $theme_colors['text']; ?>;
            border: none;
            padding: 1rem 2rem;
            border-radius: <?php echo get_option('rmu_border_radius', '25'); ?>px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,123,186,0.3);
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .rmu-button:hover {
            background: <?php echo $theme_colors['secondary']; ?>;
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
        $selectors_json = json_encode($this->theme_selectors);
        ?>
        <script id="read-more-universal-script">
        (function() {
            var selectors = <?php echo $selectors_json; ?>;
            var debugMode = <?php echo get_option('rmu_debug_mode', 0) ? 'true' : 'false'; ?>;
            
            function log(message) {
                if (debugMode) console.log('[Read More Universal] ' + message);
            }
            
            function findPostContent() {
                log('Buscando contenido del post...');
                log('Tema detectado: <?php echo $this->theme_name; ?>');
                
                for (var i = 0; i < selectors.length; i++) {
                    var element = document.querySelector(selectors[i]);
                    if (element && element.children.length > 0) {
                        log('Contenido encontrado con selector: ' + selectors[i]);
                        return element;
                    }
                }
                
                log('No se encontró contenido con selectores específicos, buscando genérico...');
                
                // Búsqueda genérica como respaldo
                var genericSelectors = [
                    'article .entry-content',
                    '.post .entry-content',
                    '.content .entry-content',
                    'main .entry-content'
                ];
                
                for (var j = 0; j < genericSelectors.length; j++) {
                    var element = document.querySelector(genericSelectors[j]);
                    if (element) {
                        log('Contenido encontrado con selector genérico: ' + genericSelectors[j]);
                        return element;
                    }
                }
                
                log('No se encontró contenido del post');
                return null;
            }

            function initReadMore() {
                log('Iniciando Read More Universal');
                
                var postContent = findPostContent();
                
                if (!postContent) {
                    log('No se pudo inicializar - contenido no encontrado');
                    return;
                }
                
                if (postContent.classList.contains('rmu-processed')) {
                    log('Ya procesado anteriormente');
                    return;
                }
                
                log('Procesando contenido...');
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
                
                log('Read More Universal aplicado correctamente');
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
                            theme: '<?php echo $this->theme_name; ?>'
                        });
                    }
                } else {
                    log('No se pudo expandir - elementos no encontrados');
                }
            };
            
            // Múltiples intentos de inicialización
            document.addEventListener('DOMContentLoaded', initReadMore);
            setTimeout(initReadMore, 500);
            window.addEventListener('load', initReadMore);
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
        register_setting('rmu_settings', 'rmu_min_characters');
        register_setting('rmu_settings', 'rmu_max_height');
        register_setting('rmu_settings', 'rmu_button_text');
        register_setting('rmu_settings', 'rmu_button_color');
        register_setting('rmu_settings', 'rmu_text_color');
        register_setting('rmu_settings', 'rmu_border_radius');
        register_setting('rmu_settings', 'rmu_debug_mode');
    }
    
    public function admin_page() {
        if (isset($_POST['submit'])) {
            update_option('rmu_min_characters', intval($_POST['rmu_min_characters']));
            update_option('rmu_max_height', intval($_POST['rmu_max_height']));
            update_option('rmu_button_text', sanitize_text_field($_POST['rmu_button_text']));
            update_option('rmu_button_color', sanitize_hex_color($_POST['rmu_button_color']));
            update_option('rmu_text_color', sanitize_hex_color($_POST['rmu_text_color']));
            update_option('rmu_border_radius', intval($_POST['rmu_border_radius']));
            update_option('rmu_debug_mode', isset($_POST['rmu_debug_mode']) ? 1 : 0);
            echo '<div class="notice notice-success"><p>' . __('Settings saved.', 'read-more-universal') . '</p></div>';
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
            <h1><?php _e('Read More Universal - Settings', 'read-more-universal'); ?></h1>
            
            <div style="background: #f1f1f1; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h3><?php printf(__('🎯 Detected theme: %s', 'read-more-universal'), '<strong>' . ucfirst($this->theme_name) . '</strong>'); ?></h3>
                <p><?php _e('The plugin has been automatically configured for your theme.', 'read-more-universal'); ?></p>
            </div>
            
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th><?php _e('Minimum characters', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_min_characters" value="<?php echo $min_chars; ?>" min="50" max="1000" />
                            <p class="description"><?php _e('Minimum number of characters to show the "Read more" button', 'read-more-universal'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Max height (px)', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_max_height" value="<?php echo $max_height; ?>" min="100" max="500" />
                            <p class="description"><?php _e('Height in pixels of the truncated content', 'read-more-universal'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Button text', 'read-more-universal'); ?></th>
                        <td>
                            <input type="text" name="rmu_button_text" value="<?php echo esc_attr($button_text); ?>" style="width: 300px;" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Button color', 'read-more-universal'); ?></th>
                        <td>
                            <input type="color" name="rmu_button_color" value="<?php echo $button_color; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Text color', 'read-more-universal'); ?></th>
                        <td>
                            <input type="color" name="rmu_text_color" value="<?php echo $text_color; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Border radius (px)', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_border_radius" value="<?php echo $border_radius; ?>" min="0" max="50" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Debug mode', 'read-more-universal'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="rmu_debug_mode" value="1" <?php checked($debug_mode, 1); ?> />
                                <?php _e('Enable debug messages in browser console', 'read-more-universal'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <div style="background: #fff; padding: 20px; border: 1px solid #ccc; margin-top: 30px;">
                <h3><?php _e('📋 Theme information', 'read-more-universal'); ?></h3>
                <p><strong><?php _e('Current theme:', 'read-more-universal'); ?></strong> <?php echo wp_get_theme()->get('Name'); ?></p>
                <p><strong><?php _e('Template:', 'read-more-universal'); ?></strong> <?php echo get_template(); ?></p>
                <p><strong><?php _e('CSS selectors used:', 'read-more-universal'); ?></strong></p>
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
