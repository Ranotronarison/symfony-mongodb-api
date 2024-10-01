<?php

$finder = (new PhpCsFixer\Finder())
  ->in([__DIR__ . "/src", __DIR__ . "/tests"]);


return (new PhpCsFixer\Config())
  ->setRules([
    '@PSR12' => true,
    'no_whitespace_before_comma_in_array' => true
  ])
  ->setFinder($finder);
