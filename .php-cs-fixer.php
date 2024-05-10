<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
    ->in(__DIR__.'/examples')
;

return (new PhpCsFixer\Config())
    ->setCacheFile(__DIR__.'/.cache/.php-cs-fixer.cache')
    ->setRules([
       '@PER-CS' => true,
   ])
    ->setFinder($finder)
    ;
