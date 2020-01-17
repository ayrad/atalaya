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

namespace App\Issues\Application\Command;

use App\Issues\Domain\Exception\IssueAlreadyResolvedException;
use App\Issues\Domain\Exception\IssueNotFoundException;
use App\Issues\Domain\Repository\IssueRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

class ResolveIssueCommandHandler implements CommandHandlerInterface
{
    /**
     * @var IssueRepositoryInterface
     */
    private IssueRepositoryInterface $issueRepo;

    public function __construct(IssueRepositoryInterface $issueRepo)
    {
        $this->issueRepo = $issueRepo;
    }

    public function __invoke(ResolveIssueCommand $command)
    {
        try {
            $issue = $this->issueRepo->get($command->getId());
            $issue->resolve();
            $this->issueRepo->save($issue);
        } catch (IssueNotFoundException $e) {
            // noop
        } catch (IssueAlreadyResolvedException $e) {
            // noop
        }

    }
}
