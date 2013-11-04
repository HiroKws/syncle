<?php

namespace Syncle\Services\Deployers;

/**
 * A deployer class for Git command.
 */
class GitDeployer extends BaseDeployer implements DeployerInterface
{
    protected $output;

    /**
     * Execute Git command with formatting & colorizing.
     *
     * @param string $commandLine A Command to execute.
     * @param boolean $verbose Verbose mose flag.
     * @param boolean $log Output log flag.
     * @return integer Execution code.
     */
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
