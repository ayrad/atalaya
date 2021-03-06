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

namespace App\Users\Application\Command;

use App\Security\Application\Encoder\UserPasswordEncoderInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Util\TokenGenerator;
use App\Users\Domain\Exception\EmailTakenException;
use App\Users\Domain\Model\User;
use App\Users\Domain\Model\User\UserConfirmationToken;
use App\Users\Domain\Model\User\UserEncodedPassword;
use App\Users\Domain\Repository\UserRepositoryInterface;

class RegisterUserCommandHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepo;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @var TokenGenerator
     */
    private TokenGenerator $tokenGenerator;

    public function __construct(UserRepositoryInterface $userRepo, UserPasswordEncoderInterface $userPasswordEncoder, TokenGenerator $tokenGenerator)
    {
        $this->userRepo = $userRepo;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        if ($this->userRepo->emailExists($command->getEmail())) {
            throw new EmailTakenException();
        }

        $confirmationToken = UserConfirmationToken::fromString($this->tokenGenerator->randomToken());
        $user = new User($command->getId(), $command->getName(), $command->getEmail(), $confirmationToken);
        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $command->getPlainPassword());
        $user->setPassword(UserEncodedPassword::fromString($encodedPassword));

        $this->userRepo->save($user);
    }
}
