<?php

namespace Syncle\Services\Deployers;

use Syncle\Services\Executors\CommandExecutor;

class BaseDeployer
{

    public function __construct( CommandExecutor $executor )
    {
        $this->executor = $executor;
    }

    public function outputErrors( $errors, $log )
    {
        if( !empty( $errors ) )
        {
            // Get Maximun length.
            $maxLength = 0;

            foreach( $errors as $error ) $maxLength = max( $maxLength,
                                                           strlen( trim( $error ) ) );

            // Format error output.
            foreach( $errors as $error )
            {
                if( $log ) \Log::info( $error );

                $this->output[] = '<error>'.sprintf( '  %-'.$maxLength.'s  ',
                                                     trim( $error ) ).'</error>';
            }
        }
    }

    public function getOutput()
    {
        return $this->output;
    }

}