<?php

namespace CaueSantos\AutoClassDiscovery;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class AutoClassDiscovery
{

    private static array $discovered = [];

    public static function discover($folder): array
    {

        if (is_array($folder)) {
            foreach ($folder as $group => $f) {
                self::searchInFolder($f, $group);
            }
        } else {
            self::searchInFolder($folder);
        }

        return self::$discovered;

    }

    public static function manualDiscover(string $type, array $item, string $group = null, $key = null)
    {

        if (!in_array($type, ['class', 'interface'])) {
            throw new \TypeError(self::class . "::manualDiscover(): Argument #1 ($type) must be in following values [class, interface], but '{$type}' was passed.");
        }

        if ($group) {
            if ($key) {
                self::$discovered[$type][$group][$key] = $item;
            } else {
                self::$discovered[$type][$group][] = $item;
            }
        } else {
            if ($key) {
                self::$discovered[$type][$key] = $item;
            } else {
                self::$discovered[$type][] = $item;
            }
        }

    }

    public static function discoverFromCache($cache)
    {
        self::$discovered = $cache;
    }

    private static function searchInFolder(string $folder, string $group = null)
    {

        $namespace_pattern = '/namespace [A-z0-9]+[\\]+[A-z0-9]+/';
        $interface_pattern = '/interface [A-z0-9]+[\\]+[A-z0-9]+/';
        $class_pattern = '/class [A-z0-9]+[\\]+[A-z0-9]+/';

        $directory = new RecursiveDirectoryIterator($folder);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        foreach ($regex as $file_from_regex) {

            $file_content = file_get_contents($file_from_regex[0]);

            preg_match($namespace_pattern, $file_content, $namespace_match);
            preg_match($interface_pattern, $file_content, $interface_match);
            preg_match($class_pattern, $file_content, $class_match);

            if (isset($namespace_match[0])) {

                $namespace_match = str_replace('namespace', '', $namespace_match[0]);
                $match = null;
                $type = null;

                if (isset($interface_match[0])) {
                    $match = str_replace('interface', '', $interface_match[0]);
                    $type = 'interface';
                } else if (isset($class_match[0])) {
                    $match = str_replace('class', '', $class_match[0]);
                    $type = 'class';
                }

                if ($match) {

                    $full_class = $namespace_match . '\\' . $match;
                    $full_class = str_replace(' ', '', $full_class);

                    if (class_exists($full_class) || interface_exists($full_class)) {

                        $content = [
                            'class' => $full_class,
                            'interfaces' => class_implements($full_class),
                            'parent' => class_parents($full_class),
                            'traits' => class_uses($full_class)
                        ];

                        if ($group) {
                            self::$discovered[$type][$group][$full_class] = $content;
                        } else {
                            self::$discovered[$type][$full_class] = $content;
                        }

                    }

                }

            }

        }

    }

    public static function getDiscovered(): array
    {
        return self::$discovered;
    }

}
