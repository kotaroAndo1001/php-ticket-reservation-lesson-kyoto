<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true, // PSR-12ベース
    ])
    ->setFinder($finder)
    ;
