<?php

return array(
    'CommandName'  => 'syncle',
    'DeployMethod' => array(
        // Set your rsync command options. ':to' replace to document root directory.
        'default' => 'rsync -av --delete '.
        '--exclude=".git/" --exclude="storage/cache/*" --exclude="storage/logs/*" '.
        '--exclude="storage/meta/*" --exclude="storage/sessions/*" '.
        '--exclude="storage/views/*" --exclude="storage/database/*" '.
        '--exclude="storage/work" --exclude="nbproject/" '.
        '--exclude="test/" --exclude="tests/" --exclude="Tests/" --exclude="test-suite" '.
        '-e "ssh -p 99999" :to username@somewhereexample.com:/home/username/public_html/project',
    ),
);
