<?php


final class MesmerizePRO_Updater
{
    public static function init()
    {
        add_action('load-update.php', array(__CLASS__, 'set_hooks'));
    }
    
    public static function set_hooks()
    {
        include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
        
        add_action('admin_action_upload-theme', array(__CLASS__, 'update_theme'));
    }
    
    public static function update_theme()
    {
        if ( ! current_user_can('upload_themes')) {
            wp_die(esc_html__('Sorry, you are not allowed to install themes on this site.'));
        }
        
        check_admin_referer('theme-upload');
        
        $file_upload = new File_Upload_Upgrader('themezip', 'package');
        
        wp_enqueue_script('customize-loader');
        
        $title        = __('Upload Theme');
        $parent_file  = 'themes.php';
        $submenu_file = 'theme-install.php';
        
        require_once(ABSPATH . 'wp-admin/admin-header.php');
        
        $title = sprintf(__('Installing Theme from uploaded file: %s'), esc_html(basename($file_upload->filename)));
        $nonce = 'theme-upload';
        $url   = add_query_arg(array('package' => $file_upload->id), 'update.php?action=upload-theme');
        $type  = 'upload'; // Install plugin type, From Web or an Upload.
        
        require_once(dirname(__FILE__) . '/theme-updater.php');
        
        $upgrader = new MesmerizePro_ThemeUpdater(new Theme_Installer_Skin(compact('type', 'title', 'nonce', 'url')));
        $result   = $upgrader->install($file_upload->package);
        
        if ($result || is_wp_error($result)) {
            $file_upload->cleanup();
        }
        
        include(ABSPATH . 'wp-admin/admin-footer.php');
        
        exit();
    }
}

MesmerizePRO_Updater::init();
