<?php

namespace Chess\Tests\Functional;

use Chess\Game;
use Chess\Tests\AbstractFunctionalTestCase;

class GameTest extends AbstractFunctionalTestCase
{
    /**
     * @dataProvider filenameData
     * @test
     */
    public function play_sample_games($filename)
    {
        $game = new Game();
        $contents = file_get_contents(self::DATA_FOLDER."/game/$filename");
        $pairs = array_filter(preg_split('/[0-9]+\./', $contents));
        $moves = [];
        foreach ($pairs as $pair) {
            $moves[] = array_values(array_filter(explode(' ', $pair)));
        }
        $moves = array_values(array_filter($moves));
        for ($i = 0; $i < count($moves); ++$i) {
            $whiteMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][0]));
            $this->assertTrue($game->play('w', $whiteMove));
            if (isset($moves[$i][1])) {
                $blackMove = str_replace("\r", '', str_replace("\n", '', $moves[$i][1]));
                $this->assertTrue($game->play('b', $blackMove));
            }
        }
    }

    public function filenameData()
    {
        $data = [];
        for ($i = 1; $i <= 99; ++$i) {
            $i <= 9 ? $data[] = ["0$i.pgn"] : $data[] = ["$i.pgn"];
        }

        return $data;
    }
}
