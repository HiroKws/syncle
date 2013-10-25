<?php

use Syncle\TestCase;
use Syncle\Commands\SyncleCommand;
use Symfony\Component\Console\Tester\CommandTester;

class SyncleCommandTest extends TestCase
{

    public function testRunWithoutArguments()
    {
        $tester = new CommandTester( new SyncleCommand );

        $tester->execute( array( ) );

        $this->assertEquals( "", $tester->getDisplay() );
    }


}