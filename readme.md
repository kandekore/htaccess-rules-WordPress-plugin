# My Htaccess Rules Plugin

The **My Htaccess Rules** plugin allows you to add custom rules to your `.htaccess` file directly from your WordPress admin panel. The plugin provides a simple user interface that lets you control specific rules like compression and caching rules.

## Features

- Add custom rules to your `.htaccess` file.
- Enable or disable compression rules.
- Enable or disable caching rules.

## Installation

1. Download and extract the plugin files.
2. Upload the extracted folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

After activating the plugin, you will find a new settings page under the "Settings" menu in your WordPress admin area. Navigate to "My Htaccess Rules" settings page, here you can:

- Enable the plugin entirely by checking the "Enable My Htaccess Rules" box.
- Enable compression rules by checking the "Enable Compression Rules" box. This will help compress various types of content before sending them to the browser, thus reducing load times.
- Enable caching rules by checking the "Enable Caching Rules" box. This will set the browser caching policy for different types of content to help reduce server load and improve website speed.

After making your selection, click on the "Save Changes" button to apply the rules to your `.htaccess` file.

> **Note:** The plugin modifies your `.htaccess` file directly, so it's a good idea to keep a backup of your `.htaccess` file before using this plugin. Make sure your `.htaccess` file is writable by WordPress for the plugin to work properly.

## Changelog

### Version 1.0

- Initial release.

## Author

- D.Kandekore

## License

This project is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

## Support

If you encounter any issues with this plugin, please contact the author or open an issue on the plugin's repository.

## Contributing

Contributions are welcome. If you'd like to contribute, please fork the repository and submit a pull request.
