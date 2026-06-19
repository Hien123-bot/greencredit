<?php
/**
 * Public path of the application.
 *
 * It is detected from the web server document root, so the same source works
 * at http://localhost/green_credit/ and when a production domain points
 * directly at this project (https://greencredit.id.vn/).
 * APP_BASE_PATH can be set by the server for unusual hosting layouts.
 */
$configuredBasePath = getenv('APP_BASE_PATH');

if ($configuredBasePath !== false && trim($configuredBasePath) !== '') {
    $basePath = '/' . trim(str_replace('\\', '/', $configuredBasePath), '/');
} else {
    $appRoot = str_replace('\\', '/', realpath(dirname(__DIR__)) ?: dirname(__DIR__));
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT'])
        ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']) ?: $_SERVER['DOCUMENT_ROOT'])
        : '';

    $documentRoot = rtrim($documentRoot, '/');
    $isInsideDocumentRoot = $documentRoot !== ''
        && ($appRoot === $documentRoot || strncasecmp($appRoot, $documentRoot . '/', strlen($documentRoot) + 1) === 0);

    if ($isInsideDocumentRoot) {
        $basePath = substr($appRoot, strlen($documentRoot));
    } else {
        // CLI or a hosting setup where DOCUMENT_ROOT is unavailable.
        $basePath = '';
    }

    $basePath = '/' . trim($basePath, '/');
}

define('BASE_URL', $basePath === '/' ? '/' : $basePath . '/');
define('PTS_TO_VND', 1000); // 1 Point = 1000 VND
