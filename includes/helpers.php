<?php
/**
 * Green Credit Helper Functions
 */

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return '/green_credit/' . ltrim($path, '/');
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
