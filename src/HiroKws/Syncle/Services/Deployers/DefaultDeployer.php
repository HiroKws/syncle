<?php

namespace Syncle\Services\Deployers;

class DefaultDeployer extends BaseDeployer implements DeployerInterface
{
    protected $output;

    public function run( $commandLine, $verbose, $log )
    {
        $result = $this->executor->execute( $commandLine );

        $outputs = $this->executor->getOutput();
        $errorOutputs = $this->executor->getErrorOutput();

        $this->output = array( );

        foreach( $outputs as $output )
        {
            if( $log ) \Log::info( $output );

            $this->output[] = $output;
        }

        $this->outputErrors( $errorOutputs, $log );

        return $result;
    }

}
