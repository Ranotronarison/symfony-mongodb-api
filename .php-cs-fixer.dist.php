<?php

$finder = (new PhpCsFixer\Finder())
  ->in([__DIR__ . "/src", __DIR__ . "/tests"]);


return (new PhpCsFixer\Config())
  ->setRules([
    '@PSR1' => true,
    '@PSR2' => true,
    '@PSR12' => true,
  ])
  ->setFinder($finder)
;
