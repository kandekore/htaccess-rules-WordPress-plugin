<?php
/*
Plugin Name: .htaccess Rules
Description: Allows you to add custom rules to your `.htaccess` file directly from your WordPress admin panel.
Version:     1.0
Author:      D.Kandekore
*/

class My_Htaccess_Rules {
    const OPTION_NAME = 'my_htaccess_rules_enabled';
    const OPTION_COMPRESSION = 'my_htaccess_compression_enabled';
    const OPTION_CACHING = 'my_htaccess_caching_enabled';

    function __construct() {
        register_activation_hook( __FILE__, array( $this, 'activation' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
        register_uninstall_hook( __FILE__, array( 'My_Htaccess_Rules', 'uninstall' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_filter( 'mod_rewrite_rules', array( $this, 'add_rules' ) );
    }

    function activation() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $this->insert_rules();
    }

    function deactivation() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $this->remove_rules();
    }

    static function uninstall() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        self::remove_rules();
        self::restore_backup();
        delete_option( self::OPTION_NAME );
        delete_option( self::OPTION_COMPRESSION );
        delete_option( self::OPTION_CACHING );
    }
    
    static function restore_backup() {
        $htaccess_file = ABSPATH . '.htaccess';
        $backup_file = ABSPATH . '.htaccess.bak'; // Backup file
        if ( file_exists( $backup_file ) && is_readable( $backup_file ) && is_writable( ABSPATH ) ) {
            // Copy .htaccess.bak to .htaccess
            copy($backup_file, $htaccess_file);
        }
    }
    

    function admin_menu() {
        add_options_page( 'My Htaccess Rules Settings', 'My Htaccess Rules', 'manage_options', 'my-htaccess-rules', array( $this, 'settings_page' ) );
        wp_enqueue_script( 'my-htaccess-rules-js', plugin_dir_url( __FILE__ ) . 'my-htaccess-rules.js', array(), '1.0', true );
    }

    function settings_page() {
        if ( isset( $_POST[self::OPTION_NAME] ) ) {
            update_option( self::OPTION_NAME, intval( $_POST[self::OPTION_NAME] ) );
            update_option( self::OPTION_COMPRESSION, isset( $_POST[self::OPTION_COMPRESSION] ) ? 1 : 0 );
            update_option( self::OPTION_CACHING, isset( $_POST[self::OPTION_CACHING] ) ? 1 : 0 );
            $this->insert_rules();
            echo '<div class="updated"><p>Settings updated</p></div>';
        }
        $enabled = get_option( self::OPTION_NAME, 0 );
        $compression_enabled = get_option( self::OPTION_COMPRESSION, 0 );
        $caching_enabled = get_option( self::OPTION_CACHING, 0 );
        ?>
        <div class="wrap">
        <style>
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }
    
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }
    
    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }
    
    input:checked + .slider {
      background-color: #2196F3;
    }
    
    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }
    
    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }
    
    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }
    
    .slider.round:before {
      border-radius: 50%;
    }
    </style>
            <h2>My Htaccess Rules Settings</h2>
            <form method="POST">
                <label class="switch">
                    <input type="checkbox" id="main-switch" name="<?php echo self::OPTION_NAME; ?>" value="1" <?php checked( $enabled ); ?>>
                    <span class="slider round"></span>
                   
                </label><h3> Enable Plugin</h3>
                <p>Turn this on to enable the My Htaccess Rules plugin and create a backup of your .htaccess file (this backup will be restored upon deactivation of this plugin)</p>
                <br>
                <label class="switch">
                    <input type="checkbox" class="dependent-checkbox" name="<?php echo self::OPTION_COMPRESSION; ?>" value="1" <?php checked( $compression_enabled ); echo ($enabled ? '' : 'disabled'); ?>>
                    <span class="slider round"></span>
                   
                </label><h3>Enable Compression</h3>
                <p>Compresses your site's HTML, CSS, JavaScript, and more to improve loading times.</p>
                <br>
                <label class="switch">
                    <input type="checkbox" class="dependent-checkbox" name="<?php echo self::OPTION_CACHING; ?>" value="1" <?php checked( $caching_enabled ); echo ($enabled ? '' : 'disabled'); ?>>
                    <span class="slider round"></span>
                  
                </label><h3>  Enable Leverage Browser Caching</h3>
                <p>Sets expiration times for certain types of content, reducing server load and improving performance.</p>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    function insert_rules() {
        $enabled = get_option( self::OPTION_NAME, 0 );
        if ( $enabled ) {
            // Before flushing the rules, we backup the current .htaccess
            $this->backup_htaccess();
    
            flush_rewrite_rules();
        }
    }
    
    function backup_htaccess() {
        $htaccess_file = ABSPATH . '.htaccess';
        $backup_file = ABSPATH . '.htaccess.bak'; // Backup file
        if ( file_exists( $htaccess_file ) && is_readable( $htaccess_file ) && is_writable( ABSPATH ) ) {
            // Copy .htaccess to .htaccess.bak
            copy($htaccess_file, $backup_file);
        }
    }
    

    function add_rules( $rules ) {
        $enabled = get_option( self::OPTION_NAME, 0 );
        if ( $enabled ) {
            $compression_enabled = get_option( self::OPTION_COMPRESSION, 0 );
            $caching_enabled = get_option( self::OPTION_CACHING, 0 );

            $my_rules = '# BEGIN My Custom Rules' . "\n";

            if ( $compression_enabled ) {
                $my_rules .= <<<EOD
<IfModule mod_deflate.c>
  # Compress HTML, CSS, JavaScript, Text, XML and fonts
  AddOutputFilterByType DEFLATE application/javascript
  AddOutputFilterByType DEFLATE application/rss+xml
  AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
  AddOutputFilterByType DEFLATE application/x-font
  AddOutputFilterByType DEFLATE application/x-font-opentype
  AddOutputFilterByType DEFLATE application/x-font-otf
  AddOutputFilterByType DEFLATE application/x-font-truetype
  AddOutputFilterByType DEFLATE application/x-font-ttf
  AddOutputFilterByType DEFLATE application/x-javascript
  AddOutputFilterByType DEFLATE application/xhtml+xml
  AddOutputFilterByType DEFLATE application/xml
  AddOutputFilterByType DEFLATE font/opentype
  AddOutputFilterByType DEFLATE font/otf
  AddOutputFilterByType DEFLATE font/ttf
  AddOutputFilterByType DEFLATE image/svg+xml
  AddOutputFilterByType DEFLATE image/x-icon
  AddOutputFilterByType DEFLATE text/css
  AddOutputFilterByType DEFLATE text/html
  AddOutputFilterByType DEFLATE text/javascript
  AddOutputFilterByType DEFLATE text/plain
  AddOutputFilterByType DEFLATE text/xml

  # Remove browser bugs (only needed for really old browsers)
  BrowserMatch ^Mozilla/4 gzip-only-text/html
  BrowserMatch ^Mozilla/4\.0[678] no-gzip
  BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
  Header append Vary User-Agent
  </IfModule>\n
EOD;
            }

            if ( $caching_enabled ) {
                $my_rules .= <<<EOD
<IfModule mod_expires.c>
  ExpiresActive On

 # Images
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType image/svg+xml "access plus 1 year"
  ExpiresByType image/x-icon "access plus 1 year"

  # Video
  ExpiresByType video/webm "access plus 1 year"
  ExpiresByType video/mp4 "access plus 1 year"
  ExpiresByType video/mpeg "access plus 1 year"

  # Fonts
  ExpiresByType font/ttf "access plus 1 year"
  ExpiresByType font/otf "access plus 1 year"
  ExpiresByType font/woff "access plus 1 year"
  ExpiresByType font/woff2 "access plus 1 year"
  ExpiresByType application/font-woff "access plus 1 year"

  # CSS, JavaScript
  ExpiresByType text/css "access plus 1 year"
  ExpiresByType text/javascript "access plus 1 year"
  ExpiresByType application/javascript "access plus 1 year"

  # Others
  ExpiresByType application/pdf "access plus 1 year"
  ExpiresByType image/vnd.microsoft.icon "access plus 1 year"
  </IfModule>\n
EOD;
            }

            $my_rules .= '# END My Custom Rules' . "\n";

            $rules = $my_rules . $rules;
        }
        return $rules;
    }

    static function remove_rules() {
        $htaccess_file = ABSPATH . '.htaccess';
        if ( file_exists( $htaccess_file ) && is_writable( $htaccess_file ) ) {
            $contents = file_get_contents( $htaccess_file );
            $pattern = '/# BEGIN My Custom Rules.*# END My Custom Rules\n/s';
            $new_contents = preg_replace( $pattern, '', $contents );
            if ( $new_contents !== null && $new_contents !== $contents ) {
                file_put_contents( $htaccess_file, $new_contents );
            }
        }
    }
}

new My_Htaccess_Rules();
