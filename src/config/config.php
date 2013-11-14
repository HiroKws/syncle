<?php

return array(
    'CommandName'       => 'syncle',
    // Display language. Only 'en' and 'ja'.
    'MessageLang'       => 'en',
    'DeployMethod'      => array(
        // Set your rsync command options.
        // ':root' will be replaced to document root directory.
        // ':projectRoot' will be replaced to project root directory. This is for project on workbench directory.
        'default' => array(
            'php artisan --ansi --force optimize',
            'rsync -av --delete '.
            '--exclude=".git/" --exclude="storage/cache/*" --exclude="storage/logs/*" '.
            '--exclude="storage/meta/*" --exclude="storage/sessions/*" '.
            '--exclude="storage/views/*" --exclude="storage/database/*" '.
            '--exclude="storage/work" --exclude="nbproject/" '.
            '--exclude="test/" --exclude="tests/" --exclude="Tests/" --exclude="test-suite" '.
            '-e "ssh -p 99999" :root/ username@somewhereexample.com:/home/username/public_html/project',
            'php artisan --ansi --force clear-compiled'
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
