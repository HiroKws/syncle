<?php

namespace Syncle\Services\Deployers;

/**
 * Deployer classes interface.
 */
interface DeployerInterface
{
    public function run( $commnd, $verbose , $log );
    public function getOutput();
}