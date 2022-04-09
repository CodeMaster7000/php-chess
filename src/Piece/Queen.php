<?php

namespace Chess\Piece;

use Chess\PGN\Symbol;
use Chess\Piece\Type\RookType;

/**
 * Queen class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Queen extends Slider
{
    /**
     * @var \Chess\Piece\Rook
     */
    private Rook $rook;

    /**
     * @var \Chess\Piece\Bishop
     */
    private Bishop $bishop;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     */
    public function __construct(string $color, string $sq)
    {
        parent::__construct($color, $sq, Symbol::Q);

        $this->rook = new Rook($color, $sq, RookType::SLIDER);
        $this->bishop = new Bishop($color, $sq);

        $this->setTravel();
    }

    /**
     * Calculates the piece's travel.
     */
    protected function setTravel(): void
    {
        $this->travel = (object) array_merge(
            (array) $this->rook->getTravel(),
            (array) $this->bishop->getTravel()
        );
    }
}
