<?php
/**
 *
 * @copyright 2019 Mohammadi El Youzghi. All rights reserved
 * @author    Mohammadi El Youzghi (mo.elyouzghi@gmail.com)
 *
 * @link      https://github.com/ayrad
 *
 * @licence   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

declare(strict_types=1);

namespace App\Issues\Domain\Model;

use App\Shared\Domain\Model\AbstractIntegerValueObject;
use Assert\Assertion;

class FileLine extends AbstractIntegerValueObject
{
    public static function fromInteger(int $value): self
    {
        Assertion::notBlank($value);
        Assertion::greaterOrEqualThan($value, 1);

        return new self($value);
    }
}
