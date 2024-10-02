<?php

$finder = (new PhpCsFixer\Finder())
  ->in([__DIR__ . '/src', __DIR__ . '/tests']);


return (new PhpCsFixer\Config())
  ->setRules([
    '@PSR12' => true,
    '@PSR2' => true,
    'no_whitespace_before_comma_in_array' => true,
    'blank_line_before_statement' => true,
    'single_quote' => true,
    'no_unused_imports' => true,
    'clean_namespace' => true,
    'align_multiline_comment' => true,
    'phpdoc_indent' => true,
    'yoda_style' => true,
    'nullable_type_declaration' => true,
    'doctrine_annotation_array_assignment' => true,
    'no_unused_imports' => true,
  ])
  ->setFinder($finder);
