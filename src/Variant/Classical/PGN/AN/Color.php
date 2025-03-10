<?php

namespace Chess\Variant\Classical\PGN\AN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\AbstractNotation;
use Chess\Variant\Classical\PGN\ValidationInterface;

/**
 * Color.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class Color extends AbstractNotation implements ValidationInterface
{
    const W = 'w';
    const B = 'b';

    /**
     * Validate.
     *
     * @param string $value
     * @return string if the value is valid
     * @throws UnknownNotationException
     */
    public static function validate(string $value): string
    {
        if (!in_array($value, self::values())) {
            throw new UnknownNotationException();
        }

        return $value;
    }

    /**
     * Returns the opposite color.
     *
     * @param string $color
     * @return string
     */
    public static function opp(string $color): string
    {
        if ($color === self::W) {
            return self::B;
        }

        return self::W;
    }
}
