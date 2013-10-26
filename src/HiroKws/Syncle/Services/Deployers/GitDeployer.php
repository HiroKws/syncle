<?php

namespace Syncle\Services\Deployers;

class GitDeployer implements DeployerInterface
{

    public function deploy( $commndLine, $verbose = false, $log = false )
    {
        $output = '';

        exec( $commndLine, $output );

        // Now do specially nothing.
        if( $log )
        {
            foreach( $output as $line )
            {
                \Log::info($line);
            }
        }

       return $output;
    }

}
