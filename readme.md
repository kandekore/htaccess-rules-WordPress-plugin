# .htaccess Rules Plugin

The **.htaccess Rules** plugin allows you to add custom rules to your `.htaccess` file directly from your WordPress admin panel. The plugin provides a user-friendly interface for controlling specific rules like compression and caching.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Author](#author)
- [License](#license)
- [Support](#support)
- [Contributing](#contributing)

## Features

- Enable or disable the entire plugin
- Enable or disable compression rules
- Enable or disable caching rules
- Backup and restore of the `.htaccess` file

## Requirements

This plugin requires your server to be running Apache with the `mod_rewrite`, `mod_deflate`, and `mod_expires` modules enabled.

> **Warning**: Editing the `.htaccess` file can break your website if not done correctly. We highly recommend taking a backup of your `.htaccess` file before using this plugin.

## Installation

1. Download and extract the plugin files
2. Upload the extracted folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

After activating the plugin, you will find a new settings page under the "Settings" menu in your WordPress admin area. Here you can:

- Enable the plugin entirely by checking the "Enable .htaccess Rules" box
- Enable compression rules by checking the "Enable Compression Rules" box
- Enable caching rules by checking the "Enable Caching Rules" box

Once you have made your selections, click on the "Save Changes" button to apply the rules to your `.htaccess` file.

This plugin includes a convenient backup and restore function for your `.htaccess` file. The plugin will automatically create a backup of your `.htaccess` file before any rules are applied. If something goes wrong, you can use the restore function to revert your `.htaccess` file back to its previous state. This is an additional safety measure to ensure your site remains functional, even if a rule causes unexpected issues.

> **Note**: Despite the automated backup, we recommend taking your own manual backup of the `.htaccess` file as well, especially if you have other custom rules in place.

## Author

- D.Kandekore

## License

This project is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

## Support

If you encounter any issues with this plugin, please contact the author or open an issue on the plugin's repository.

## Contributing

Contributions are welcome. If you'd like to contribute, please fork the repository and submit a pull request.
