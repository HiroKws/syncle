<?php

namespace Syncle\Services\Deployers;

class GitDeployer implements DeployerInterface
{
    private $output;

    public function run( $commndLine, $verbose, $log )
    {
        $output = '';
        $result = 0;

        exec( $commndLine, $output, $result );

        if ( $result != 0 )
        {
            $this->output = $output;

            return $result;
        }

        // Now do specially nothing.
        if( $log )
        {
            foreach( $output as $line )
            {
                \Log::info( $line );
            }
        }

        $this->output = $output;

        return 0;
    }

    public function getOutput()
    {
        return $this->output;
    }

}
