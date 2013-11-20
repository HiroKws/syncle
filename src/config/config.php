<?php

return array(
    'CommandName'       => 'syncle',
    // Display language. Only 'en' and 'ja'.
    'MessageLang'       => 'en',
    'DeployMethod'      => array(
        // Set your rsync command options.
        // ':root' will be replaced to document root directory.
        // ':projectRoot' will be replaced to project root directory.
        // ':projectRoot' is for a project on workbench directory.
        // ':excludeRequireDev' will be replaced to strings that
        // exclude to transfer packages on require-dev section in
        // your root composer.json.
        'default' => array(
            // Generate compiled classes file and dump autoload files with -o
            'php artisan --ansi --force optimize',
            // rsync without tranfer local storages file and test.
            'rsync -av --delete '.
            '--exclude=".git/" '.
            '--exclude="storage/cache/*" '.
            '--exclude="storage/logs/*" '.
            '--exclude="storage/meta/*" '.
            '--exclude="storage/sessions/*" '.
            '--exclude="storage/views/*" '.
            '--exclude="storage/database/*" '.
            '--exclude="storage/work/*" '.
            '--exclude="nbproject/" '.
            '--exclude="test/" '.
            '--exclude="tests/" '.
            '--exclude="Tests/" '.
            '--exclude="test-suite" '.
            ':excludeRequireDev '.
            '-e "ssh -p 99999" '.
            ':root/ username@example.com:/home/username/public_html/project',
            // Remove compiled classes file.
            'php artisan --ansi clear-compiled'
        ),
        // Set Git commands. ':message' replace to string specified by --message option.
        // When set multiple command, use array.
        'git'     => array(
            'git add -A',
            'git commit -am ":message"',
            'git push' ),
    ),
    'DefaultGitMessage' => 'Automatically commited by deploy command.',
);
