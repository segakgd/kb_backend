<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
    ]);

$config = new Config();

return $config
    ->setRules([
        '@PSR12'                      => true,
        '@PhpCsFixer'                 => true,
        'yoda_style'                  => false,
        'method_chaining_indentation' => false,
        'explicit_string_variable'    => false,
        'explicit_indirect_variable'  => false,
        'phpdoc_summary'              => false,
        'phpdoc_separation'           => false,
        'phpdoc_align'                => true,

        'phpdoc_types_order' => [
            'sort_algorithm'  => 'alpha',
            'null_adjustment' => 'always_last',
        ],

        'phpdoc_no_alias_tag' => [
            'replacements' => [
                'type' => 'var',
                'link' => 'see',
            ],
        ],

        'phpdoc_to_comment'       => false,
        'global_namespace_import' => true,

        'types_spaces' => [
            'space_multiple_catch' => 'single', // error on fix just now
        ],

        'ordered_imports' => [
            'imports_order'  => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],

        'binary_operator_spaces' => [
            'default' => 'single_space',

            'operators' => [
                '='  => 'at_least_single_space',
                '=>' => 'align_single_space_minimal',
            ],
        ],

        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],

        'concat_space' => [
            'spacing' => 'one',
        ],

        'blank_line_before_statement' => [
            'statements' => [
                'case',
                'continue',
                'declare',
                'default',
                'exit',
                'goto',
                'include',
                'include_once',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'if',
                'for',
                'foreach',
            ],
        ],
    ])
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setFinder($finder);
