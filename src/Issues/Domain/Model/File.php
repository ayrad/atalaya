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
 * @ORM\Embeddable()
 */
class File
{
    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private string $path;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $line;

    /**
     * @ORM\Column(type="string", length=1024)
     * @var string
     */
    private string $excerpt;

    private function __construct(FilePath $path, FileLine $line, FileExcerpt $excerpt)
    {
        $this->path = $path->value();
        $this->line = $line->value();
        $this->excerpt = $excerpt->value();
    }

    public static function create(FilePath $path, FileLine $line, FileExcerpt $excerpt)
    {
        return new self($path, $line, $excerpt);
    }

    public function getPath(): FilePath
    {
        return FilePath::fromString($this->path);
    }

    public function getLine(): FileLine
    {
        return FileLine::fromInteger($this->line);
    }

    public function getExcerpt(): FileExcerpt
    {
        return FileExcerpt::fromString($this->excerpt);
    }
}
