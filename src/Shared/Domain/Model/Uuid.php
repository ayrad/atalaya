<?php
/*
 *
 * @copyright 2019-present Mohammadi El Youzghi. All rights reserved
 * @author    Mohammadi El Youzghi (mo.elyouzghi@gmail.com)
 *
 * @link      https://github.com/melyouz
 *
 * @licence   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

declare(strict_types=1);

namespace App\Shared\Domain\Model;

use Assert\Assertion;

class Uuid extends AbstractStringValueObject
{
    public static function fromString(string $value): self
    {
        Assertion::notBlank($value);
        Assertion::uuid($value);

        return new static($value);
    }
}
