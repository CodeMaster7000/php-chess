<?php

namespace Chess\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\PieceArray;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FEN\Str;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

/**
 * StrToBoard
 *
 * Converts a FEN string to a chessboard object.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class StrToBoard
{
    protected array $size;

    protected Str $fenStr;

    protected string $string;

    protected array $fields;

    protected string $castlingAbility;

    protected array $castlingRule;

    public function __construct(string $string)
    {
        $this->size = Square::SIZE;
        $this->fenStr = new Str();
        $this->string = $this->fenStr->validate($string);
        $this->fields = array_filter(explode(' ', $this->string));
        $this->castlingAbility = $this->fields[2];
        $this->castlingRule = (new CastlingRule())->getRule();
    }

    public function create(): Board
    {
        try {
            $pieces = (new PieceArray(
                $this->fenStr->toAsciiArray($this->fields[0]),
                $this->size,
                $this->castlingRule
            ))->getArray();
            $board = (new Board(
                $pieces,
                $this->castlingAbility
            ))->setTurn($this->fields[1])->setStartFen($this->string);
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $this->enPassant($board);
    }

    protected function enPassant(Board $board): Board
    {
        if ($this->fields[3] !== '-') {
            foreach ($pieces = $board->getPieces($this->fields[1]) as $piece) {
                if ($piece->getId() === Piece::P) {
                    if (in_array($this->fields[3], $piece->getCaptureSqs())) {
                        $piece->setEnPassantSq($this->fields[3]);
                    }
                }
            }
        }

        return $board;
    }
}
