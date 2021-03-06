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

namespace App\Issues\Presentation\Api\Controller;

use App\Issues\Application\Command\AddIssueCommand;
use App\Issues\Presentation\Api\Input\AddIssueInput;
use App\Shared\Presentation\Api\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddIssueController extends AbstractController
{
    private ?string $projectId;
    private ?string $projectToken;

    public function __invoke(Request $request, AddIssueInput $input, array $validationErrors): Response
    {
        if (count($validationErrors)) {
            return $this->validationErrorResponse($validationErrors);
        }

        $this->handleRequest($request);

        if (empty($this->projectId) || empty($this->projectToken)) {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }

        $issueId = $this->uuid();
        $command = new AddIssueCommand($issueId, $this->projectId, $this->projectToken, $input->toDto());
        $this->dispatch($command);

        return new JsonResponse(['id' => $issueId], Response::HTTP_CREATED);
    }

    private function handleRequest(Request $request): void
    {
        $this->projectId = $request->getUser();
        $this->projectToken = $request->getPassword();
    }
}
