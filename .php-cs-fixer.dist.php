<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2'                              => true,
        'array_syntax'                       => ['syntax' => 'short'],
        'ordered_imports'                    => ['sort_algorithm' => 'alpha'],
        'no_unused_imports'                  => true,
        'not_operator_with_successor_space'  => true,
        'trailing_comma_in_multiline'        => true,
        'phpdoc_scalar'                      => true,
        'unary_operator_spaces'              => true,
        'binary_operator_spaces'             => [
            'default'   => 'align',
            'operators' => [
                '=>' => 'align',
            ],
        ],
        'blank_line_before_statement'        => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'phpdoc_single_line_var_spacing'     => true,
        'phpdoc_var_without_name'            => true,
        'method_argument_space'              => [
            'on_multiline'                     => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => true,
        ],
        'single_trait_insert_per_statement'  => true,
        'braces'                             => [
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_anonymous_constructs'         => 'same',
            'position_after_control_structures'           => 'same',
        ],
        'class_attributes_separation'        => ['elements' => ['method' => 'one']],
        'concat_space'                       => ['spacing' => 'one'],
        'no_blank_lines_after_class_opening' => true,
        'blank_line_after_namespace'         => true,
        'single_blank_line_before_namespace' => true,
        'align_multiline_comment'            => ['comment_type' => 'phpdocs_only'],
        'function_typehint_space'            => true,
        'no_extra_blank_lines'               => [
            'tokens' => [
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use',
                'curly_brace_block',
            ],
        ],
        'ternary_operator_spaces'            => true,
        'whitespace_after_comma_in_array'    => true,
        'phpdoc_align'                       => ['tags' => ['param']],
        'phpdoc_order'                       => true,
        'phpdoc_separation'                  => true,
        'single_blank_line_at_eof'           => true,
        'fully_qualified_strict_types'       => true,
        'psr_autoloading'                    => [
            'dir' => null,
        ],
    ])
    ->setFinder($finder);
