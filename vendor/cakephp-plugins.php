<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'BarCode' => $baseDir . '/vendor/cristiandean/bar-code/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'MailgunEmail' => $baseDir . '/vendor/narendravaghela/cakephp-mailgun/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'WyriHaximus/TwigView' => $baseDir . '/vendor/wyrihaximus/twig-view/'
    ]
];