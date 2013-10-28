<?php

namespace Syncle\Services\Deployers;

class GitDeployer extends BaseDeployer implements DeployerInterface
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

            // Todo : put format/colorize code for git here.
            $this->output[] = $output;
        }

        $this->outputErrors( $errorOutputs, $log );

        return $result;
    }

}
