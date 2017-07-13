<?php
/**
 * Plugin Name: Simple Zippyshare Embed
 * Plugin URI: http://it-maniak.pl
 * Description: Replace all Zippyshare links to embed media.
 * Version: 1.7
 * Author: Adam Stachowicz
 * Author URI: http://it-maniak.pl
 * License: GPLv2
 */

/*  Copyright 2014  Adam Stachowicz  (email : saibamenppl@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Activation
register_activation_hook( __FILE__, 'zippy_set_up_options' );

// Uninstall
register_uninstall_hook( __FILE__, 'zippy_delete_options' );

// Set default zippy values
function zippy_set_up_options() {
    add_option( 'zippydownbutt', 'above' );
    add_option( 'zippyvol', '80' );
    add_option( 'zippyauto', '0' );
    add_option( 'zippywidth', '850' );
    // Colors
    add_option( 'zippytext', '#000000' );
    add_option( 'zippyback', '#e8e8e8' );
    add_option( 'zippyplay', '#ff6600' );
    add_option( 'zippywave', '#000000' );
    add_option( 'zippyborder', '#cccccc' );
}

// Cleaning after uninstall
function zippy_delete_options() {
    delete_option( 'zippydownbutt' );
    delete_option( 'zippyvol' );
    delete_option( 'zippyauto' );
    delete_option( 'zippywidth' );
    delete_option( 'zippytext' );
    delete_option( 'zippyback' );
    delete_option( 'zippyplay' );
    delete_option( 'zippywave' );
    delete_option( 'zippyborder' );
}

function zippy_replace_links_to_embed( $the_content ) {
    // Searching for Zippyshare links and attach it into $matches
    preg_match_all( "#http:\/\/www(.*?).zippyshare.com\/v\/([a-zA-Z0-9]*)\/file.html#", $the_content, $matches, PREG_SET_ORDER );

    if ( $matches ) {

        $zippydownbutt = esc_attr( get_option( 'zippydownbutt', 'above' ) );
        $zippydownimg = plugins_url( '/images/download_button.png', __FILE__ );
        $zippyvol = esc_attr( get_option( 'zippyvol', '80' ) );
        $zippywidth = esc_attr( get_option( 'zippywidth', '850' ) );
        $zippytext = esc_attr( get_option( 'zippytext', '#000000' ) );
        $zippyback = esc_attr( get_option( 'zippyback', '#e8e8e8' ) );
        $zippyplay = esc_attr( get_option( 'zippyplay', '#ff6600' ) );
        $zippywave = esc_attr( get_option( 'zippywave', '#000000' ) );
        $zippyborder = esc_attr( get_option( 'zippyborder', '#cccccc' ) );

        if ( esc_attr( get_option( 'zippyauto', '0' ) ) === '1' ) {
            $zippyauto = 'true';
        }
        else {
            $zippyauto = 'false';
        }

        foreach ( $matches as $data ) {
            // Understanding the $data
            $zippylink = esc_attr( $data[0] );
            $zippywww = esc_attr( $data[1] );
            $zippyfile = esc_attr( $data[2] );

            // Modify content
            // Button above
            if ( $zippydownbutt === 'above' ) {
                $the_content = str_replace( $zippylink, '<div style="text-align:center;"><a href="'. $zippylink .'"><img align="middle" style="margin-left:auto; margin-right:auto;" src="'. $zippydownimg .'" /></a></div><br />'
                        . '<script type="text/javascript">var zippywww="'. $zippywww .'";var zippyfile="'. $zippyfile .'";var zippytext="'. $zippytext .'";var zippyback="'. $zippyback .'";var zippyplay="'. $zippyplay .'";var zippywidth='. $zippywidth .';var zippyauto='. $zippyauto .';var zippyvol='. $zippyvol .';var zippywave = "'. $zippywave .'";var zippyborder = "'. $zippyborder .'";</script><script type="text/javascript" src="http://api.zippyshare.com/api/embed_new.js"></script>', $the_content );
            }
            // Button under
            elseif ( $zippydownbutt === 'under' ) {
                $the_content = str_replace( $zippylink, '<script type="text/javascript">var zippywww="'. $zippywww .'";var zippyfile="'. $zippyfile .'";var zippytext="'. $zippytext .'";var zippyback="'. $zippyback .'";var zippyplay="'. $zippyplay .'";var zippywidth='. $zippywidth .';var zippyauto='. $zippyauto .';var zippyvol='. $zippyvol .';var zippywave = "'. $zippywave .'";var zippyborder = "'. $zippyborder .'";</script><script type="text/javascript" src="http://api.zippyshare.com/api/embed_new.js"></script>'
                        .'<div style="text-align:center;"><a href="'. $zippylink .'"><img align="middle" style="margin-left:auto; margin-right:auto;" src="'. $zippydownimg .'" /></a></div>', $the_content );
            }
            // No download button
            else {
                $the_content = str_replace( $zippylink, '<script type="text/javascript">var zippywww="'. $zippywww .'";var zippyfile="'. $zippyfile .'";var zippytext="'. $zippytext .'";var zippyback="'. $zippyback .'";var zippyplay="'. $zippyplay .'";var zippywidth='. $zippywidth .';var zippyauto='. $zippyauto .';var zippyvol='. $zippyvol .';var zippywave = "'. $zippywave .'";var zippyborder = "'. $zippyborder .'";</script><script type="text/javascript" src="http://api.zippyshare.com/api/embed_new.js"></script>', $the_content );
            }
        }
    }

    // Return changed or unchanged content
    return $the_content;
}

// Activate plugin when we see the_content
add_action( 'the_content', 'zippy_replace_links_to_embed' );

function zippy_create_menu() {
    // Create new options page
    add_options_page( 'Simple Zippyshare Embed options', 'Simple Zippyshare Embed', 'administrator', __FILE__, 'zippy_settings_page' );

    // Call register settings function
    add_action( 'admin_init', 'zippy_register_settings' );
}

add_action( 'admin_menu', 'zippy_create_menu' );

function zippy_register_settings() {
    register_setting( 'zippy-settings-group', 'zippydownbutt' );
    register_setting( 'zippy-settings-group', 'zippyvol' );
    register_setting( 'zippy-settings-group', 'zippyauto' );
    register_setting( 'zippy-settings-group', 'zippywidth' );
    register_setting( 'zippy-settings-group', 'zippytext' );
    register_setting( 'zippy-settings-group', 'zippyback' );
    register_setting( 'zippy-settings-group', 'zippyplay' );
    register_setting( 'zippy-settings-group', 'zippywave' );
    register_setting( 'zippy-settings-group', 'zippyborder' );
}

// Localization
function zippy_translations_init() {
    load_plugin_textdomain( 'simple-zippyshare-embed', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'zippy_translations_init' );

function zippy_settings_page() {
?>
    <div class="wrap">
    <h2>Simple Zippyshare Embed</h2>
    <?php echo __( 'Colors must be in HTML (HEX) format', 'simple-zippyshare-embed' ); ?>

    <form method="post" action="options.php">
        <?php
        settings_fields( 'zippy-settings-group' );
        do_settings_sections( 'zippy-settings-group' );

        $zippydownbutt = esc_attr( get_option( 'zippydownbutt', 'above' ) );
        ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php echo __( 'Download Button', 'simple-zippyshare-embed' ); ?></th>
            <td><select name="zippydownbutt">
                    <option value="none" <?php selected( $zippydownbutt, 'none' ); ?> ><?php echo __( 'None', 'simple-zippyshare-embed' ); ?></option>
                    <option value="above" <?php selected( $zippydownbutt, 'above' ); ?> ><?php echo __( 'Above', 'simple-zippyshare-embed' ); ?></option>
                    <option value="under" <?php selected( $zippydownbutt, 'under' ); ?> ><?php echo __( 'Under', 'simple-zippyshare-embed' ); ?></option>
                </select></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __( 'Default volume', 'simple-zippyshare-embed' ); ?></th>
                <td><input type="number" name="zippyvol" value="<?php echo esc_attr( get_option( 'zippyvol', '80' ) ); ?>" required /> %</td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __( 'Autoplay', 'simple-zippyshare-embed' ); ?></th>
                <td><input type="checkbox" name="zippyauto" value="1" <?php checked( '1', get_option( 'zippyauto', '0' ) ); ?> /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __( 'Width', 'simple-zippyshare-embed' ); ?></th>
                <td><input type="number" min="60" name="zippywidth" value="<?php echo esc_attr( get_option( 'zippywidth', '850' ) ); ?>" required /> px</td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __( 'Text and Waveform Progress Color', 'simple-zippyshare-embed' ); ?></th>
                <td><input type="color" name="zippytext" value="<?php echo esc_attr( get_option( 'zippytext', '#000000' ) ); ?>" pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __( 'Background Color', 'simple-zippyshare-embed' ); ?></th>
                <td><input type="color" name="zippyback" value="<?php echo esc_attr( get_option( 'zippyback', '#e8e8e8' ) ); ?>" pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __( 'Play and Full Waveform Color', 'simple-zippyshare-embed' ); ?></th>
                <td><input type="color" name="zippyplay" value="<?php echo esc_attr( get_option( 'zippyplay', '#ff6600' ) ); ?>" pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __( 'Waveform Color', 'simple-zippyshare-embed' ); ?></th>
                <td><input type="color" name="zippywave" value="<?php echo esc_attr( get_option( 'zippywave', '#000000' ) ); ?>" pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __( 'Border Color', 'simple-zippyshare-embed' ); ?></th>
                <td><input type="color" minlength="4" name="zippyborder" value="<?php echo esc_attr( get_option( 'zippyborder', '#cccccc' ) ); ?>" pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
            </tr>
        </table>

        <?php

        // Security
        wp_nonce_field( 'zippy_form_check', 'zippy_check' );

        // Compatibility check
        if ( get_bloginfo( 'version' ) >= 3.1 ) {
            submit_button();
        }
        else {?>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __( 'Save Changes', 'simple-zippyshare-embed' ); ?>" /></p>
        <?php } ?>

    </form>

    <?php echo __( 'Plugin by', 'simple-zippyshare-embed' ); ?> <a href="http://it-maniak.pl">Saibamen</a>
    <br /><br /><br /><br />

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="62WX9NREDGCBG">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
    </form>


    </div>
<?php
}

// Widget
class zippy_widget extends WP_Widget {

    // Constructor
    function zippy_widget() {
        parent::WP_Widget( false, $name = __( 'Zippyshare Profile Widget', 'simple-zippyshare-embed' ) );
    }

    // Widget form creation
    function form( $instance ) {
        // Check values
        if ( $instance ) {
            $title = esc_attr( $instance['title'] );
            $profile_url = esc_url( $instance['profile_url'] );
        }
        else {
            $title = '';
            $profile_url = '';
        }
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'simple-zippyshare-embed' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'profile_url' ); ?>"><?php _e( 'Zippyshare Profile URL:', 'simple-zippyshare-embed' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'profile_url' ); ?>" name="<?php echo $this->get_field_name( 'profile_url' ); ?>" type="url" value="<?php echo $profile_url; ?>" />
        </p>
<?php
    }

    // Widget update
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        // Fields
        $instance['title'] = esc_attr( $new_instance['title'] );
        $instance['profile_url'] = esc_url_raw( $new_instance['profile_url'] );
        return $instance;
    }

    // Widget display
    function widget( $args, $instance ) {
        extract( $args );
        // Widget options
        $title = apply_filters( 'widget_title', $instance['title'] );
        $profile_url = $instance['profile_url'];

        echo $before_widget;
        // Display the widget
        echo '<div class="widget-text wp_widget_plugin_box">';

        // Check if title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        // Check if profile_url is set
        if ( $profile_url ) {
            $zippy_profile = file_get_contents( $profile_url );

            // Avatar
            preg_match("'<img alt=\"avatar\" src=\"/(.*?)\" (.*?)/>'si", $zippy_profile, $profileavatar);
            if( $profileavatar ) {
                echo '<a href="'. $profile_url. '"><img alt="avatar" src="http://www.zippyshare.com/'. $profileavatar[1] .'" height="100" width="100"/></a>';
            }

            // Profile name
            preg_match("'<font class=\"profilename\"><a href=\"/(.*?)\">(.*?)</a></font>'si", $zippy_profile, $profilename);
            if( $profilename ) {
                echo '<h4><a href="'. $profile_url. '">'. $profilename[2] .'</a></h4>';
            }

            // Previews and downloads
            preg_match("'title=\"Number of Previews\"> ([0-9]*) \|\ (.*?)title=\"Number of Downloads\"> ([0-9]*)</font>'si", $zippy_profile, $profilestats);
            if( $profilestats ) {
                echo '<p class="wp_widget_plugin_text">'. __( 'Total Previews:', 'simple-zippyshare-embed' ) .' '. $profilestats[1]. '<br />'
                        . __( 'Total Downloads:', 'simple-zippyshare-embed' ) .' '. $profilestats[3] .'</p>';
            }
        }

        echo '</div>';
        echo $after_widget;
    }

}

// Register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "zippy_widget" );' ) );
