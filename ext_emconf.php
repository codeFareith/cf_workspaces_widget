<?php declare(strict_types=1);

defined('TYPO3')
    or die('Access denied.');

/** @var string $_EXTKEY */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Workspaces Widget',
    'description' => 'Workspaces Widget',
    'category' => 'widgets',
    'author' => 'Robin von den Bergen',
    'author_email' => 'robinvonberg@gmx.de',
    'state' => 'stable',
    'version' => '14.0.0',
    'constraints' => [
        'depends' => [
            'php' => '8.2.0-8.5.99',
            'typo3' => '14.0.0-14.4.99',
            'backend' => '14.0.0-14.4.99',
            'extbase' => '14.0.0-14.4.99',
            'dashboard' => '14.0.0-14.4.99',
            'workspaces' => '14.0.0-14.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'CodeFareith\\CfWorkspacesWidget\\' => 'Classes'
        ]
    ],
    'autoload-dev' => [
        'psr-4' => [
            'CodeFareith\\CfWorkspacesWidget\\Tests\\' => 'Tests'
        ]
    ]
];