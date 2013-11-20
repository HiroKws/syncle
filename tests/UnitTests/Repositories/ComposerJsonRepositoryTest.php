<?php

use Syncle\TestCase;
use Syncle\Repositories\ComposerJsonRepository;
use Mockery as M;

class ComposerJsonRepositoryTest extends TestCase
{

    public function testNoRequireDevSection()
    {
        $helper = M::mock( 'Syncle\Helpers' );
        $helper->shouldReceive( 'base_path' )
            ->andReturn( realpath( __DIR__.'/../../../' ) );

        File::shouldReceive( 'get' )
            ->andReturn( json_encode( array() ) );

        $repo = new ComposerJsonRepository( $helper );

        $this->assertEquals( array(), $repo->get() );
    }

    public function testSingleRequireDevSection()
    {
        $helper = M::mock( 'Syncle\Helpers' );
        $helper->shouldReceive( 'base_path' )
            ->andReturn( realpath( __DIR__.'/../../../' ) );

        File::shouldReceive( 'get' )
            ->andReturn( json_encode( array( 'require-dev' => array(
                    'vendor1/package1' => 'dev-master'
        ) ) ) );

        $repo = new ComposerJsonRepository( $helper );

        $this->assertEquals( array( 'vendor1/package1' => 'dev-master' ), $repo->get() );
    }

    public function testMulitipleRequireDevSection()
    {
        $helper = M::mock( 'Syncle\Helpers' );
        $helper->shouldReceive( 'base_path' )
            ->andReturn( realpath( __DIR__.'/../../../' ) );

        File::shouldReceive( 'get' )
            ->andReturn( json_encode( array( 'require-dev' => array(
                    'vendor10/package100' => 'dev-master',
                    'vendor20/package200' => 'dev-develop',
                    'vendor30/package300' => '4.3.2'
        ) ) ) );

        $repo = new ComposerJsonRepository( $helper );

        $this->assertEquals( array(
            'vendor10/package100' => 'dev-master',
            'vendor20/package200' => 'dev-develop',
            'vendor30/package300' => '4.3.2'
            ), $repo->get() );
    }

}