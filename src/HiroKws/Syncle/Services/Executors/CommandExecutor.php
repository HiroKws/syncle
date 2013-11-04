<?php

namespace Syncle\Services\Executors;

/**
 * Execute shell command by proc_open,
 *
 * This is good sample to handle standard error
 * from within PHP script.
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

            // Delete last 'false' item.
            array_pop( $this->output );

            // Get stderr.
            while( !feof( $pipes[2] ) ) $this->errorOutput[] = fgets( $pipes[2] );
            fclose( $pipes[2] );

            // Delete last 'false' item.
            array_pop( $this->errorOutput );

            $result = proc_close( $process );
        }
        else
        {
            // todo : Need internationalize.
            $this->errorOutput[] = array( 'Faild to Execute : '.$command );
            return 1;
        }

        return $result;
    }

    /**
     * Get output strings.
     *
     * @return array An array of output strings.
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Get error output strings.
     *
     * @return array An array of error output strings.
     */
    public function getErrorOutput()
    {
        return $this->errorOutput;
    }

}