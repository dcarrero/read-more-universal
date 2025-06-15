# Changelog

All notable changes to Read More Universal will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2025-01-15

### 🚀 Added
- **Enhanced Elementor Support**: Full compatibility with Elementor + any theme combinations
- **Astra + Elementor Detection**: Automatic detection and optimization for Astra + Elementor setups
- **Multi-layered Content Detection**: 3-tier content finding system for maximum compatibility
- **Dynamic Content Support**: Detects AJAX-loaded content from page builders
- **MutationObserver Integration**: Watches for dynamically added content
- **Elementor Hooks**: Integrates with Elementor's frontend loading system
- **Advanced Content Validation**: Intelligent filtering to exclude navigation, sidebars, footers
- **Enhanced Debug Logging**: More detailed console output with emojis and status indicators
- **Multiple Timing Strategies**: 4 different initialization approaches for page builders

### 🔧 Improved
- **Content Detection Algorithm**: Smarter element validation with multiple criteria
- **Selector Specificity**: Added 15+ new CSS selectors for page builders
- **Loading Performance**: Optimized retry mechanisms for dynamic content
- **Error Handling**: Better fallbacks when content detection fails
- **Debug Mode**: More informative console messages with clear status indicators

### 🐛 Fixed
- **Astra Compatibility**: Resolved issues with Astra theme content detection
- **Elementor Integration**: Fixed problems with Elementor-generated content
- **Page Builder Support**: Enhanced compatibility with dynamically loaded content
- **Timing Issues**: Fixed race conditions with page builder initialization
- **Selector Conflicts**: Resolved CSS selector priority issues

### 📋 Technical Changes
- Added `isValidContent()` function for intelligent content validation
- Implemented `waitForElementor()` function for Elementor-specific timing
- Enhanced `findPostContent()` with 3-tier detection system
- Added MutationObserver for dynamic content changes
- Improved theme detection logic with page builder awareness

### 🎯 Supported Combinations
- ✅ **Astra + Elementor** (Free & Pro)
- ✅ **Twenty Twenty-Five + Elementor**
- ✅ **GeneratePress + Elementor**
- ✅ **Hello Elementor** (Elementor's theme)
- ✅ **Any Theme + Elementor** (Universal fallback)
- ✅ **Generic Page Builders** (Beaver Builder, Divi, etc.)

## [1.0.0] - 2025-01-15

### 🎉 Initial Release

#### ✨ Core Features
- **Universal Theme Detection**: Automatic configuration for 20+ popular WordPress themes
- **Multi-language Support**: Built-in translations for English, Spanish, French, German
- **Smart Content Truncation**: Character-based detection with smooth gradient fade
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices
- **Zero Configuration**: Works out of the box with intelligent defaults

#### 🎯 Supported Themes
- **WordPress Default Themes**: Twenty Twenty-Five, Twenty Twenty-Four, Twenty Twenty-Three, etc.
- **Popular Third-Party Themes**: Astra, GeneratePress, OceanWP
- **Page Builder Themes**: Hello Elementor
- **Generic Fallback**: Universal selectors for any WordPress theme

#### 🎨 Customization Options
- Minimum character threshold (default: 250)
- Maximum height for truncated content (default: 250px)
- Custom button text with automatic translation
- Button colors and styling options
- Border radius customization
- Debug mode for troubleshooting

#### 📊 Analytics Integration
- **Google Analytics 4**: Event tracking for engagement
- **Universal Analytics**: Legacy support
- **Facebook Pixel**: Custom "ReadMore" events
- **Extensible**: Easy integration with other analytics platforms

#### 🔧 Developer Features
- WordPress hooks and filters for customization
- CSS customization support
- Debug mode with detailed console logging
- Theme information panel in admin
- Security hardening (CSRF protection, input sanitization)

#### 🌍 Internationalization
- **Translation System**: WordPress standard .po/.mo files
- **Text Domain**: `read-more-universal`
- **Included Languages**: English (default), Spanish (es_ES), French (fr_FR), German (de_DE)
- **Translation Tools**: POT template and compilation scripts included

#### 📋 Technical Specifications
- **WordPress**: 5.0+ (Recommended: 6.7+)
- **PHP**: 7.4+ (Recommended: 8.0+)
- **License**: GPL v2+
- **Performance**: ~3KB total weight, zero database queries
- **Security**: Input sanitization, nonce verification, capability checks

#### 🏗️ Architecture
- Object-oriented PHP design
- Automatic theme detection system
- CSS-first approach with JavaScript enhancement
- Lightweight and performant implementation
- No external dependencies

---

## 📝 Version History Summary

- **v1.0.1**: Enhanced page builder support, improved Astra + Elementor compatibility
- **v1.0.0**: Initial release with core functionality and multi-theme support

## 🔮 Upcoming Features

Ideas being considered for future releases:

- Gutenberg block integration
- Custom post type support
- Advanced animation options
- A/B testing capabilities
- AMP compatibility
- WooCommerce integration

## 🐛 Known Issues

- None currently reported for v1.0.1

## 📞 Support

- **Bug Reports**: [GitHub Issues](https://github.com/dcarrero/read-more-universal/issues)
- **Feature Requests**: [GitHub Discussions](https://github.com/dcarrero/read-more-universal/discussions)
- **Email Support**: david@carrero.es

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for more information.

---

**Note**: This plugin follows semantic versioning. Breaking changes will only be introduced in major version updates.
