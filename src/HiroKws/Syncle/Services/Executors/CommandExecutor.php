<?php

namespace Syncle\Services\Executors;

/**
 * Execute shell command by proc_open to get stderr.
 */
class CommandExecutor
{
    private $output;

    private $errorOutput;

    function execute( $command )
    {
        $fd = array(
            1 => array( "pipe", "w" ),
            2 => array( "pipe", "w" ),
        );
        $pipes = array( );

        $process = proc_open( $command, $fd, $pipes );

        $this->output = array( );
        $this->errorOutput = array( );

        if( is_resource( $process ) )
        {
            // Get stdin.
            while( !feof( $pipes[1] ) ) $this->output[] = fgets( $pipes[1] );
            fclose( $pipes[1] );

            array_pop( $this->output );

            // Get stderr.
            while( !feof( $pipes[2] ) ) $this->errorOutput[] = fgets( $pipes[2] );
            fclose( $pipes[2] );

            array_pop( $this->errorOutput );

            $result = proc_close( $process );
        }
        else
        {
            $this->errorOutput[] = array( 'Faild to Execute : '.$command );
            return 1;
        }

        return $result;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getErrorOutput()
    {
        return $this->errorOutput;
    }

}