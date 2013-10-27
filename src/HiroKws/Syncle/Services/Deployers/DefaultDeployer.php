<?php

namespace Syncle\Services\Deployers;

class DefaultDeployer implements DeployerInterface
{
    private $output;

    public function run( $commnd, $verbose, $log )
    {
        $outputs = '';
        $result = 0;

        exec( $commnd, $outputs, $result );

        $this->output = $outputs;

        if ( $result != 0 ) return  $result;

        if( $log )
        {
            foreach( $outputs as $line )
            {
                \Log::info( $line );
            }
        }
        return 0;
    }

    public function getOutput()
    {
        return $this->output;
    }

}
