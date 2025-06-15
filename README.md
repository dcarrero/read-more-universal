# 📖 Read More Universal

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-6.7%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Translations](https://img.shields.io/badge/Languages-4-brightgreen.svg)](#-translations)

A universal "Read More" system that automatically adapts to **Twenty Twenty-Five**, **Astra**, **Elementor**, **GeneratePress**, **OceanWP** and other popular WordPress themes. Improve user engagement and page views with a sleek, animated content truncation system.

## ✨ Features

- 🎯 **Smart Theme Detection** - Automatically configures for 20+ popular themes
- 🌍 **Multi-language Support** - Available in English, Spanish, French, German + more
- ⚡ **Lightweight & Fast** - Zero impact on site performance
- 🎨 **Fully Customizable** - Colors, text, dimensions, and animations
- 📱 **Responsive Design** - Perfect on desktop, tablet, and mobile
- 📊 **Analytics Ready** - Built-in Google Analytics and Facebook Pixel tracking
- 🔧 **Zero Configuration** - Works out of the box, customize if needed
- 🔍 **Debug Mode** - Developer-friendly troubleshooting tools

## 🚀 Quick Start

### Installation

1. **Download** the plugin files
2. **Upload** to `/wp-content/plugins/read-more-universal/`
3. **Activate** the plugin through WordPress admin
4. **Done!** The plugin automatically detects your theme and starts working

### Basic Usage

The plugin automatically:
- Detects posts with 250+ characters
- Truncates content with a smooth gradient fade
- Adds a "Read full article" button
- Expands content with smooth animations

No configuration needed! But plenty of customization options available.

## 🎨 Customization

Access settings via **WordPress Admin → Settings → Read More Universal**

### Available Options

| Setting | Description | Default |
|---------|-------------|---------|
| **Minimum Characters** | Character threshold to show button | 250 |
| **Max Height** | Height of truncated content | 250px |
| **Button Text** | Customize button label | "📖 Read full article" |
| **Button Color** | Primary button color | #007cba |
| **Text Color** | Button text color | #ffffff |
| **Border Radius** | Button roundness | 25px |
| **Debug Mode** | Enable console debugging | Off |

## 🎯 Supported Themes

### Automatically Detected & Optimized

- ✅ **Twenty Twenty-Five** (WordPress 6.7 default)
- ✅ **Twenty Twenty-Four** (WordPress 6.4-6.6 default)
- ✅ **Twenty Twenty-Three** (WordPress 6.1-6.3 default)
- ✅ **All Twenty Series** (Twenty Twenty-Two, Twenty Twenty-One, etc.)
- ✅ **Astra** (Free & Pro)
- ✅ **Elementor** / Hello Elementor
- ✅ **GeneratePress** (Free & Premium)
- ✅ **OceanWP**
- ✅ **Generic Theme Support** (Universal selectors as fallback)

### Theme-Specific Optimizations

Each supported theme gets:
- 🎯 **Precise content detection** using theme-specific CSS selectors
- 🎨 **Color integration** with theme variables when available
- 📱 **Responsive breakpoints** matching theme defaults
- ⚡ **Performance optimization** for theme-specific layouts

## 🌍 Translations

Currently available in:

- 🇬🇧 **English** (default)
- 🇪🇸 **Spanish** (es_ES)
- 🇫🇷 **French** (fr_FR)
- 🇩🇪 **German** (de_DE)

### Contributing Translations

We welcome translations! See [TRANSLATION.md](TRANSLATION.md) for detailed instructions.

**Priority languages needed:**
- 🇮🇹 Italian, 🇵🇹 Portuguese, 🇳🇱 Dutch, 🇷🇺 Russian, 🇵🇱 Polish, 🇯🇵 Japanese, 🇰🇷 Korean, 🇨🇳 Chinese

## 📊 Analytics Integration

Built-in support for popular analytics platforms:

- **Google Analytics 4** - Tracks engagement events
- **Universal Analytics** (legacy) - Backward compatibility
- **Facebook Pixel** - Custom "ReadMore" events
- **Custom Events** - Extensible for other platforms

### Event Data Tracked

```javascript
// Google Analytics 4 example
gtag('event', 'engagement', {
    event_category: 'Content',
    event_label: 'Read More Clicked',
    value: 1
});
```

## 🔧 Developer Guide

### Hooks & Filters

```php
// Customize minimum character threshold
add_filter('rmu_min_characters', function($min) {
    return 400; // Require 400+ characters
});

// Modify button text programmatically
add_filter('rmu_button_text', function($text) {
    return '🔥 Continue Reading';
});
```

### CSS Customization

```css
/* Override button styles */
.rmu-button {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24) !important;
    font-size: 1.1rem !important;
    padding: 1.2rem 2.5rem !important;
}

/* Customize fade gradient */
.rmu-content.truncated::after {
    background: linear-gradient(transparent, #f8f9fa) !important;
}
```

### Debug Mode

Enable debug mode to see detailed console output:

1. Go to **Settings → Read More Universal**
2. Check **"Enable debug messages"**
3. Open browser console (F12) on any post
4. See detailed selector detection and processing logs

## 🐛 Troubleshooting

### Common Issues

**❓ Button doesn't appear**
- Check post has 250+ characters
- Enable debug mode to see console logs
- Verify theme compatibility

**❓ Content doesn't expand**
- Check for JavaScript errors in console
- Ensure no conflicting plugins
- Try disabling other "read more" plugins

**❓ Wrong theme detected**
- Plugin shows detected theme in settings
- Generic fallback works with most themes
- Custom CSS selectors can be added via support

### Getting Help

1. **Enable debug mode** and check console
2. **Check theme compatibility** in plugin settings
3. **Review browser console** for JavaScript errors
4. **Open an issue** on GitHub with details

## 📈 Performance

- ⚡ **Zero database queries** added
- 🏃 **Loads only on single posts** - no homepage impact
- 📦 **Lightweight CSS/JS** - ~3KB total
- 🎯 **Smart loading** - only when content meets criteria
- 🚀 **No external dependencies** - everything self-contained

## 🔐 Security

- ✅ **Sanitized inputs** - All user data properly escaped
- ✅ **Nonce verification** - CSRF protection on settings
- ✅ **Capability checks** - Admin functions protected
- ✅ **XSS prevention** - Output properly escaped
- ✅ **SQL injection safe** - No direct database queries

## 📋 Requirements

- **WordPress:** 5.0 or higher
- **PHP:** 7.4 or higher
- **Browser:** Modern browsers (IE11+ support)

### Recommended

- **WordPress:** 6.7+ (for best Twenty Twenty-Five support)
- **PHP:** 8.0+ (for optimal performance)

## 🤝 Contributing

We welcome contributions! Here's how to help:

### Code Contributions

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/amazing-feature`
3. **Commit** changes: `git commit -m 'Add amazing feature'`
4. **Push** to branch: `git push origin feature/amazing-feature`
5. **Submit** a Pull Request

### Translation Contributions

See [TRANSLATION.md](TRANSLATION.md) for detailed translation guidelines.

### Bug Reports

Use GitHub Issues with:
- WordPress version
- Theme name and version
- Plugin version
- Console errors (if any)
- Steps to reproduce

## 📊 Roadmap

### Version 1.1
- [ ] Gutenberg block integration
- [ ] Custom post type support
- [ ] Advanced animation options
- [ ] Theme builder integration (Elementor Pro, Beaver Builder)

### Version 1.2
- [ ] A/B testing capabilities
- [ ] Heat map tracking
- [ ] Multiple content truncation points
- [ ] AI-powered content summarization

### Future Considerations
- [ ] AMP compatibility
- [ ] WooCommerce product description support
- [ ] bbPress/BuddyPress integration
- [ ] REST API endpoints

## 📄 License

This project is licensed under the **GPL v2 or later** - see the [LICENSE](LICENSE) file for details.

```
Read More Universal - WordPress Plugin
Copyright (C) 2025 David Carrero Fernández-Baillo

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## 👤 Author

**David Carrero Fernández-Baillo**
- 🌐 Website: [carrero.es](https://carrero.es)
- 📧 Email: david@carrero.es
- 🐙 GitHub: [@dcarrero](https://github.com/dcarrero)

## 🙏 Acknowledgments

- WordPress community for theme structure insights
- Contributors and translators
- Beta testers across different themes
- Inspiration from various "read more" implementations

## ⭐ Support the Project

If you find this plugin useful:

- ⭐ **Star** the repository
- 🐛 **Report bugs** and suggest features
- 🌍 **Contribute translations**
- 📢 **Share** with the WordPress community
- ☕ **Buy me a coffee** (link in profile)

---

**Made with ❤️ for the WordPress community**

*Compatible with WordPress 6.7+ and the new Twenty Twenty-Five theme*
