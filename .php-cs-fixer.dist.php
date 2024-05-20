<?php

/*
 * This file is part of Auth0 for EC-CUBE
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (php_sapi_name() !== 'cli') {
    throw new LogicException();
}

$header = <<<EOL
This file is part of Auth0 for EC-CUBE

Copyright(c) Akira Kurozumi <info@a-zumi.net>

https://a-zumi.net

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOL;

$rules = [
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'phpdoc_align' => false,
    'phpdoc_summary' => false,
    'phpdoc_scalar' => false,
    'phpdoc_annotation_without_dot' => false,
    'no_superfluous_phpdoc_tags' => false,
    'increment_style' => false,
    'yoda_style' => false,
    'header_comment' => ['header' => $header],
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->name('*.php')
;
$config = new PhpCsFixer\Config();

return $config
    ->setRules($rules)
    ->setFinder($finder)
;
