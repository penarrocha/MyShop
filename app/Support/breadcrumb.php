<?php

if (! function_exists('breadcrumb')) {
    /**
     * Helper to pass breadcrumb definition to the view.
     */
    function breadcrumb(string $name, ...$params): array
    {
        return ['name' => $name, 'params' => $params];
    }
}
