<?php

namespace Syncle\Services\Deployers;

class RsyncDeployer implements DeployerInterface
{

    public function deploy( $commandLine, $verbose = false, $log = false )
    {
        $outputs = '';

        exec( $commandLine, $outputs );

        $colored = array( );
        $fileCnt = 0;

        foreach( $outputs as $line )
        {
            // Put in log.
            if( $log ) \Log::info( $line );

            // Maybe for Mac
            if( starts_with( $line, "Number of files transferred" ) )
            {
                $parts = explode( ":", $line );
                $colored[] = \Lang::trans( 'syncle::SyncleCommand.MaxFileTransferred',
                                           array( 'file' => trim( $parts[1] ) ) );
            }
            // Maybe for Mac
            elseif( starts_with( $line, "Total transferred file size" ) )
            {
                $parts = explode( ":", $line );
                $colored[] = \Lang::trans( 'syncle::SyncleCommand.LinuxFileTransferred',
                                           array( 'file' => trim( $parts[1] ) ) );
            }
            // For Linux
            elseif( $verbose && $line == 'sending incremental file list' )
            {
                $colored[] = \Lang::trans( 'syncle::SyncleCommand.LinuxSentList' );
            }
            // For Linux
            elseif( starts_with( $line, 'sent ' ) )
            {
                $parts = explode( ' ', str_replace( '  ', ' ', $line ) );

                $colored[] = \Lang::trans( 'syncle::SyncleCommand.LinuxSentByte',
                                           array( 'byte' => trim( $parts[1] ) ) );

                $colored[] = \Lang::trans( 'syncle::SyncleCommand.LinuxRecievedByte',
                                           array( 'byte' => trim( $parts[4] ) ) );
            }
            // For Linux
            elseif( starts_with( $line, 'total size is ' ) )
            {
                $parts = explode( ' ', str_replace( '  ', ' ', $line ) );

                $colored[] = \Lang::trans( 'syncle::SyncleCommand.LinuxTotalTransferred',
                                           array( 'byte' => trim( $parts[3] ) ) );

                $colored[] = \Lang::trans( 'syncle::SyncleCommand.LinuxFileTransferred',
                                           array( 'file' => $fileCnt ) );
            }
            elseif( $verbose )
            {
                $fileCnt++;
                $colored[] = $line;
            }
        }
        return $colored;
    }

}
