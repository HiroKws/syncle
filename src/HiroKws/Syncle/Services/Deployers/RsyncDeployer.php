<?php

namespace Syncle\Services\Deployers;

class RsyncDeployer implements DeployerInterface
{

    public function deploy( $commandLine, $verbose = false, $log = false )
    {
        $outputs = '';

        exec( $commandLine, $outputs );

        $colored = array( );

        foreach( $outputs as $line )
        {
            if( $log ) \Log::info( $line );

            if( starts_with( $line, "Number of files transferred" ) )
            {
                $parts = explode( ":", $line );
                $colored[] = ( "<comment>".trim( $parts[1] ).
                    "</comment><info> ファイルを転送しました。</info>" );
            }
            elseif( starts_with( $line, "Total transferred file size" ) )
            {
                $parts = explode( ":", $line );
                $colored[] = ( "<comment>".trim( $parts[1] ).
                    "</comment><info> 転送終了。</info>" );
            }
            elseif( $verbose )
            {
                $colored[] = $line;
            }
        }
        return $colored;
    }

}
