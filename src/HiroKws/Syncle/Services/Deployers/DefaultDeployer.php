<?php

namespace Syncle\Services\Deployers;

class DefaultDeployer implements DeployerInterface
{

    public function deploy( $commndLine, $verbose = false, $log = false )
    {
        $output = '';

        exec( $commndLine, $output );

        if( $log )
        {
            foreach( $output as $line )
            {
                \Log::info($line);
            }
        }

        // This is default fallback deployer.
        // So ignore $verbose. Everytime output all.
        return $output;
    }

}
