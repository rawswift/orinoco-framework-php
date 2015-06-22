<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2015 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class AutoLoad
{
    /**
     * Try to autoload framework related class
     *
     * @param Class name $class_name
     * @return void
     */
    public static function autoLoadFramework($class_name)
    {
        $class_name = ltrim($class_name, '\\');
        $file_name  = '';
        $namespace = '';
        if ($last_ns_pos = strrpos($class_name, '\\')) {
            $namespace = substr($class_name, 0, $last_ns_pos);
            $class_name = substr($class_name, $last_ns_pos + 1);
            $file_name  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $file_name .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
        if (file_exists(FRAMEWORK_BASE_DIR . $file_name)) {
            require FRAMEWORK_BASE_DIR . $file_name;
        }
    }

    /**
     * Register framework's autoload methods
     *
     * @return void
     */
    public static function initialize()
    {
        spl_autoload_register(array('Orinoco\Framework\AutoLoad', 'autoLoadFramework')); // register class Orinoco\Framework\AutoLoad::autoLoadFramework
    }

    /**
     * Register an anonymous (autoload) function
     *
     * @param Directory/folder name $dir_name
     * @return void
     */
    public static function register($dir_name)
    {
        // Prepare directory/folder name
        $dir_name = APPLICATION_BASE_DIR . ltrim(rtrim($dir_name, '/'), '/') . '/';
        // Register an anonymous (autoload) function
        spl_autoload_register(function ($class_name) use ($dir_name)
            {
                $class_name = ltrim($class_name, '\\');
                $file_name  = '';
                $namespace = '';
                if ($last_ns_pos = strrpos($class_name, '\\')) {
                    $namespace = substr($class_name, 0, $last_ns_pos);
                    $class_name = substr($class_name, $last_ns_pos + 1);
                    $file_name  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
                }
                $file_name .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
                if (file_exists($dir_name . $file_name)) {
                    require $dir_name . $file_name;
                }
            }
        );
    }
}
