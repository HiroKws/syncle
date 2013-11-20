<?php

namespace Syncle\Commands;

use Symfony\Component\Console\Input\InputOption;

/**
 * Simple deploy Artisan command.
 *
 * You can change command by config file setting.
 */
class SyncleCommand extends BaseCommand
{
    /**
     * The dummy console command name.
     * This will be replaced by config setting.
     *
     * @var string
     */
    protected $name = 'syncle';

    /**
     * The dummy console command description.
     * This will be replaced by language file setting.
     *
     * @var string
     */
    protected $description = 'Deploy this project.';

    /**
     * Execute the console command.
     *
     * Return value will be execute code of this command.
     * So don't return ture/false. It must be integer.
     *
     * @return integer Return Code. 0: Terminated successfully.
     */
    public function fire()
    {
        $args = array_merge( $this->option(), $this->argument() );

        // I don't want to make extra instance by cascading dependency injection
        // on constructor for commands from service provider.
        // So I don't use constructor injection on this command class.
        $validator = \App::make( 'Syncle\Services\Validators\SyncleCommandValidator' );
        $message = $validator->validate( $args );

        // The validator return only error message.
        if( $message != '' )
        {
            $this->error( $message );

            return 1; // Presented abnormal termination.
        }

        // Get an execute command line or command array.
        // When no specified --by, default valude is 'default'. See  getOptions() method.
        $commandItems = \Config::get( 'syncle::DeployMethod.'.$args['by'] );

        // Get default commit message.
        $commitMessage = $args['message'] == '' ? \Config::get( 'syncle::DefaultGitMessage' ) : $args['message'];

        // Deploy this project.
        $deployer = \App::make( 'Syncle\Services\Deployers\Deployer' );
        $result = $deployer
            ->deploy( $commandItems, $args['verbose'], $args['log'], $commitMessage );

        // Display output.
        foreach( $deployer->getOutput() as $line ) $this->line( $line );

        return $result;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array( 'by',
                'b',
                InputOption::VALUE_OPTIONAL,
                'Deploy method.',
                'default' // default deploy method.
            ),
            array( 'log',
                'l',
                InputOption::VALUE_NONE, // VALUE_NONE means true/false flag.
                'Log output',
                null // When VALUE_NONE, keep this null.
            ),
            array( 'message',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Commit message',
                ''
            ),
        );
    }

}