<?php

namespace Syncle\Services\Deployers;

/**
 * Default deployer class when the command specific deployer is not exist.
 */
class DefaultDeployer extends BaseDeployer implements DeployerInterface
{
    /**
     * Output messages.
     *
     * @var array Output messages.
     */
    protected $output;

    /**
     * Execute an command without formatting & colorize.
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

            $this->output[] = $output;
        }

        $this->outputErrors( $errorOutputs, $log );

        return $result;
    }

}
