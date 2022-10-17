<?php

// Get around direct access blockers.
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/../../../');
}

define('PROJECTMANAGERINTEGRATION_PATH', __DIR__ . '/../../../');
define('PROJECTMANAGERINTEGRATION_URL', 'https://example.com/wp-content/plugins/' . 'api-project-manager-integration');
define('PROJECTMANAGERINTEGRATION_TEMPLATE_PATH', PROJECTMANAGERINTEGRATION_PATH . 'templates/');


// Register the autoloader
$loader = require __DIR__ . '/../../../vendor/autoload.php';
$loader->addPsr4('ProjectManagerIntegration\\Test\\', __DIR__ . '/../php/');

require_once __DIR__ . '/PluginTestCase.php';
