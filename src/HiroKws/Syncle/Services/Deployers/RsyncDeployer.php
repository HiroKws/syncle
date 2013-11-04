<?php

namespace Syncle\Services\Deployers;

/**
 * A deployer class for rsync command.
 *
 * This came from Larave 4 Cookbook. The code in this book, is maybe for Mac,
 * not for Linux rsync command. So added to handle output from Linux version rsync.
 */
class RsyncDeployer extends BaseDeployer implements DeployerInterface
{
    protected $output;

    /**
     * Execute rsync command with formatting & colorizing.
     *
     * @param string $commandLine A Command to execute.
     * @param boolean $verbose Verbose mose flag.
     * @param boolean $log Output log flag.
     * @return integer Execution code.
     */
    public function run( $commandLine, $verbose, $log )
    {
        $result = $this->executor->execute( $commandLine );

        $outputs = $this->executor->getOutput();
        $errorOutputs = $this->executor->getErrorOutput();

        $this->output = array( );
        $fileCnt = 0;

        foreach( $outputs as $output )
        {
            $line = trim( $output );

            // Put in log.
            if( $log ) \Log::info( $line );

            // Format & colorize output.
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
            elseif( starts_with( $line, 'sending incremental file list' ) )
            {
                if( $verbose ) $this->output[] = \Lang::trans( 'syncle::SyncleCommand.LinuxSentList' );
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
            // ...otherwise, maybe transfered file path.
            else
            {
                if( !empty( $line ) ) $fileCnt++;

                if( $verbose ) $this->output[] = $line;
            }
        }

        $this->outputErrors( $errorOutputs, $log );

        return $result;
    }

}
