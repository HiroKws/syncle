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
            if( $log ) \Log::info( $line );

            // Maybe for Mac
            if( starts_with( $line, "Number of files transferred" ) )
            {
                $parts = explode( ":", $line );
                $colored[] = \Lang::trans( 'syncle::MaxFileTransferred',
                                           array( 'file' => trim( $parts[1] ) ) );
            }
            // Maybe for Mac
            elseif( starts_with( $line, "Total transferred file size" ) )
            {
                $parts = explode( ":", $line );
                $colored[] = "<comment>".trim( $parts[1] ).
                    "</comment><info> 転送終了。</info>";
            }
            // For Linux
            elseif( $verbose && $line == 'sending incremental file list' )
            {
                $colored[] = '<info>sending incremental file list</info>';
            }
            // For Linux
            elseif( starts_with( $line, 'sent ' ) )
            {
                $parts = explode( ' ', str_replace( '  ', ' ', $line ) );
                $colored[] = '<comment>'.trim( $parts[1] ).
                    '</comment><info> bytes sent.</info>';
                $colored[] = '<comment>'.trim( $parts[4] ).
                    '</comment><info> bytes recieved.</info>';
            }
            // For Linux
            elseif( starts_with( $line, 'total size is ' ) )
            {
                $parts = explode( ' ', str_replace( '  ', ' ', $line ) );
                $colored[] = ('<comment>'.trim( $parts[3] ).
                    '</comment><info> bytes transferred totally.</info>');

                $colored[] = \Lang::trans( 'syncle::LinuxFileTransferred',
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
