<?php
/**
 * Green Credit Helper Functions
 */

require_once __DIR__ . '/config.php';

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return BASE_URL . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('format_points')) {
    function format_points($points) {
        return number_format($points) . ' pts';
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}
