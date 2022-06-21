<?php

namespace Chess\Eval;

use Chess\Board;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;

/**
 * Material.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class MaterialEval extends AbstractEval
{
    const NAME = 'Material';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Color::W => 0,
            Color::B => 0,
        ];
    }

    public function eval(): array
    {
        foreach ($this->board->getPiecesByColor(Color::W) as $piece) {
            if ($piece->getId() !== Piece::K) {
                $this->result[Color::W] += $this->value[$piece->getId()];
            }
        }
        foreach ($this->board->getPiecesByColor(Color::B) as $piece) {
            if ($piece->getId() !== Piece::K) {
                $this->result[Color::B] += $this->value[$piece->getId()];
            }
        }

        return $this->result;
    }
}
