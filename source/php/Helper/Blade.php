<?php

namespace ProjectManagerIntegration\Helper;

use BadFunctionCallException;

class Blade
{
    public static function render(string $view, array $data): string
    {
        $view = str_replace('.blade.php', '', $view);

        $viewPaths = array(
            PROJECTMANAGERINTEGRATION_PATH,
            PROJECTMANAGERINTEGRATION_VIEW_PATH,
        );

        if (!function_exists('render_blade_view')) {
            throw new BadFunctionCallException(
                'Blade render method cannot be called before Municipio has been initialized.'
            );
        }

        return \render_blade_view($view, $data, $viewPaths);
    }
}
