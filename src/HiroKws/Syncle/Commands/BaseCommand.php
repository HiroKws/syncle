<?php

namespace Syncle\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Handle exception and internationalize messages
 * for Laravel & Symfony Command class.
 */
class BaseCommand extends Command
{

    /**
     * Override run method to internationalize error message.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int Return code
     */
    public function run( InputInterface $input, OutputInterface $output )
    {
        // Set extra colors
        // The most problem is $output->getFormatter() don't work.
        // So create new formatter to add extra color.
        $formatter = new OutputFormatter( $output->isDecorated() );
        $formatter->setStyle( 'red', new OutputFormatterStyle( 'red', 'black' ) );
        $formatter->setStyle( 'green', new OutputFormatterStyle( 'green', 'black' ) );
        $formatter->setStyle( 'yellow', new OutputFormatterStyle( 'yellow', 'black' ) );
        $formatter->setStyle( 'blue', new OutputFormatterStyle( 'blue', 'black' ) );
        $formatter->setStyle( 'magenta', new OutputFormatterStyle( 'magenta', 'black' ) );
        $formatter->setStyle( 'yellow-blue', new OutputFormatterStyle( 'yellow', 'blue' ) );
        $output->setFormatter( $formatter );

        \App::setLocale( \Config::get( 'syncle::MessageLang' ) );

        try
        {
            $result = parent::run( $input, $output );
        }
        catch( \RuntimeException $e )
        {
            // All error messages were hard coded in
            // Symfony/Component/Console/Input/Input.php
            if( $e->getMessage() == 'Not enough arguments.' )
            {
                $this->error( \Lang::get( 'syncle::BaseCommand.ArgumentNotEnough' ) );
            }
            elseif( $e->getMessage() == 'Too many arguments.' )
            {
                $this->error( \Lang::get( 'syncle::BaseCommand.TooManyArgument' ) );
            }
            elseif( preg_match( '/The "(.+)" option does not exist./', $e->getMessage(),
                                $matches ) )
            {
                $this->error( \Lang::get( 'syncle::BaseCommand.OptionNotExist',
                                          array( 'option' => $matches[1] ) ) );
            }
            else
            {
                $this->error( $e->getMessage() );
            }
            $result = 1;
        }

        return $result;
    }

}