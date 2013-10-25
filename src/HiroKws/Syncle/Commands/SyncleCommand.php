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
        // Validation :
        // The reason I make this instance here, if I put this on constructor,
        // and when a command instantiated, all other classes in package will also
        // instantiate by cascading from service providor. I don't want it.
        // ( In this case, just one command. But when a package have a lot of commnads,
        // it make waste resouce. )
        // So I make each instance just before when use it in package commands.

        $validator = \App::make( 'Syncle\Services\Validators\SyncleCommandValidator' );

        $args = array_merge( $this->option(), $this->argument() );

        $message = $validator->validate( $args );

        if( $message != '' )
        {
            $this->error( $message );

            // non zero value is abnormal teminated code for Symfony console.
            // (Or for most of shell/OS, only zero is normal terminate code.)
            return 1;
        }

        // Get execution modes. Automaticall set ture or false on both items.
        $verbose = $args['verbose'];
        $log = $args['log'];

        // Set locale for display messages' language.
        \App::setLocale($args['lang']);

        // Get project root. 'base_path' don't work in a command.
        // 7th higher directory is project root on 'vendor' also 'workbench' directory.
        $basePath = realpath( __DIR__.'/../../../../../../..' ).'/';

        // Get execute command line.
        // escapeshellcmd for string from config item..., sorry I can't believe you. :D
        $commandLine = escapeshellcmd(
            str_replace( ':to', $basePath,
                         \Config::get( 'syncle::DeployMethod.'.$args['by'] )
            )
        );

        // Get command.
        $command = head( explode( ' ', $commandLine ) );

        // Get Deployer instance.
        try
        {
            // First, try to command name + Deployer class to instantiate.
            $deployer = \App::make( 'Syncle\Services\Deployers\\'.
                    studly_case( $command ).'Deployer' );
        }
        catch( \Exception $e )
        {
            // Get fallback default Deployer instance.
            $deployer = \App::make( 'Syncle\Services\Deployers\DefaultDeployer' );
        }

        // Deploy this project and edit output.
        $outputs = $deployer->deploy( $commandLine, $verbose, $log );

        // Display output.
        foreach( $outputs as $line )
        {
            $this->line( $line );
        }

        // Normal terminate.
        return 0;
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
            array( 'lang',
                'l',
                InputOption::VALUE_OPTIONAL,
                'language code ( en, ja, ...etc. )',
                'en' // English is fallback language.
            ),
        );
    }

}