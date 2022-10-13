<?php

namespace App\Traits;

use App\Http\Controllers\{BasicAdminController, BasicApiController};
use DirectoryIterator;
use ReflectionClass;
use ReflectionMethod;

trait PermissionsTrait
{
    /**
     * Get list of the possible permissions
     *
     * @param $folders
     * @param array $result
     * @return array
     */
    protected function permissionList($folders, array $result = []): array
    {
        if (!is_array($folders)) {
            $folders = [$folders];
        }

        foreach ($folders as $folder) {
            $content = new DirectoryIterator(base_path($folder));
            foreach ($content as $item) {
                if (!$item->isDot()) {
                    if ($item->isDir()) {
                        $result = $this->permissionList($this->relatedPath($item->getPathname()), $result);
                    } else {
                        // File basename
                        $file = $item->getFilename();
                        // Class name
                        $class_name = preg_replace(
                            '/\//',
                            '\\',
                            ucfirst($this->relatedPath($item->getPath())) . '/' . pathinfo($file, PATHINFO_FILENAME)
                        );
                        // Parent class
                        $parent_class = get_parent_class($class_name);
                        // Check parent classes
                        if ($parent_class == BasicAdminController::class || $parent_class == BasicApiController::class) {
                            $result[] = [
                                'file'    => $file,
                                'class'   => $class_name,
                                'parent'  => $parent_class,
                                'methods' => (function ($methods = []) use ($class_name) {
                                    foreach ((new ReflectionClass($class_name))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                                        if ($method->class == $class_name) {
                                            $methods[] = $method->name;
                                        }
                                    }
                                    return $methods;
                                })()
                            ];
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Convert an absolute path to relative
     *
     * @param string $path
     * @return string
     */
    protected function relatedPath(string $path): string
    {
        return substr($path, strlen(base_path()) + 1);
    }
}