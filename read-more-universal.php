<?php
/**
 * Plugin Name: Read More Universal
 * Plugin URI: https://github.com/dcarrero/read-more-universal
 * Description: Universal "Read More" system that automatically adapts to Twenty Twenty-Five, Astra, Elementor and other popular themes.
 * Version: 1.2.0
 * Author: David Carrero Fernández-Baillo
 * Author URI: https://carrero.es
 * License: GPL v2 or later
 * Text Domain: read-more-universal
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 */

// Prevent direct access
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
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('read-more-universal', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function init() {
        // Detect current theme
        $this->detect_theme();
        // Load configuration
        $this->min_characters = apply_filters('rmu_min_characters', get_option('rmu_min_characters', 250));
        $this->theme_selectors = apply_filters('rmu_theme_selectors', $this->theme_selectors, $this->theme_name);
    }
    
    private function detect_theme() {
        $theme = wp_get_theme();
        $theme_name = strtolower($theme->get('Name'));
        $template = get_template();
        
        // Detect theme and configure specific selectors
        if (strpos($theme_name, 'astra') !== false || $template === 'astra') {
            $this->theme_name = 'astra';
            $this->theme_selectors = array(
                '.ast-article-post .entry-content',
                '.single-post .entry-content',
                '.ast-article-single .entry-content',
                '.entry-content',
                'article .entry-content',
                '.post-content',
                '.ast-container .entry-content'
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
        } elseif (strpos($theme_name, 'divi') !== false || $template === 'divi') {
            $this->theme_name = 'divi';
            $this->theme_selectors = array(
                '.et_pb_post_content',
                '.entry-content',
                '.et_pb_text_inner'
            );
        } elseif (strpos($theme_name, 'wpbakery') !== false || $template === 'wpbakery') {
            $this->theme_name = 'wpbakery';
            $this->theme_selectors = array(
                '.wpb_text_column',
                '.entry-content',
                '.vc_column-inner'
            );
        } else {
            // Generic theme - use universal selectors
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
        $apply_to = get_option('rmu_apply_to', array('post'));
        $is_applicable = (
            (in_array('post', $apply_to) && is_single()) ||
            (in_array('page', $apply_to) && is_page()) ||
            (in_array('archive', $apply_to) && (is_archive() || is_home()))
        );

        if (!$is_applicable) return;

        global $post;
        if ((is_single() || is_page()) && $post) {
            $enabled = get_post_meta($post->ID, '_rmu_enabled', true);
            if ($enabled === '0') return; // Explicitly disabled
        }
        
        $content = get_the_content();
        $text_content = wp_strip_all_tags($content);
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
            max-height: <?php echo esc_attr(get_option('rmu_max_height', '250')); ?>px;
            overflow: hidden;
            position: relative;
            transition: max-height 0.5s ease-in-out;
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
            max-height: 10000px !important;
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

        /* Theme specific */
        <?php if ($this->theme_name === 'astra'): ?>
        .ast-article-single .rmu-wrapper,
        .ast-separate-container .rmu-wrapper {
            margin-bottom: 1.5em;
        }
        <?php elseif ($this->theme_name === 'elementor'): ?>
        .elementor-widget-theme-post-content .rmu-wrapper {
            margin-bottom: 1.5em;
        }
        <?php elseif ($this->theme_name === 'divi'): ?>
        .et_pb_post_content .rmu-wrapper {
            margin-bottom: 1.5em;
        }
        <?php elseif ($this->theme_name === 'wpbakery'): ?>
        .wpb_text_column .rmu-wrapper {
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
        
        // Try to get theme colors
        if ($this->theme_name === 'astra' && function_exists('astra_get_option')) {
            $primary = astra_get_option('theme-color');
            if ($primary) $defaults['primary'] = $primary;
        }
        
        // Allow customization from settings
        $defaults['primary'] = get_option('rmu_button_color', $defaults['primary']);
        $defaults['text'] = get_option('rmu_text_color', $defaults['text']);
        
        return $defaults;
    }
    
    private function output_script() {
        $selectors_json = wp_json_encode($this->theme_selectors);
        ?>
        <script id="read-more-universal-script">
        (function() {
            var selectors = <?php echo $selectors_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
            var debugMode = <?php echo get_option('rmu_debug_mode', 0) ? 'true' : 'false'; ?>;
            
            function log(message) {
                if (debugMode) console.log('[Read More Universal] ' + message);
            }
            
            function findPostContent() {
                log('Searching for post content...');
                log('Detected theme: <?php echo esc_js($this->theme_name); ?>');
                
                for (var i = 0; i < selectors.length; i++) {
                    var elements = document.querySelectorAll(selectors[i]);
                    log('Trying selector: ' + selectors[i] + ' - Found: ' + elements.length);
                    
                    for (var j = 0; j < elements.length; j++) {
                        var element = elements[j];
                        // Verify element has real content and is not empty
                        if (element && element.textContent.trim().length > 50) {
                            log('Content found with selector: ' + selectors[i]);
                            log('Text content: ' + element.textContent.length + ' characters');
                            return element;
                        }
                    }
                }
                
                log('No content found with specific selectors, searching generic...');
                
                // Broader search for generic themes
                var genericSelectors = [
                    'main .entry-content',
                    '#main .entry-content', 
                    '.site-main .entry-content',
                    '.ast-container .entry-content',
                    'article.post .entry-content',
                    '.single article .entry-content',
                    '[class*="entry-content"]'
                ];
                
                for (var k = 0; k < genericSelectors.length; k++) {
                    var elements = document.querySelectorAll(genericSelectors[k]);
                    log('Trying generic selector: ' + genericSelectors[k] + ' - Found: ' + elements.length);
                    
                    for (var l = 0; l < elements.length; l++) {
                        var element = elements[l];
                        if (element && element.textContent.trim().length > 50) {
                            log('Content found with generic selector: ' + genericSelectors[k]);
                            return element;
                        }
                    }
                }
                
                log('Post content not found');
                return null;
            }

            function processContent(postContent) {
                if (postContent.classList.contains('rmu-processed')) {
                    log('Already processed previously');
                    return;
                }
                
                log('Processing content...');
                postContent.classList.add('rmu-processed');
                
                var wrapper = document.createElement('div');
                wrapper.className = 'rmu-wrapper';
                
                postContent.parentNode.insertBefore(wrapper, postContent);
                wrapper.appendChild(postContent);
                
                postContent.classList.add('rmu-content', 'truncated');
                
                var buttonContainer = document.createElement('div');
                buttonContainer.className = 'rmu-button-container';
                buttonContainer.innerHTML = '<button class="rmu-button" aria-expanded="false" onclick="RMU_expandContent()" onkeydown="RMU_handleKeydown(event)"><?php echo esc_js(get_option('rmu_button_text', $this->get_default_button_text())); ?></button>';
                
                wrapper.appendChild(buttonContainer);
                
                log('Read More Universal applied successfully');
            }
            
            window.RMU_handleKeydown = function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    RMU_expandContent();
                }
            };
            
            window.RMU_expandContent = function() {
                log('Expanding content...');
                var content = document.querySelector('.rmu-content');
                var button = document.querySelector('.rmu-button');
                
                if (content && button) {
                    content.classList.remove('truncated');
                    content.classList.add('expanded');
                    button.classList.add('hidden');
                    button.setAttribute('aria-expanded', 'true');
                    
                    log('Content expanded');
                    
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
                    log('Could not expand - elements not found');
                }
            };
            
            function initReadMore() {
                log('Starting Read More Universal');
                
                var postContent = findPostContent();
                
                if (postContent) {
                    processContent(postContent);
                    return;
                }
                
                // Use MutationObserver for dynamic content
                log('Setting up MutationObserver for dynamic content');
                var observer = new MutationObserver(function(mutations, obs) {
                    var postContent = findPostContent();
                    if (postContent) {
                        processContent(postContent);
                        obs.disconnect();
                    }
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
                
                // Fallback: stop observing after 5 seconds
                setTimeout(function() {
                    observer.disconnect();
                    log('Stopped MutationObserver - timeout reached');
                }, 5000);
            }
            
            document.addEventListener('DOMContentLoaded', function() {
                log('DOM loaded, starting Read More');
                initReadMore();
            });
            
            window.addEventListener('load', function() {
                initReadMore();
            });
        })();
        </script>
        <?php
    }
    
    // Admin panel
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
        // Register settings with sanitization callbacks
        register_setting('rmu_settings', 'rmu_min_characters', array(
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 250
        ));
        
        register_setting('rmu_settings', 'rmu_max_height', array(
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 250
        ));
        
        register_setting('rmu_settings', 'rmu_button_text', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->get_default_button_text()
        ));
        
        register_setting('rmu_settings', 'rmu_button_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#007cba'
        ));
        
        register_setting('rmu_settings', 'rmu_text_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff'
        ));
        
        register_setting('rmu_settings', 'rmu_border_radius', array(
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 25
        ));
        
        register_setting('rmu_settings', 'rmu_debug_mode', array(
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default' => false
        ));
        
        register_setting('rmu_settings', 'rmu_apply_to', array(
            'type' => 'array',
            'sanitize_callback' => array($this, 'sanitize_array'),
            'default' => array('post')
        ));
    }
    
    public function sanitize_array($input) {
        return is_array($input) ? array_map('sanitize_text_field', $input) : array();
    }
    
    public function add_meta_box() {
        add_meta_box(
            'rmu_meta_box',
            __('Read More Universal', 'read-more-universal'),
            array($this, 'render_meta_box'),
            array('post', 'page'),
            'side',
            'default'
        );
    }
    
    public function render_meta_box($post) {
        $enabled = get_post_meta($post->ID, '_rmu_enabled', true);
        wp_nonce_field('rmu_meta_box', 'rmu_meta_box_nonce');
        ?>
        <label>
            <input type="checkbox" name="rmu_enabled" value="1" <?php checked($enabled, '1'); ?>>
            <?php esc_html_e('Enable Read More for this post/page', 'read-more-universal'); ?>
        </label>
        <p class="description"><?php esc_html_e('Leave unchecked to use global settings', 'read-more-universal'); ?></p>
        <?php
    }
    
    public function save_meta_box($post_id) {
        if (!isset($_POST['rmu_meta_box_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rmu_meta_box_nonce'])), 'rmu_meta_box')) {
            return;
        }
        if (isset($_POST['rmu_enabled'])) {
            update_post_meta($post_id, '_rmu_enabled', '1');
        } else {
            update_post_meta($post_id, '_rmu_enabled', '0');
        }
    }
    
    public function admin_page() {
        // Handle form submission with nonce verification
        if (isset($_POST['submit']) && isset($_POST['rmu_settings_nonce'])) {
            if (wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rmu_settings_nonce'])), 'rmu_settings_action')) {
                update_option('rmu_min_characters', absint($_POST['rmu_min_characters'] ?? 250));
                update_option('rmu_max_height', absint($_POST['rmu_max_height'] ?? 250));
                update_option('rmu_button_text', sanitize_text_field(wp_unslash($_POST['rmu_button_text'] ?? '')));
                update_option('rmu_button_color', sanitize_hex_color(wp_unslash($_POST['rmu_button_color'] ?? '')));
                update_option('rmu_text_color', sanitize_hex_color(wp_unslash($_POST['rmu_text_color'] ?? '')));
                update_option('rmu_border_radius', absint($_POST['rmu_border_radius'] ?? 25));
                update_option('rmu_debug_mode', isset($_POST['rmu_debug_mode']) ? 1 : 0);
                update_option('rmu_apply_to', $this->sanitize_array($_POST['rmu_apply_to'] ?? array('post')));
                echo '<div class="notice notice-success"><p>' . esc_html__('Settings saved.', 'read-more-universal') . '</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . esc_html__('Security check failed. Please try again.', 'read-more-universal') . '</p></div>';
            }
        }
        
        $min_chars = get_option('rmu_min_characters', 250);
        $max_height = get_option('rmu_max_height', 250);
        $button_text = get_option('rmu_button_text', $this->get_default_button_text());
        $button_color = get_option('rmu_button_color', '#007cba');
        $text_color = get_option('rmu_text_color', '#ffffff');
        $border_radius = get_option('rmu_border_radius', 25);
        $debug_mode = get_option('rmu_debug_mode', 0);
        $apply_to = get_option('rmu_apply_to', array('post'));
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Read More Universal - Settings', 'read-more-universal'); ?></h1>
            
            <div style="background: #f1f1f1; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h3><?php 
                /* translators: %s: Name of the detected theme */
                printf(esc_html__('🎯 Detected theme: %s', 'read-more-universal'), '<strong>' . esc_html(ucfirst($this->theme_name)) . '</strong>'); 
                ?></h3>
                <p><?php esc_html_e('The plugin has been automatically configured for your theme.', 'read-more-universal'); ?></p>
            </div>
            
            <form method="post">
                <?php wp_nonce_field('rmu_settings_action', 'rmu_settings_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e('Apply to', 'read-more-universal'); ?></th>
                        <td>
                            <?php
                            $options = array(
                                'post' => __('Posts', 'read-more-universal'),
                                'page' => __('Pages', 'read-more-universal'),
                                'archive' => __('Archives', 'read-more-universal')
                            );
                            foreach ($options as $value => $label) {
                                ?>
                                <label>
                                    <input type="checkbox" name="rmu_apply_to[]" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $apply_to)); ?>>
                                    <?php echo esc_html($label); ?>
                                </label><br>
                                <?php
                            }
                            ?>
                            <p class="description"><?php esc_html_e('Select where to apply the Read More functionality', 'read-more-universal'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Minimum characters', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_min_characters" id="rmu_min_characters" value="<?php echo esc_attr($min_chars); ?>" min="50" max="1000" />
                            <p class="description"><?php esc_html_e('Minimum number of characters to show the "Read more" button', 'read-more-universal'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Max height (px)', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_max_height" id="rmu_max_height" value="<?php echo esc_attr($max_height); ?>" min="100" max="500" />
                            <p class="description"><?php esc_html_e('Height in pixels of the truncated content', 'read-more-universal'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Button text', 'read-more-universal'); ?></th>
                        <td>
                            <input type="text" name="rmu_button_text" id="rmu_button_text" value="<?php echo esc_attr($button_text); ?>" style="width: 300px;" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Button color', 'read-more-universal'); ?></th>
                        <td>
                            <input type="color" name="rmu_button_color" id="rmu_button_color" value="<?php echo esc_attr($button_color); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Text color', 'read-more-universal'); ?></th>
                        <td>
                            <input type="color" name="rmu_text_color" id="rmu_text_color" value="<?php echo esc_attr($text_color); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Border radius (px)', 'read-more-universal'); ?></th>
                        <td>
                            <input type="number" name="rmu_border_radius" id="rmu_border_radius" value="<?php echo esc_attr($border_radius); ?>" min="0" max="50" />
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
                
                <div style="margin-top: 20px;">
                    <h3><?php esc_html_e('Button Preview', 'read-more-universal'); ?></h3>
                    <button id="rmu_preview_button" style="padding: 1rem 2rem; font-weight: 600; background: <?php echo esc_attr($button_color); ?>; color: <?php echo esc_attr($text_color); ?>; border-radius: <?php echo esc_attr($border_radius); ?>px;"><?php echo esc_html($button_text); ?></button>
                </div>
                
                <script>
                jQuery(document).ready(function($) {
                    function updatePreview() {
                        var buttonColor = $('#rmu_button_color').val();
                        var textColor = $('#rmu_text_color').val();
                        var borderRadius = $('#rmu_border_radius').val() + 'px';
                        var buttonText = $('#rmu_button_text').val();

                        $('#rmu_preview_button').css({
                            'background-color': buttonColor,
                            'color': textColor,
                            'border-radius': borderRadius
                        }).text(buttonText);
                    }

                    $('#rmu_button_color, #rmu_text_color, #rmu_border_radius, #rmu_button_text').on('input change', updatePreview);
                });
                </script>
                
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
        return apply_filters('rmu_button_text', __('📖 Read full article', 'read-more-universal'));
    }
}

// Initialize the plugin
new ReadMoreUniversal();
?>
