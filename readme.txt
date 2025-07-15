=== Read More Universal ===
Contributors: dcarrero
Tags: read more, content truncation, accessibility, analytics, theme compatibility
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.2.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight, universal "Read More" plugin that automatically adapts to popular WordPress themes with customizable settings, accessibility support, and analytics integration.

== Description ==

Read More Universal is a lightweight (~4KB) WordPress plugin that automatically adds a "Read More" button to long content, enhancing user engagement and site performance. It intelligently detects your theme (e.g., Twenty Twenty-Five, Astra, Elementor, Divi, WPBakery) and applies the appropriate CSS selectors for seamless integration. No database queries are used, ensuring optimal performance.

Key features include:
- **Automatic Theme Detection**: Supports 20+ popular themes, including Twenty series, Astra, GeneratePress, OceanWP, Elementor, Divi, WPBakery, and more.
- **Customizable Settings**: Adjust minimum character count, content height, button text, colors, and border radius.
- **Per-Post/Page Control**: Enable or disable the "Read More" feature on individual posts or pages via a metabox.
- **Multi-Context Support**: Apply the "Read More" button to posts, pages, and archives (home, categories, tags).
- **Accessibility Compliance**: Includes ARIA attributes and keyboard navigation support (Enter/Space keys).
- **Analytics Integration**: Tracks "Read More" clicks with Google Analytics (GA4 and Universal) and Facebook Pixel.
- **Smooth Animations**: Enhanced content expansion with CSS transitions for a better user experience.
- **Multilingual Support**: Available in English, Spanish, French, German, Italian, and Portuguese.
- **Debug Mode**: Detailed console logging for troubleshooting theme compatibility.
- **Developer-Friendly**: Includes filters (`rmu_min_characters`, `rmu_button_text`, `rmu_theme_selectors`) for customization.
- **SEO-Friendly**: Full content remains in the HTML for search engine indexing.

The plugin is designed to be plug-and-play, requiring zero configuration for most themes, with advanced options for power users.

== Installation ==

1. Upload the `read-more-universal` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. (Optional) Configure settings under **Settings > Read More Universal** to customize the button appearance, content length, and application scope.
4. (Optional) Use the metabox in the post/page editor to enable/disable the "Read More" feature for specific content.
5. Enable debug mode in settings to troubleshoot theme compatibility if needed.

== Frequently Asked Questions ==

= Which themes are supported? =
The plugin automatically detects and supports popular themes including Twenty Twenty-Five, Twenty Twenty-Four, Twenty Twenty-Three, Astra, GeneratePress, OceanWP, Elementor Hello, Divi, WPBakery, and more. It includes a generic fallback for other themes.

= Can I customize the button appearance? =
Yes! You can customize the button text, background color, text color, and border radius in the settings panel. A live preview is available to see changes in real-time.

= Can I control where the "Read More" button appears? =
Yes, you can choose to apply the button to posts, pages, and/or archives (home, categories, tags) via the settings. Additionally, a metabox in the post/page editor allows you to enable or disable it for specific content.

= Is the plugin accessible? =
Yes, the plugin includes ARIA attributes (`aria-expanded`) and supports keyboard navigation (Enter and Space keys) for accessibility compliance.

= Does it work with dynamic content? =
Yes, the plugin uses `MutationObserver` to handle dynamically loaded content, ensuring compatibility with themes like Astra that load content asynchronously.

= How do I troubleshoot compatibility issues? =
Enable debug mode in the settings to log detailed information in the browser console, including detected theme and CSS selectors used.

= Does it support analytics? =
Yes, it integrates with Google Analytics (GA4 and Universal) and Facebook Pixel to track "Read More" button clicks.

= Can developers extend the plugin? =
Yes, the plugin includes filters (`rmu_min_characters`, `rmu_button_text`, `rmu_theme_selectors`) to allow developers to customize its behavior.

== Screenshots ==

1. Admin settings panel with live button preview and theme detection.
2. "Read More" button displayed on a post with a smooth gradient overlay.
3. Metabox in the post/page editor to enable/disable the feature.
4. Expanded content after clicking the "Read More" button.

== Changelog ==

= 1.2.0 - 2025-07-15 =
* Added: Metabox to enable/disable Read More per post/page.
* Added: Option to apply Read More to posts, pages, and archives.
* Added: Support for Divi and WPBakery themes.
* Added: Filters (`rmu_min_characters`, `rmu_button_text`, `rmu_theme_selectors`) for developer customization.
* Added: Accessibility improvements with ARIA attributes and keyboard navigation.
* Added: Real-time button preview in the admin panel.
* Added: `MutationObserver` for better dynamic content detection.
* Added: Smoother content expansion animation with CSS transitions.
* Changed: Optimized JavaScript by replacing multiple `setTimeout` with `MutationObserver`.
* Changed: Improved admin interface with better organization and preview.
* Fixed: Resolved syntax error in `read-more-universal.php`.

= 1.1.1 - 2025-01-16 =
* Security: Fixed all WordPress coding standard violations.
* Security: Added proper output escaping for all dynamic content.
* Security: Implemented nonce verification for admin form submissions.
* Security: Added sanitization callbacks for all settings.
* Security: Fixed input validation and unslashing for POST data.
* Changed: Replaced `strip_tags()` with `wp_strip_all_tags()` for better security.
* Changed: Used `wp_json_encode()` instead of `json_encode()`.
* Changed: Added proper escaping functions (`esc_html_e`, `esc_attr`, `esc_js`).
* Technical: Improved code compliance with WordPress-Extra and WordPress-VIP standards.

= 1.1.0 - 2025-01-16 =
* Added: Portuguese (pt_PT) and Italian (it_IT) translation support.
* Added: Enhanced debug mode with detailed theme detection information.
* Added: Support for dynamically loaded content with multiple initialization attempts.
* Added: CSS selector logging in debug mode.
* Changed: Optimized theme detection algorithm for better performance.
* Changed: Improved gradient overlay rendering on mobile devices.
* Changed: Enhanced button hover effects for better UX.
* Fixed: Content detection issues with Astra theme.
* Fixed: Gradient overlay display on iOS Safari.
* Fixed: Button text color inheritance issues.
* Fixed: Character counting for UTF-8 content.

= 1.0.0 - 2025-01-01 =
* Initial release with support for 20+ themes, multilingual support, analytics integration, and customizable settings.

== Upgrade Notice ==

= 1.2.0 =
This update adds per-post/page control, support for pages and archives, Divi/WPBakery compatibility, accessibility improvements, developer filters, and a smoother user experience. Update `read-more-universal.php` to fix a syntax error from previous versions. Back up your settings before upgrading.

== Additional Notes ==
* The plugin is SEO-friendly, keeping full content in the HTML.
* No database queries ensure high performance.
* Compatible with WordPress 5.0+ and PHP 7.4+.
* For support, visit https://github.com/dcarrero/read-more-universal.
