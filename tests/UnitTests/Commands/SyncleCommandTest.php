<?php

use Syncle\TestCase;
use Syncle\Commands\SyncleCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Input\InputOption;

class SyncleCommandTest extends TestCase
{

    public function testRunWithoutArguments()
    {
        $command = new SyncleCommand;
        $command->addOption( 'verbose', '', InputOption::VALUE_NONE, '', null );
        $command->addOption( 'log', 'l', InputOption::VALUE_NONE, '', null );

        $tester = new CommandTester( $command );

        // モックを使う

        $tester->execute( array( ) );

        // In real command, verbose and log item will pass automatically.
        $this->assertEquals( "", $tester->getDisplay() );
    }

}