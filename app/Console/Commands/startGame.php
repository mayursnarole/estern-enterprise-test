<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class startGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'startGame';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the game of team assignment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $strTeamA = trim( $this->ask('Enter A Teams players:') );
       $strTeamB = trim( $this->ask('Enter B team players:') );

       $this->log( 'Team A players: ' . $strTeamA );
       $this->log( 'Team B players: ' . $strTeamB );

       $objTeamAssignment = new \App\Models\CTeamAssignment( $strTeamA, $strTeamB );

       $boolIsValid = $objTeamAssignment->validate();

       if( false == $boolIsValid ) {
           $this->logErrors( $objTeamAssignment->getErrorMsgs() );
           return false;
       }
      
       $this->log( 'Update Team A: ' . implode( ',', $objTeamAssignment->getUpdatedTeamA() ) );

       $this->log( 'Team A: ' . $objTeamAssignment->getGameWinner() );

    }

    public function logErrors( $arrErrors ) {
        foreach( $arrErrors as $strError ) {
            $this->error( $strError );
        }
    }

    public function log( String $strMsg ) {
        $this->info( $strMsg );
    }
}
