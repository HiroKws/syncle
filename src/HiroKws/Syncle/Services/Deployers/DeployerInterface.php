<?php

namespace Syncle\Services\Deployers;

interface DeployerInterface
{
    public function deploy( $options, $verbose = false, $log = false );
}