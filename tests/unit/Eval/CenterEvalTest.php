<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\CenterEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class CenterEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function B25()
    {
        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');

        $board = (new SanPlay($B25))->validate()->getBoard();

        $expected = [
            'w' => 37.73,
            'b' => 34.73,
        ];

        $ctrEval = (new CenterEval($board))->eval();

        $this->assertSame($expected, $ctrEval);
    }

    /**
     * @test
     */
    public function B56()
    {
        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');

        $board = (new SanPlay($B56))->validate()->getBoard();

        $expected = [
            'w' => 47.0,
            'b' => 36.8,
        ];

        $ctrEval = (new CenterEval($board))->eval();

        $this->assertSame($expected, $ctrEval);
    }

    /**
     * @test
     */
    public function C60()
    {
        $C60 = file_get_contents(self::DATA_FOLDER.'/sample/C60.pgn');

        $board = (new SanPlay($C60))->validate()->getBoard();

        $expected = [
            'w' => 37.73,
            'b' => 34.73,
        ];

        $ctrEval = (new CenterEval($board))->eval();

        $this->assertSame($expected, $ctrEval);
    }
}
