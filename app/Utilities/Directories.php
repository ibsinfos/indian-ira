<?php

namespace IndianIra\Utilities;

trait Directories
{
    /**
     * Create the directory if the given path does not exist.
     *
     * @param   string  $path
     * @return  string
     */
    private function createDirectoryIfNotExists($path)
    {
        $fullPath = $this->getPublicPath() . $path;

        if (! file_exists($fullPath)) {
            mkdir($fullPath, 0775, true);
        }

        return $fullPath;
    }

    /**
     * Get the public path depending upon the hosting type
     * set in the .env file.
     *
     * @return  string
     */
    private function getPublicPath()
    {
        $hostingType = env('HOSTING_TYPE');

        if ($hostingType == 'shared') {
            return base_path() . '/../public_html/';
        }

        if ($hostingType == 'cloud') {
            return public_path() . '/';
        }
    }
}
