<?php

// Can't test BaseCommand directly. Use a stub.
use Syncle\TestCase;
use Syncle\Commands\TestStub\BaseCommandTestStub;
use Symfony\Component\Console\Tester\CommandTester;

class BaseCommandTest extends TestCase
{

    public function testRunNormalTerminate()
    {
        $tester = new CommandTester( new BaseCommandTestStub );

        $tester->execute( array( 'param1' => 'dummy' ) );

        $this->assertEquals( "", $tester->getDisplay() );
    }

    public function testRunWithNoArgument()
    {
        $tester = new CommandTester( new BaseCommandTestStub );

        $tester->execute( array( ) );

        $this->assertEquals( "Not enough arguments.\n", $tester->getDisplay() );
    }

    // By limitation of test runner class, can't put extra parameter to test command itself.
//    public function testRunWithTooManyArgument()
//    {
//        $tester = new CommandTester( new BaseCommandTestStub );
//
//        $tester->execute( array( 'param1'=>'dummy1', '2'=>'dummy2' ) );
//
//        $this->assertEquals( "\n", $tester->getDisplay() );
//    }

    public function testRunWithCorrectOption()
    {
        $tester = new CommandTester( new BaseCommandTestStub );

        $tester->execute( array( 'param1'    => 'dummy', '--option1' => 'dummy' ) );

        $this->assertEquals( "", $tester->getDisplay() );
    }

    // This also can't test because different exception was throw
    // between real command and test runner.
//    public function testRunWithWrongOption()
//    {
//        $tester = new CommandTester( new BaseCommandTestStub );
//
//        $tester->execute( array( 'param1'    => 'dummy', '--nonExist' => 'dummy' ) );
//
//        $this->assertEquals( "", $tester->getDisplay() );
//    }

}