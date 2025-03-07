<?php

namespace Chess\Tests\Unit\Media\FEN\Field;

use Chess\Media\FEN\Field\JpgToPiecePlacement;
use Chess\Tests\AbstractUnitTestCase;

class JpgToPiecePlacementTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function predict_01_kaufman()
    {
        $filename = self::DATA_FOLDER.'/img/01_kaufman.jpg';

        $expected = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K';

        $this->assertSame($expected, (new JpgToPiecePlacement($filename))->predict());
    }

    /**
     * @test
     */
    public function predict_02_kaufman()
    {
        $filename = self::DATA_FOLDER.'/img/02_kaufman.jpg';

        $expected = '3r2k1/p2r1p1p/1p2p1p1/q4n2/3P4/PQ5P/1P1RNPP1/3R2K1';

        $this->assertSame($expected, (new JpgToPiecePlacement($filename))->predict());
    }
}
