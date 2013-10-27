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

        // Set locale for display messages' language. Default is 'en'.
        \App::setLocale( $args['lang'] );

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

        // Deploy this project.
        $deployer = \App::make( 'Syncle\Services\Deployers\Deploy' );
        $outputs = $deployer
            ->deploy( $commandItems, $args['verbose'], $args['log'], $args['message'] );

        // Display output.
        foreach( $outputs as $line ) $this->line( $line );

        return 0; // Presented normal termination.
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
                '',
                InputOption::VALUE_NONE, // VALUE_NONE means true/false flag.
                'Log output',
                null // When VALUE_NONE, keep this null.
            ),
            array( 'lang',
                'l',
                InputOption::VALUE_OPTIONAL,
                'language code ( en, ja, ...etc. )',
                'en' // English is fallback language.
            ),
            array( 'message',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Commit message',
                'Auto commited by deployment command.'
            ),
        );
    }

}