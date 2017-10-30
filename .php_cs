<?php

$finder = PhpCsFixer\Finder::create()
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->name('*.php')
            ->in([
                'src',
                'test',
            ])
            ->exclude([
             'vendor',
            ]);

return PhpCsFixer\Config::create()
        ->setRules([
            '@Symfony' => true,
            'psr0' => false,
            'no_useless_return' => false,
            'pre_increment' => false,
            'array_syntax' => ['syntax' => 'short'],
            'concat_space' => ['spacing' => 'one'],
            'binary_operator_spaces' => [
                'align_double_arrow' => true,
                'align_equals' => true,
            ],
            'no_empty_statement' => false,
            'phpdoc_annotation_without_dot' => false,
            'short_scalar_cast' => false
        ])
        ->setFinder($finder);
