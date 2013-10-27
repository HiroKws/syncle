<?php

namespace Syncle\Services\Deployers;

interface DeployerInterface
{
    public function run( $commnd, $verbose , $log );
    public function getOutput();
}