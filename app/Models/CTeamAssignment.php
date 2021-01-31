<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTeamAssignment extends Model
{
    use HasFactory;

    private $m_strTeamA;
    private $m_strTeamB;

    private $m_arrIntTeamA;
    private $m_arrIntTeamB;
    private $m_arrintUpdatedTeamA;

    protected $m_arrStrErrors;

    const REQUIRED_TEAM_MEMBERS = 5;

    public function __construct( String $strTeamA, String $strTeamB ) {
        $this->m_arrStrErrors = $this->m_arrintUpdatedTeamA = [];
        $this->setTeamA( $strTeamA );
        $this->setTeamB( $strTeamB );

        $this->convertTeamsInputToArray();

    }

    public function getTeamA() : String {
        return $this->m_strTeamA;
    }

    public function getTeamB() : String {
        return $this->m_strTeamB;
    }

    public function setTeamA (String $strTeamA ) {
        $this->m_strTeamA = trim( $strTeamA );
    }

    public function setTeamB (String $strTeamB ) {
        $this->m_strTeamB = trim( $strTeamB );
    }

    public function getTeamAMembers() : array {
        return $this->m_arrintTeamA;
    }

    public function getTeamBMembers() : array {
        return $this->m_arrintTeamB;
    }

    public function getUpdatedTeamA() : array {
        return $this->m_arrintUpdatedTeamA;
    }

    public function validate() : bool {
        $boolIsValid = $this->valArr( $this->getTeamAMembers() );
        if( false == $boolIsValid ) {
            $this->addErrorMsg( 'Team A is not having valid team members' );

        }

        $boolIsValid &= $this->valArr( $this->getTeamBMembers() );
        if( false == $boolIsValid ) {
            $this->addErrorMsg( 'Team B is not having valid team members' );

        }

        $boolIsValid &= $this->valRequiredTeamMembers( $this->getTeamAMembers() );
        if( false == $boolIsValid ) {
            $this->addErrorMsg( 'Team A is not haivng required team members. Found ' . count( $this->getTeamAMembers() ) . ' required: ' . self::REQUIRED_TEAM_MEMBERS );

        }
        $boolIsValid &= $this->valRequiredTeamMembers( $this->getTeamBMembers() );

        if( false == $boolIsValid ) {
            $this->addErrorMsg( 'Team B is not haivng required team members. Found ' . count( $this->getTeamBMembers() ) . ' required: ' . self::REQUIRED_TEAM_MEMBERS );

        }
    
        $boolIsValid &= $this->valInputRange( $this->getTeamAMembers() );

        if( false == $boolIsValid ) {
            $this->addErrorMsg( 'Team A team members values should be between 0 to 100.' );

        }

        $boolIsValid &= $this->valInputRange( $this->getTeamBMembers() );

        if( false == $boolIsValid ) {
            $this->addErrorMsg( 'Team B team members values should be between 0 to 100.' );

        }
        return $boolIsValid;
    }

    public function convertTeamsInputToArray() {
        $this->m_arrintTeamA = explode( ',', $this->getTeamA() );
        $this->m_arrintTeamB = explode( ',', $this->getTeamB() );

    }

    public function valArr( $arrintTeamMembers ) : bool {
        return is_array( $arrintTeamMembers );
    }

    public function valRequiredTeamMembers( $arrintTeamMembers ) : bool {
        return ( self::REQUIRED_TEAM_MEMBERS == count( $arrintTeamMembers ) );
    }

    public function valInputRange( $arrintTeamMembers ) : bool {
        foreach( $arrintTeamMembers as $intTeamMember ) {
            if( false === filter_var( $intTeamMember, FILTER_VALIDATE_INT, [ "options" =>[ "min_range"=>0, "max_range"=>101]]) ) {
                return false;
            }
        }
        return true;
    }

    public function addErrorMsg(String $strErrorMsg ) {
        array_push( $this->m_arrStrErrors, $strErrorMsg );
    }

    public function getErrorMsgs() : array {
        return $this->m_arrStrErrors;
    }

    public function getGameWinner() {

        $this->m_arrintUpdatedTeamA = [];
        $arrintTeamA = $this->getTeamAMembers();
        sort( $arrintTeamA );

        foreach( $this->getTeamBMembers() as $intTeamMemberB ) {

            foreach( $arrintTeamA as $intKey => $intTeamMemberA ) {
                    if( $this->isTeamAMemberHasLessPower( $intTeamMemberA, $intTeamMemberB) ) {
                        continue;
                    }
                    $this->m_arrintUpdatedTeamA[] = $intTeamMemberA;
                    unset( $arrintTeamA[$intKey] );
                    break;
                }
        }

        if( self::REQUIRED_TEAM_MEMBERS == count( $this->m_arrintUpdatedTeamA ) ) {
            return 'Win';
        } else {
            return 'Lose';
        }

    }

    public function isTeamAMemberHasLessPower( $intTeamMemberA, $intTeamMemberB ) {
       return (int) $intTeamMemberA <= (int) $intTeamMemberB;
    }
}
