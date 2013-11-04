<?php

namespace Syncle\Services\Validators;

/**
 * A validator for syncle command parameters and options.
 */
class SyncleCommandValidator
{

    /**
     * Validate parameters and options with syncle command.
     *
     * @param array $options A merged options and parameters arrays.
     * @return string Error message.
     */
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