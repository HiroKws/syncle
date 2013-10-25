<?php

namespace Syncle\Services\Validators;

class SyncleCommandValidator
{

    public function validate( $options )
    {
        if( !empty( $options['by'] ) and
            !\Config::has( 'syncle::DeployMethod.'.$options['by'] ) )
        {
            return \Lang::get( 'syncle::SyncleCommand.MethodNotFound' );
        }

        return '';
    }

}