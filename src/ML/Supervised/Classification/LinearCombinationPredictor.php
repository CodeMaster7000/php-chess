<?php

namespace Chess\ML\Supervised\Classification;

use Chess\Board;
use Chess\HeuristicPicture;
use Chess\ML\Supervised\AbstractLinearCombinationPredictor;
use Chess\ML\Supervised\Classification\LinearCombinationLabeller;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Rubix\ML\Datasets\Unlabeled;

/**
 * LinearCombinationPredictor
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class LinearCombinationPredictor extends AbstractLinearCombinationPredictor
{
    public function predict(): string
    {
        $color = $this->board->getTurn();
        foreach ($this->board->getPiecesByColor($color) as $piece) {
            foreach ($piece->getLegalMoves() as $square) {
                $clone = unserialize(serialize($this->board));
                switch ($piece->getIdentity()) {
                    case Symbol::KING:
                        if ($clone->play(Convert::toStdObj($color, Symbol::KING.$square))) {
                            $this->result[] = [ Symbol::KING.$square => $this->evaluate($clone) ];
                        } elseif ($clone->play(Convert::toStdObj($color, Symbol::KING.'x'.$square))) {
                            $this->result[] = [ Symbol::KING.'x'.$square => $this->evaluate($clone) ];
                        }
                        break;
                    case Symbol::PAWN:
                        if ($clone->play(Convert::toStdObj($color, $square))) {
                            $this->result[] = [ $square => $this->evaluate($clone) ];
                        } elseif ($clone->play(Convert::toStdObj($color, $piece->getFile()."x$square"))) {
                            $this->result[] = [ $piece->getFile()."x$square" => $this->evaluate($clone) ];
                        }
                        break;
                    default:
                        if ($clone->play(Convert::toStdObj($color, $piece->getIdentity().$square))) {
                            $this->result[] = [ $piece->getIdentity().$square => $this->evaluate($clone) ];
                        } elseif ($clone->play(Convert::toStdObj($color, "{$piece->getIdentity()}x$square"))) {
                            $this->result[] = [ "{$piece->getIdentity()}x$square" => $this->evaluate($clone) ];
                        }
                        break;
                }
            }
        }

        $clone = unserialize(serialize($this->board));

        if ($clone->play(Convert::toStdObj($color, Symbol::CASTLING_SHORT))) {
            $this->result[] = [ Symbol::CASTLING_SHORT => $this->evaluate($clone) ];
        } elseif ($clone->play(Convert::toStdObj($color, Symbol::CASTLING_LONG))) {
            $this->result[] = [ Symbol::CASTLING_LONG => $this->evaluate($clone) ];
        }

        return $this->sort($color)->find();
    }

    protected function evaluate(Board $clone)
    {
        $balance = (new HeuristicPicture($clone->getMovetext()))->take()->getBalance();
        $end = end($balance);
        $color = $this->board->getTurn();
        $label = (new LinearCombinationLabeller($this->permutations))->label($end)[$color];

        return [
            'label' => $label,
            'linear_combination' => $this->combine($end, $label),
        ];
    }

    protected function sort(string $color)
    {
        usort($this->result, function ($a, $b) use ($color) {
            if ($color === Symbol::WHITE) {
                $current = current($b)['linear_combination'] <=> current($a)['linear_combination'];
            } else {
                $current = current($a)['linear_combination'] <=> current($b)['linear_combination'];
            }

            return $current;
        });

        return $this;
    }

    protected function find()
    {
        foreach ($this->result as $key => $val) {
            $current = current($val);
            if ($this->prediction() === $current['label']) {
                return key($this->result[$key]);
            }
        }

        return key($this->result[0]);
    }

    protected function prediction()
    {
        $balance = (new HeuristicPicture($this->board->getMovetext()))->take()->getBalance();
        $end = end($balance);
        $dataset = new Unlabeled([$end]);

        return current($this->estimator->predict($dataset));
    }
}
