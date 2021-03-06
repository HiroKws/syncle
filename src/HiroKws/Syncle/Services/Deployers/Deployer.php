<?php

namespace Syncle\Services\Deployers;

use Syncle\Helpers as H;

/**
 * Commands Deployer.
 *
 * Execute a command in the passed array.
 */
class Deployer
{
    /**
     * Output message.
     *
     * @var array
     */
    private $output = array();

    /**
     * Execute commands and format & colorize.
     *
     * @param mix $commands A command(string) or some commands(array)
     * @param boolean $verbose Verbose mode flag.
     * @param boolean $log Log output flag.
     * @param string $message Commit message.
     * @return int Execution code.
     */
    public function deploy( $commands, $verbose, $log, $message )
    {
        $commandArray = is_array( $commands ) ? $commands : ( array ) $commands;

        // Get project base root for develop package on workbench.
        $projectBasePath = realpath( __DIR__.'/../../../../..' );

        // Get require-dev section from composer.json
        $composerRepo = \App::make( 'Syncle\Repositories\ComposerJsonRepository' );
        $requireDevs = $composerRepo->get();

        // Generate exclude options for require-dev packages.
        $excludePackages = "";

        foreach( $requireDevs as $package => $version )
        {
            $excludePackages .= "--exclude=\"vendor/{$package}\" ";
        }

        foreach( $commandArray as $command )
        {

            // Replace placeholders.
            $helper = \App::make( 'Syncle\Helpers' );
            $replacedToBase = str_replace( ':root', $helper->base_path(), $command );
            $replacedToProject = str_replace( ':projectRoot', $projectBasePath,
                                              $replacedToBase );
            $replacedMessage = str_replace( ':message', $message, $replacedToProject );
            $commandLine = str_replace( ':excludeRequireDev', $excludePackages,
                                        $replacedMessage );

            if( $verbose )
            {
                $this->output = array_merge( $this->output,
                                             array( "<blue>=><blue><comment> {$commandLine}</comment>" ) );
            }

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

    /**
     * Get output messages' array.
     *
     * @return array Output messages.
     */
    public function getOutput()
    {
        return $this->output;
    }

}