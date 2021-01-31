<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CTeamAssignmentTest extends TestCase
{

    public function getTeamAssignmentObject( $strTeamA = '', $strTeamB = '' ) {
        return new \App\Models\CTeamAssignment( $strTeamA, $strTeamB );
    }

    public function testCTestAssignmentAcceptsStringArgumentsForTeamMembers()
    {
        $objTeamAssignment = $this->getTeamAssignmentObject( '', '' );
        $this->assertTrue( true );
    }

    public function testTeamAssignmentHasValidateFunction()
    {
        $objTeamAssignment = $this->getTeamAssignmentObject( '', '' );
        $objTeamAssignment->validate();
        $this->assertTrue( true );

    }

    /**
     * @dataProvider dataProviderInputHasErrors
     */
    public function testUserInputHasErrors( $strTeamA, $strTeamB )
    {
        $objTeamAssignment = $this->getTeamAssignmentObject( $strTeamA, $strTeamB );
        $boolIsValid = $objTeamAssignment->validate();
        $this->assertFalse( $boolIsValid );

    }

    public function dataProviderInputHasErrors() {
        return [
            'both strings are empty' => [ '', '' ],
            'team A empty' => [ '', 'asdf, asdf, asfd' ],
            'team B empty' => [ 'asdf, asdf, asdf', '' ],
            'team has less members' => [ '45, 95, 65', '23,56,25,3,14' ],
            'values not in range of 0 to 100' => [ '35, 100, 20, 50, 101', '23,56,25,3,14' ]
        ];
    }

    /**
     * @dataProvider dataProviderWinningResult
     */
    public function testWinningResult( $strTeamA, $strTeamB, $strExpectedWinningResult )
    {
        $objTeamAssignment = $this->getTeamAssignmentObject( $strTeamA, $strTeamB );
        $boolIsValid = $objTeamAssignment->validate();
        $strWiningResult = $objTeamAssignment->getGameWinner();
        $this->assertTrue( $boolIsValid );
        $this->assertEquals( $strExpectedWinningResult, $strWiningResult);

    }

    public function dataProviderWinningResult() {
        return [
            'team A wins' => [ '35, 100, 20, 50, 40', '35, 10, 30, 20, 90', 'Win' ],
            'team A lose' => [ '35, 10, 30, 20, 90', '35, 100, 80, 50, 40', 'Lose' ],
        ];
    }
}
