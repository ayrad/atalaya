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

namespace App\Projects\Presentation\Api\Controller;

use App\Projects\Application\Command\EditProjectCommand;
use App\Projects\Domain\Model\Project;
use App\Projects\Domain\Model\Project\ProjectId;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Projects\Presentation\Api\Input\EditProjectInput;
use App\Shared\Presentation\Api\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EditProjectController extends AbstractController
{
    public function __invoke(string $id, ProjectRepositoryInterface $projectRepo, EditProjectInput $input, array $validationErrors): Response
    {
        if (!$this->isGranted(Project::EDIT, $projectRepo->get(ProjectId::fromString($id)))) {
            return new Response(null, Response::HTTP_UNAUTHORIZED);
        }

        if (count($validationErrors)) {
            return $this->validationErrorResponse($validationErrors);
        }

        $command = new EditProjectCommand($id, $input->name ?? '', $input->url ?? '', $input->platform ?? '');
        $this->dispatch($command);

        return new Response('', Response::HTTP_OK);
    }
}
