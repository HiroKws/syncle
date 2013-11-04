<?php

namespace Syncle\Services\Deployers;

use Syncle\Services\Executors\CommandExecutor;

/**
 * Base class for deployer classes.
 */
class BaseDeployer
{
    /**
     * Output messages.
     *
     * @var type array Output messages.
     */
    protected $output;

    /**
     * Constructor to get depedencies.
     *
     * @param \Syncle\Services\Executors\CommandExecutor $executor
     */
    public function __construct( CommandExecutor $executor )
    {
        $this->executor = $executor;
    }

    /**
     * Output errors to log & $output property.
     *
     * @param type $errors
     * @param type $log
     */
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