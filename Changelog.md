# Changelog for Read More Universal

## Version 1.2.0 - 2025-07-15

### Added
- Metabox to enable/disable Read More per post/page
- Option to apply Read More to posts, pages, and archives
- Support for Divi and WPBakery themes
- Filters: `rmu_min_characters`, `rmu_button_text`, `rmu_theme_selectors` for developer customization
- Accessibility improvements: ARIA attributes and keyboard navigation
- Real-time button preview in admin panel
- `MutationObserver` for better dynamic content detection
- Smoother content expansion animation with CSS transitions

### Changed
- Optimized JavaScript by replacing multiple `setTimeout` with `MutationObserver`
- Improved admin interface with better organization and preview

### Fixed
- Resolved syntax error in `read-more-universal.php` causing parse errors

## Version 1.1.1 - 2025-01-16

### Security & Code Quality
- Fixed all WordPress coding standard violations
- Added proper output escaping for all dynamic content
- Implemented nonce verification for admin form submissions
- Added sanitization callbacks for all `register_setting()` calls
- Fixed input validation and unslashing for POST data
- Added proper translators comment for translatable strings with placeholders

### Changed
- Replaced `strip_tags()` with `wp_strip_all_tags()` for better security
- Used `wp_json_encode()` instead of `json_encode()` for proper encoding
- Added `esc_html_e()` instead of `_e()` for text output
- Added `esc_attr()` for all HTML attribute outputs
- Added `esc_js()` for JavaScript string outputs
- Implemented proper nonce field and verification in admin form
- Added `isset()` checks for all POST variables
- Added `wp_unslash()` for text inputs before sanitization

### Technical Improvements
- All output is now properly escaped using WordPress escaping functions
- Form processing now includes security nonce verification
- Settings registration includes proper sanitization callbacks
- Improved code compliance with WordPress-Extra and WordPress-VIP standards

## Version 1.1.0 - 2025-01-16

### Added
- Portuguese (`pt_PT`) translation support
- Italian (`it_IT`) translation support
- Enhanced debug mode with more detailed theme detection information
- Improved support for dynamically loaded content
- Multiple initialization attempts for better Astra theme compatibility
- CSS selector logging in debug mode

### Changed
- Optimized theme detection algorithm for better performance
- Improved gradient overlay rendering on mobile devices
- Enhanced JavaScript execution with multiple fallback attempts
- Updated button hover effects for better UX

### Fixed
- Fixed content detection issues with Astra theme
- Resolved gradient overlay display on iOS Safari
- Fixed button text color inheritance issues
- Corrected character counting for UTF-8 content

### Technical
- Added retry mechanism for content detection (500ms and 1500ms delays)
- Improved CSS specificity for better theme compatibility
- Optimized selector matching algorithm
- Added protection against duplicate processing

## Version 1.0.0 - 2025-01-01

### Initial Release
- Smart theme detection for 20+ popular WordPress themes
- Multi-language support (English, Spanish, French, German)
- Customizable appearance settings
- Analytics integration (Google Analytics 4, Universal Analytics, Facebook Pixel)
- Debug mode for troubleshooting
- Responsive design with mobile optimization
- Smooth animations and transitions
- Gradient fade effect for truncated content
- Admin settings panel with live preview
- Zero configuration required - works out of the box

### Supported Themes
- Twenty Twenty-Five (WordPress 6.7 default)
- Twenty Twenty-Four, Twenty Twenty-Three
- All Twenty series themes
- Astra (Free & Pro versions)
- GeneratePress
- OceanWP
- Elementor Hello
- Generic fallback for unknown themes

### Features
- Automatic content truncation for posts over 250 characters
- Customizable button text and colors
- Adjustable content height and border radius
- Lightweight implementation (~4KB total)
- No database queries
- SEO-friendly (full content remains in HTML)
- Accessibility compliant

### Known Issues
- None reported in initial release

### Notes
- Requires WordPress 5.0 or higher
- Requires PHP 7.4 or higher
- Tested up to WordPress 6.8
