<?php

namespace app\helpers;

/**
 * Class DirHelper
 * @package app\helpers
 */
class DirHelper
{
    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            return null;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}