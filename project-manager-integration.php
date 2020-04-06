<?php

/**
 * Plugin Name:       Project Manager Integration
 * Plugin URI:        (#plugin_url#)
 * Description:       Imports and displays projects from Project Manager API.
 * Version:           1.0.0
 * Author:            Jonatan Hanson
 * Author URI:        (#plugin_author_url#)
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       project-manager-integration
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('PROJECTMANAGERINTEGRATION_PATH', plugin_dir_path(__FILE__));
define('PROJECTMANAGERINTEGRATION_URL', plugins_url('', __FILE__));
define('PROJECTMANAGERINTEGRATION_TEMPLATE_PATH', PROJECTMANAGERINTEGRATION_PATH . 'templates/');
define('PROJECTMANAGERINTEGRATION_TEXTDOMAIN', 'project-manager-integration');

load_plugin_textdomain('project-manager-integration', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once PROJECTMANAGERINTEGRATION_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once PROJECTMANAGERINTEGRATION_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new ProjectManagerIntegration\Vendor\Psr4ClassLoader();
$loader->addPrefix('ProjectManagerIntegration', PROJECTMANAGERINTEGRATION_PATH);
$loader->addPrefix('ProjectManagerIntegration', PROJECTMANAGERINTEGRATION_PATH . 'source/php/');
$loader->register();

// Start application
new ProjectManagerIntegration\App();
