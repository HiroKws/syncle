<?php

namespace Syncle\Services\Deployers;

class RsyncDeployer extends BaseDeployer implements DeployerInterface
{
    protected $output;

    public function run( $commandLine, $verbose, $log )
    {
        $result = $this->executor->execute( $commandLine );

        $outputs = $this->executor->getOutput();
        $errorOutputs = $this->executor->getErrorOutput();

        $this->output = array( );
        $fileCnt = -1; // One empty line will be displayed in Linux rsync command.

        foreach( $outputs as $line )
        {
            // Put in log.
            if( $log ) \Log::info( $line );

            // Maybe for Mac
            if( starts_with( $line, "Number of files transferred" ) )
            {
                $parts = explode( ":", $line );
                $this->output[] = \Lang::trans( 'syncle::SyncleCommand.MaxFileTransferred',
                                                array( 'file' => trim( $parts[1] ) ) );
            }
            // Maybe for Mac
            elseif( starts_with( $line, "Total transferred file size" ) )
            {
                $parts = explode( ":", $line );
                $this->output[] = \Lang::trans( 'syncle::SyncleCommand.LinuxFileTransferred',
                                                array( 'file' => trim( $parts[1] ) ) );
            }
            // For Linux
            elseif( $verbose && $line == 'sending incremental file list' )
            {
                $this->output[] = \Lang::trans( 'syncle::SyncleCommand.LinuxSentList' );
            }
            // For Linux
            elseif( starts_with( $line, 'sent ' ) )
            {
                // Replace double spaces to single one.
                $parts = explode( ' ', str_replace( '  ', ' ', $line ) );

                $this->output[] = \Lang::trans( 'syncle::SyncleCommand.LinuxSentByte',
                                                array( 'byte' => trim( $parts[1] ) ) );

                $this->output[] = \Lang::trans( 'syncle::SyncleCommand.LinuxRecievedByte',
                                                array( 'byte' => trim( $parts[4] ) ) );
            }
            // For Linux
            elseif( starts_with( $line, 'total size is ' ) )
            {
                // Replace double spaces to single one.
                $parts = explode( ' ', str_replace( '  ', ' ', $line ) );

                $this->output[] = \Lang::trans( 'syncle::SyncleCommand.LinuxTotalTransferred',
                                                array( 'byte' => trim( $parts[3] ) ) );

                $this->output[] = \Lang::trans( 'syncle::SyncleCommand.LinuxFileTransferred',
                                                array( 'file' => $fileCnt ) );
            }
            else
            {
                if( $line != '' ) $fileCnt++;

                if( $verbose ) $this->output[] = $line;
            }
        }

        $this->outputErrors( $errorOutputs, $log );

        return $result;
    }

}
