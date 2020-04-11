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

namespace Tests\Projects\Application\Command;

use App\Projects\Application\Command\ArchiveProjectCommand;
use App\Projects\Application\Command\ArchiveProjectCommandHandler;
use App\Projects\Domain\Exception\ProjectAlreadyArchivedException;
use App\Projects\Domain\Model\Project;
use App\Projects\Domain\Model\Project\ProjectId;
use App\Projects\Domain\Model\Project\ProjectName;
use App\Projects\Domain\Model\Project\ProjectToken;
use App\Projects\Domain\Model\Project\ProjectUrl;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Users\Domain\Model\User\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ArchiveProjectCommandHandlerTest extends TestCase
{
    private Project $project;
    private ArchiveProjectCommand $command;
    private ArchiveProjectCommandHandler $handler;

    protected function setUp()
    {
        $id = '70ffba47-a7e5-40bf-90fc-0542ff44d891';
        $name = 'Cool project';
        $url = 'https://coolproject.dev';
        $userId = '3c9ec32a-9c3a-4be1-b64d-0a0bb6ddf28f';

        $this->project = Project::create(ProjectId::fromString($id), ProjectName::fromString($name), ProjectUrl::fromString($url), ProjectToken::fromString('d15e6e18cd0a8ef2672e0f392368cc56'), UserId::fromString($userId));
        $this->command = new ArchiveProjectCommand($id);
        $repoMock = $this->createMock(ProjectRepositoryInterface::class);
        $repoMock->expects($this->once())
            ->method('get')
            ->with(ProjectId::fromString($id))
            ->willReturn($this->project);

        $this->handler = new ArchiveProjectCommandHandler($repoMock);
    }

    public function testArchiveProject()
    {
        $this->handler->__invoke($this->command);
        $this->assertTrue($this->project->isArchived());
    }

    public function testProjectCannotBeArchivedTwice()
    {
        $this->project->archive();
        $this->expectException(ProjectAlreadyArchivedException::class);
        $this->handler->__invoke($this->command);
    }
}
