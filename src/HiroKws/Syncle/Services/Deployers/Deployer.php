<?php

namespace Syncle\Services\Deployers;

class Deployer
{
    private $output = array( );

    public function deploy( $commands, $verbose, $log, $message )
    {
        // Get project root. 'base_path' don't work in a command.
        // 8th upper directory is project root.
        $basePath = realpath( __DIR__.'/../../../../../../../..' ).'/';

        $commandArray = is_array( $commands ) ? $commands : ( array ) $commands;

        foreach( $commandArray as $command )
        {
            $replacedTo = str_replace( ':to', $basePath, $command );
            $replacedMessage = str_replace( ':message', $message, $replacedTo );
            $commandLine = escapeshellcmd( $replacedMessage );

            // Get only execute command.
            $command = head( explode( ' ', $commandLine ) );

            // Get each command's deployer instance.
            // Class name started with command name.
            try
            {
                // First, try to instantiate command name + "Deployer" class.
                $deployer = \App::make( 'Syncle\Services\Deployers\\'.
                        studly_case( $command ).'Deployer' );
            }
            catch( \Exception $e )
            {
                // Get fallback default deployer instance.
                $deployer = \App::make( 'Syncle\Services\Deployers\DefaultDeployer' );
            }

            // Deploy this project and edit output.
            $result = $deployer->run( $commandLine, $verbose, $log );

            $this->output = array_merge( $this->output, $deployer->getOutput() );

            if( $result != 0 )
            {
                array_push( $this->output,
                            \Lang::trans( 'syncle::SyncleCommand.ExecutionError',
                                          array( 'command' => $commandLine ) ) );

                return $result;
            }
        }

        return 0;
    }

    public function getOutput()
    {
        return $this->output;
    }

}