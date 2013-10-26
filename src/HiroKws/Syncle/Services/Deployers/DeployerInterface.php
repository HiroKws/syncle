<?php

namespace Syncle\Services\Deployers;

interface DeployerInterface
{
    public function deploy( $commandLine, $verbose = false, $log = false );
}