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

namespace App\Projects\Domain\Model\Project;

use App\Shared\Domain\Model\AbstractStringValueObject;
use Assert\Assertion;

class ProjectPlatform extends AbstractStringValueObject
{
    const MAX_LENGTH = 30;

    const PHP = 'PHP';
    const SYMFONY = 'Symfony';

    const CHOICES = [
        self::PHP,
        self::SYMFONY,
    ];

    public static function fromString(string $value): self
    {
        Assertion::notBlank($value);
        Assertion::maxLength($value, self::MAX_LENGTH);
        Assertion::inArray($value, self::CHOICES);

        return new self($value);
    }
}
