<?php

namespace Syncle\Repositories;

use Syncle\Helpers;

class ComposerJsonRepository
{

    public function __construct( Helpers $helpers )
    {
        $this->helper = $helpers;
    }

    public function get( $item = '' )
    {
        $composer = json_decode( \File::get( $this->helper->base_path().'/composer.json' ),
                                             true );

        if (  key_exists( 'require-dev', $composer))
        {
            return $composer['require-dev'];
        }

        return array();
    }

}