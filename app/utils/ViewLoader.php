<?php

namespace App\Utils;

/**
 * Class for loading views.
 * 
 * @package App\Utils
 * 
 */
class ViewLoader {

    // predefined layouts for views
    private array $layouts;

    // predefined locations of views
    private array $locations;

    // singleton instance
    private static ?ViewLoader $viewLoader = null;

    // don't allow to create instance from outside
    private function __construct() {
        $this->layouts = [
            'default' => __DIR__ . '/../app.php'
        ];
        
        $this->locations = [
            'default' => __DIR__ . '/../../app/views/pages/'
        ];
    }

    /**
     * Returns the singleton instance.
     * 
     * @return ViewLoader singleton instance.
     */
    public static function getViewLoader(): ViewLoader {
        if (self::$viewLoader === null) {
            self::$viewLoader = new ViewLoader();
        }
        return self::$viewLoader;
    }

    /**
     * Loads a view.
     * 
     * @param string $viewPath Path to view.
     * @param array $data Data to pass to view.
     * @param string $layout Layout to use.
     * @param string $location Location of view.
     * 
     * @return void
     * 
     * @throws \InvalidArgumentException If the given layout or location does not exist.
     */
    public function loadView(string $viewPath, array $data = [], string $layout = 'default', string $location = 'default'): void {
        if (!isset($this->locations[$location])) {
            throw new \InvalidArgumentException("Location {$location} does not exist.");
        }

        if (!isset($this->layouts[$layout])) {
            throw new \InvalidArgumentException("Layout {$layout} does not exist.");
        }

        $path = $this->locations[$location] . $viewPath . '.php';

        // extract data array into variables
        extract($data);

        // start output buffering and load view
        ob_start();
        require_once $path;

        // extract view into variable
        $content = ob_get_clean();

        // inject view into layout
        require_once $this->layouts[$layout];
    }
}
