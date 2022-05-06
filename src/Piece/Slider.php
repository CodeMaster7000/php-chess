<?php

namespace Chess\Piece;

/**
 * Class that represents a bishop, a rook or a queen.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class Slider extends AbstractPiece
{
    /**
     * Constructor.
     *
     * @param $color
     * @param $sq
     * @param $id
     */
    public function __construct(string $color, string $sq, string $id)
    {
        parent::__construct($color, $sq, $id);
    }

    /**
     * Gets the defended squares.
     *
     * @return mixed array|null
     */
    public function getDefendedSqs(): ?array
    {
        $sqs = [];
        foreach ($this->travel as $direction) {
            foreach ($direction as $sq) {
                if (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                    $sqs[] = $sq;
                    break 1;
                } elseif (in_array($sq, $this->board->getSqEval()->used->{$this->oppColor()})) {
                    break 1;
                }
            }
        }

        return $sqs;
    }

    /**
     * Gets the squares where the piece can be placed on.
     *
     * @return mixed array|null
     */
    public function sqs(): ?array
    {
        $moves = [];
        foreach ($this->travel as $direction) {
            foreach ($direction as $sq) {
                if (
                    !in_array($sq, $this->board->getSqEval()->used->{$this->getColor()}) &&
                    !in_array($sq, $this->board->getSqEval()->used->{$this->oppColor()})
                ) {
                    $moves[] = $sq;
                } elseif (in_array($sq, $this->board->getSqEval()->used->{$this->oppColor()})) {
                    $moves[] = $sq;
                    break 1;
                } elseif (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                    break 1;
                }
            }
        }

        return $moves;
    }
}
