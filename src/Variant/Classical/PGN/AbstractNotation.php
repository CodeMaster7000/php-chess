<?php

namespace Chess\Variant\Classical\PGN;

/**
 * Abstract notation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class AbstractNotation
{
    public static function values(): array
    {
        return (new \ReflectionClass(get_called_class()))->getConstants();
    }
}
