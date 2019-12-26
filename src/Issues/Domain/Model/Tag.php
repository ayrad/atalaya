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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Issues\Domain\Model\Issue", inversedBy="tags")
     * @var Issue
     */
    private Issue $issue;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private string $value;

    private function __construct(Issue $issue, TagName $name, TagValue $value)
    {
        $this->issue = $issue;
        $this->name = $name->value();
        $this->value = $value->value();
    }

    public static function create(Issue $issue, TagName $name, TagValue $value)
    {
        return new self($issue, $name, $value);
    }

    public function getName(): TagName
    {
        return TagName::fromString($this->name);
    }

    public function getValue(): TagValue
    {
        return TagValue::fromString($this->value);
    }
}
