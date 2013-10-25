<?php

use Syncle\TestCase;
use Syncle\Services\Validators\SyncleCommandValidator;

class SyncleCommandValidatorTest extends TestCase
{

    public function testValidateWithEmptyArgument()
    {
        $validator = new SyncleCommandValidator;

        $this->assertEquals( '', $validator->validate( array( ) ) );
    }

    public function testValidateWithByDefaultOptionValue()
    {
        $validator = new SyncleCommandValidator;

        $this->assertEquals( '', $validator->validate( array( 'by' => 'default' ) ) );
    }

    public function testValidateWithByValidOptionValue()
    {
        Config::set('syncle::DeployMethod.TestValue', 'test value');

        $validator = new SyncleCommandValidator;

        $this->assertEquals( '', $validator->validate( array( 'by' => 'TestValue' ) ) );
    }

    public function testValidateWithNoValidByOption()
    {
        Config::set( 'app.local', 'en' );

        $validator = new SyncleCommandValidator;

        $this->assertEquals( "Can't find deploy method specified by '--by' option.",
                             $validator->validate( array( 'by' => 'testValue' ) ) );
    }

}