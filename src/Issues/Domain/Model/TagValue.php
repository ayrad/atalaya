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

use App\Shared\Domain\Model\AbstractStringValueObject;
use Assert\Assertion;

class TagValue extends AbstractStringValueObject
{
    public static function fromString(string $value): self
    {
        Assertion::maxLength($value, self::MAX_LENGTH);

        return new self($value);
    }
}
