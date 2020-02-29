<?php
/**
 *
 * @copyright 2020 Mohammadi El Youzghi. All rights reserved
 * @author    Mohammadi El Youzghi (mo.elyouzghi@gmail.com)
 *
 * @link      https://github.com/ayrad
 *
 * @licence   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

declare(strict_types=1);

namespace App\Projects\Presentation\Api\Controller;

use App\Projects\Application\Query\GetProjectQuery;
use App\Projects\Domain\Model\Project;
use App\Projects\Domain\Model\ProjectId;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Shared\Presentation\Api\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetProjectController extends AbstractController
{
    public function __invoke(string $id, ProjectRepositoryInterface $projectRepo): Response
    {
        if (!$this->isGranted(Project::VIEW, $projectRepo->get(ProjectId::fromString($id)))) {
            return new Response(null, Response::HTTP_UNAUTHORIZED);
        }

        $query = new GetProjectQuery($id);
        $project = $this->query($query);

        return $this->toJsonResponse($project);
    }
}
