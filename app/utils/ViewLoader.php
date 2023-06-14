<?php

namespace App\Utils;

use InvalidArgumentException;

/**
 * Class for loading views.
 *
 * @package App\Utils
 *
 */
class ViewLoader {
    // predefined locations of views
    private array $locations;

    // singleton instance
    private static ?ViewLoader $viewLoader = null;

    // don't allow to create instance from outside
    private function __construct() {
        $this->locations = [
            "default" => __DIR__ . '/../../app/views/pages/',
            "shared" => __DIR__ . '/../../app/views/shared/'
        ];
    }

    /**
     * Returns the singleton instance.
     *
     * @return ViewLoader singleton instance.
     */
    public static function getViewLoader(): ViewLoader {
        if (self::$viewLoader !== null) {
            return self::$viewLoader;
        }

        self::$viewLoader = new ViewLoader();
        return self::$viewLoader;
    }

    /**
     * Loads a view.
     *
     * @param string $viewPath Path to view.
     * @param array $data Data to pass to view.
     * @param string $location Location of view.
     *
     * @return string
     *
     * @throws InvalidArgumentException If the given location does not exist.
     */
    public function loadView(string $viewPath, array $data = [], string $location = 'default'): string {
        if (!isset($this->locations[$location])) {
            throw new InvalidArgumentException("Location {$location} does not exist.");
        }

        $output = $this->renderView('navbar', $data, "shared");
        $output .= $this->renderView($viewPath, $data, $location);
        $output .= $this->renderView('footer', $data, "shared");

        return $output;
    }

    /**
     * Renders a view.
     *
     * @param string $viewName Name of the view file.
     * @param array $data Data to pass to view.
     * @param string $location Location of view.
     *
     * @return string Rendered view content.
     *
     * @throws InvalidArgumentException If the view file does not exist.
     */
    private function renderView(string $viewName, array $data, string $location): string {
        $path = $this->locations[$location] . $viewName . '.php';

        if (!file_exists($path)) {
            throw new InvalidArgumentException("View file {$path} does not exist.");
        }

        // replace variables in the view
        $output = file_get_contents($path);

        foreach ($data as $key => $value) {
            $output = str_replace('{{' . $key . '}}', $value, $output);
        }

        return $output;
    }
}
