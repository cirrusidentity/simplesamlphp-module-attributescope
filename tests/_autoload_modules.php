<?php

/**
 * This file implements a trimmed down version of the SSP module aware autoloader that can be used in tests.
 *
 * @author Patrick Radtke
 */

/**
 * Autoload function for local SimpleSAMLphp modules.
 *
 * @param string $className Name of the class.
 */
function SimpleSAML_test_module_autoload($className)
{
    $modulePrefixLength = strlen('sspmod_');
    $classPrefix = substr($className, 0, $modulePrefixLength);
    if ($classPrefix !== 'sspmod_') {
        return;
    }

    $modNameEnd = strpos($className, '_', $modulePrefixLength);
    $moduleClass = substr($className, $modNameEnd + 1);
    $module = substr($className, $modulePrefixLength, $modNameEnd - $modulePrefixLength);
    $path = explode('_', substr($className, $modNameEnd + 1));

    $file = dirname(dirname(__FILE__)) . '/lib/' . str_replace('_', '/', $moduleClass) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }

    if (!class_exists($className, false) && !interface_exists($className, false)) {
        // the file exists, but the class is not defined. Is it using namespaces?
        $nspath = join('\\', $path);
        if (
            class_exists('SimpleSAML\\Module\\' . $module . '\\' . $nspath)
            || interface_exists('SimpleSAML\\Module\\' . $module . '\\' . $nspath)
        ) {
            // the class has been migrated, create an alias
            class_alias("SimpleSAML\\Module\\$module\\$nspath", $className);
        }
    }
}

spl_autoload_register('SimpleSAML_test_module_autoload');
