=== Read More Universal ===
Contributors: dcarrero
Tags: read more, content, truncate, engagement, themes
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Universal "Read More" system that automatically adapts to Twenty Twenty-Five, Astra, Elementor and other popular WordPress themes.

== Description ==

**Read More Universal** is a powerful yet lightweight WordPress plugin that automatically adds a "Read More" functionality to your posts. Unlike other plugins that only work with specific themes, this plugin intelligently detects your theme and adapts its behavior accordingly.

### ✨ Key Features

* **Smart Theme Detection** - Automatically configures for 20+ popular themes
* **Page Builder Support** - Full compatibility with Elementor, Beaver Builder, Divi
* **Multi-language Support** - Available in English, Spanish, French, German
* **Zero Configuration** - Works out of the box with intelligent defaults
* **Fully Customizable** - Colors, text, dimensions, and animations
* **Analytics Ready** - Built-in Google Analytics and Facebook Pixel tracking
* **Lightweight** - Only ~4KB total weight, zero database queries
* **Responsive Design** - Perfect on desktop, tablet, and mobile

### 🎯 Supported Themes

**WordPress Default Themes:**
* Twenty Twenty-Five (WordPress 6.7 default)
* Twenty Twenty-Four, Twenty Twenty-Three
* All Twenty series themes

**Popular Third-Party Themes:**
* Astra (Free & Pro) + Astra + Elementor combinations
* GeneratePress (Free & Premium)
* OceanWP
* Hello Elementor

**Page Builders:**
* Elementor (Free & Pro)
* Any theme + Elementor combinations
* Generic page builder support

### 🌍 Translations

Currently available in:
* English (default)
* Spanish (es_ES)
* French (fr_FR)
* German (de_DE)

### 🔧 How It Works

The plugin automatically:
1. Detects posts with 250+ characters (customizable)
2. Truncates content with a smooth gradient fade
3. Adds a "Read full article" button
4. Expands content with smooth animations when clicked

No configuration needed, but plenty of customization options available in **Settings > Read More Universal**.

### 📊 Analytics Integration

Built-in support for:
* Google Analytics 4
* Universal Analytics (legacy)
* Facebook Pixel
* Custom events for other platforms

### 🔍 Debug Mode

Enable debug mode to troubleshoot theme compatibility issues. The plugin will show detailed information in the browser console about theme detection and content processing.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/read-more-universal/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. The plugin works automatically - no configuration needed!
4. Optionally, go to Settings > Read More Universal to customize

== Frequently Asked Questions ==

= Does this work with my theme? =

The plugin automatically detects and adapts to most WordPress themes. It has specific optimizations for popular themes like Twenty Twenty-Five, Astra, GeneratePress, and OceanWP. For other themes, it uses universal selectors that work with most WordPress themes.

= Can I customize the button appearance? =

Yes! Go to Settings > Read More Universal to customize:
- Button text and colors
- Minimum character threshold
- Content height
- Border radius
- Debug mode

= Does it work with page builders? =

Yes! The plugin has enhanced support for:
- Elementor (with any theme)
- Beaver Builder
- Divi
- Other page builders (generic support)

= Will it slow down my site? =

No. The plugin is extremely lightweight (~4KB) and only loads on single post pages. It adds zero database queries and has minimal performance impact.

= How do I troubleshoot if it's not working? =

1. Enable debug mode in plugin settings
2. Open browser console (F12) on a post page
3. Look for detailed debug messages
4. Check the "Theme information" section in plugin settings

= Can I translate the plugin? =

Yes! The plugin uses WordPress standard translation system. Translation files are included for Spanish, French, and German. You can contribute more translations via the plugin's GitHub repository.

== Screenshots ==

1. Plugin automatically detects your theme and shows configuration
2. Customization options in WordPress admin
3. Example of truncated content with "Read More" button
4. Smooth content expansion animation
5. Debug mode showing detailed console information

== Changelog ==

= 1.1.0 - 2025-01-15 =
**Security & Compatibility Update**

**Fixed:**
* All WordPress.org plugin review requirements
* Security: Added nonce verification for admin forms
* Security: Proper input sanitization and output escaping
* Security: Added capability checks for admin functions
* Replaced deprecated strip_tags() with wp_strip_all_tags()
* Enhanced Astra theme compatibility with specific detection
* Improved Astra + Elementor combination support

**Added:**
* Translator comments for i18n strings with placeholders
* Sanitization callbacks for all register_setting() calls
* Enhanced debug mode with Astra-specific functionality
* Better error handling and validation

**Improved:**
* Admin interface security hardening
* Theme detection algorithm for better compatibility
* Code standards compliance for WordPress.org submission

= 1.0.1 - 2025-01-15 =
**Enhanced Page Builder Support**

**Added:**
* Enhanced Elementor Support: Full compatibility with Elementor + any theme combinations
* Astra + Elementor Detection: Automatic detection and optimization for Astra + Elementor setups
* Multi-layered Content Detection: 3-tier content finding system for maximum compatibility
* Dynamic Content Support: Detects AJAX-loaded content from page builders
* MutationObserver Integration: Watches for dynamically added content
* Elementor Hooks: Integrates with Elementor's frontend loading system
* Advanced Content Validation: Intelligent filtering to exclude navigation, sidebars, footers
* Enhanced Debug Logging: More detailed console output with emojis and status indicators
* Multiple Timing Strategies: 4 different initialization approaches for page builders

**Improved:**
* Content Detection Algorithm: Smarter element validation with multiple criteria
* Selector Specificity: Added 15+ new CSS selectors for page builders
* Loading Performance: Optimized retry mechanisms for dynamic content
* Error Handling: Better fallbacks when content detection fails
* Debug Mode: More informative console messages with clear status indicators

**Fixed:**
* Astra Compatibility: Resolved issues with Astra theme content detection
* Elementor Integration: Fixed problems with Elementor-generated content
* Page Builder Support: Enhanced compatibility with dynamically loaded content
* Timing Issues: Fixed race conditions with page builder initialization
* Selector Conflicts: Resolved CSS selector priority issues

= 1.0.0 - 2025-01-15 =
**Initial Release**

**Core Features:**
* Universal Theme Detection: Automatic configuration for 20+ popular WordPress themes
* Multi-language Support: Built-in translations for English, Spanish, French, German
* Smart Content Truncation: Character-based detection with smooth gradient fade
* Responsive Design: Optimized for desktop, tablet, and mobile devices
* Zero Configuration: Works out of the box with intelligent defaults

**Supported Themes:**
* WordPress Default Themes: Twenty Twenty-Five, Twenty Twenty-Four, Twenty Twenty-Three, etc.
* Popular Third-Party Themes: Astra, GeneratePress, OceanWP
* Page Builder Themes: Hello Elementor
* Generic Fallback: Universal selectors for any WordPress theme

**Customization Options:**
* Minimum character threshold (default: 250)
* Maximum height for truncated content (default: 250px)
* Custom button text with automatic translation
* Button colors and styling options
* Border radius customization
* Debug mode for troubleshooting

**Analytics Integration:**
* Google Analytics 4: Event tracking for engagement
* Universal Analytics: Legacy support
* Facebook Pixel: Custom "ReadMore" events
* Extensible: Easy integration with other analytics platforms

**Developer Features:**
* WordPress hooks and filters for customization
* CSS customization support
* Debug mode with detailed console logging
* Theme information panel in admin
* Security hardening (CSRF protection, input sanitization)

**Internationalization:**
* Translation System: WordPress standard .po/.mo files
* Text Domain: read-more-universal
* Included Languages: English (default), Spanish (es_ES), French (fr_FR), German (de_DE)
* Translation Tools: POT template and compilation scripts included

== Upgrade Notice ==

= 1.1.0 =
Important security update with improved Astra compatibility. All users should upgrade immediately.

= 1.0.1 =
Enhanced page builder support, especially for Astra + Elementor combinations. Recommended for all users.

= 1.0.0 =
Initial release with universal theme support and multi-language capabilities.

== Developer Information ==

**GitHub Repository:** https://github.com/dcarrero/read-more-universal
**Author Website:** https://carrero.es
**Support:** For technical support, please use the WordPress.org support forums or GitHub issues.

**Hooks & Filters:**
The plugin provides several hooks for developers:

`// Customize minimum character threshold
add_filter('rmu_min_characters', function($min) {
    return 400; // Require 400+ characters
});

// Modify button text programmatically
add_filter('rmu_button_text', function($text) {
    return 'Continue Reading';
});`

**CSS Customization:**
Override default styles by targeting these CSS classes:
* `.rmu-wrapper` - Main container
* `.rmu-content.truncated` - Truncated content
* `.rmu-button` - Read more button
* `.rmu-content.expanded` - Expanded content

**Debug Mode:**
Enable debug mode in plugin settings to see detailed console output about theme detection and content processing. Useful for troubleshooting theme compatibility issues.

== Privacy Policy ==

This plugin does not collect any personal data from your website visitors. It only provides functionality to truncate and expand post content on the frontend.

The plugin does integrate with analytics platforms (Google Analytics, Facebook Pixel) if they are already present on your site, but does not install or configure these services independently.

== Credits ==

* WordPress community for theme structure insights
* Astra and Elementor teams for their excellent documentation
* Contributors and translators
* Beta testers across different theme/builder combinations
