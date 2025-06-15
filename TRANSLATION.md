# 🌍 Translation Guide for Read More Universal

This plugin uses WordPress's standard translation system with gettext `.po` and `.mo` files.

## 📂 File Structure

```
read-more-universal/
├── read-more-universal.php          # Main plugin file
├── read-more-universal.pot          # Translation template
├── read-more-universal-es_ES.po     # Spanish translation
├── read-more-universal-fr_FR.po     # French translation  
├── read-more-universal-de_DE.po     # German translation
├── compile-translations.sh          # Compilation script
└── languages/                       # Compiled .mo files
    ├── read-more-universal-es_ES.mo
    ├── read-more-universal-fr_FR.mo
    └── read-more-universal-de_DE.mo
```

## 🛠️ How to Add a New Translation

### Method 1: Using Poedit (Recommended)

1. **Download Poedit**: https://poedit.net/
2. **Open the template**: Load `read-more-universal.pot`
3. **Create new translation**: Choose your language
4. **Translate strings**: Fill in all translations
5. **Save**: Save as `read-more-universal-[locale].po` (e.g., `read-more-universal-it_IT.po`)
6. **Compile**: Poedit will automatically create the `.mo` file

### Method 2: Manual editing

1. **Copy template**: `cp read-more-universal.pot read-more-universal-[locale].po`
2. **Edit header**: Update language information in the file header
3. **Translate**: Replace all `msgstr ""` with your translations
4. **Compile**: Run `./compile-translations.sh`

## 🗣️ Available Languages

Currently included:
- 🇬🇧 **English** (default)
- 🇪🇸 **Spanish** (es_ES)
- 🇫🇷 **French** (fr_FR)
- 🇩🇪 **German** (de_DE)

## 🎯 Priority Languages Needed

We need translations for these popular WordPress locales:

### European Languages
- 🇮🇹 **Italian** (it_IT)
- 🇵🇹 **Portuguese** (pt_PT, pt_BR)
- 🇳🇱 **Dutch** (nl_NL)
- 🇷🇺 **Russian** (ru_RU)
- 🇵🇱 **Polish** (pl_PL)
- 🇸🇪 **Swedish** (sv_SE)

### Regional Spanish
- 🇲🇽 **Mexican Spanish** (es_MX)
- 🇦🇷 **Argentinian Spanish** (es_AR)

### Asian Languages
- 🇯🇵 **Japanese** (ja)
- 🇰🇷 **Korean** (ko_KR)
- 🇨🇳 **Chinese Simplified** (zh_CN)
- 🇹🇼 **Chinese Traditional** (zh_TW)

## 📝 Translation Strings

The plugin has only **15 strings** to translate:

1. Plugin name and menu items
2. Settings page labels and descriptions
3. The main button text: `"📖 Read full article"`
4. Theme information labels

## 🔄 Compilation Process

### Automatic (recommended)
```bash
chmod +x compile-translations.sh
./compile-translations.sh
```

### Manual
```bash
# For each language
msgfmt -o languages/read-more-universal-es_ES.mo read-more-universal-es_ES.po
msgfmt -o languages/read-more-universal-fr_FR.mo read-more-universal-fr_FR.po
msgfmt -o languages/read-more-universal-de_DE.mo read-more-universal-de_DE.po
```

## 🚀 How Translations Work

1. **WordPress detects locale**: Based on `WPLANG` or user settings
2. **Plugin loads translation**: Automatically looks for matching `.mo` file
3. **Fallback to English**: If no translation found, uses English defaults

## 🤝 Contributing Translations

1. **Fork the repository**
2. **Add your translation** following the naming convention
3. **Test your translation** in a WordPress installation
4. **Submit a pull request**

### Translation Guidelines

- **Keep emojis**: The 📖 emoji in button text should be preserved
- **Maintain formality**: Use appropriate formality level for your language
- **Test in context**: Make sure translations fit in the UI
- **Check character limits**: Some languages need more space

## ✅ Testing Your Translation

1. **Install plugin** with your translation files
2. **Change WordPress language** to your locale
3. **Check admin panel**: Go to Settings > Read More Universal
4. **Test frontend**: Create a long post and verify button text
5. **Debug mode**: Enable debug mode to see console messages

## 📧 Support

For translation questions:
- **Email**: david@carrero.es
- **GitHub Issues**: https://github.com/dcarrero/read-more-universal/issues
- **Label your issue**: Add `translation` label

## 🏆 Credits

Translations by:
- 🇪🇸 Spanish: David Carrero Fernández-Baillo
- 🇫🇷 French: David Carrero Fernández-Baillo  
- 🇩🇪 German: David Carrero Fernández-Baillo

Want your name here? Contribute a translation! 🌟
