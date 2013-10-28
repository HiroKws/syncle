<?php

namespace Syncle\Commands;

use Symfony\Component\Console\Input\InputOption;

class SyncleCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'syncle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy this project.';

    /**
     * Execute the console command.
     *
     * @return integer Return Code. 0: Terminated successfully.
     */
    public function fire()
    {
        $args = array_merge( $this->option(), $this->argument() );

        // I don't wont to make extra instance by cascading dependency injextion
        // on constructor for commands.
        $validator = \App::make( 'Syncle\Services\Validators\SyncleCommandValidator' );
        $message = $validator->validate( $args );

        if( $message != '' )
        {
            $this->error( $message );

            return 1; // Presented abnormal termination.
        }

        // Get an execute command line or command array.
        $commandItems = \Config::get( 'syncle::DeployMethod.'.$args['by'] );

        // Get default git commit message.
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