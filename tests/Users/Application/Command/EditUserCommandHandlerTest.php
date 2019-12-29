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

use App\Users\Application\Command\EditUserCommand;
use App\Users\Application\Command\EditUserCommandHandler;
use App\Users\Application\Encoder\UserPasswordEncoderInterface;
use App\Users\Domain\Model\User;
use App\Users\Domain\Model\UserEmail;
use App\Users\Domain\Model\UserEncodedPassword;
use App\Users\Domain\Model\UserId;
use App\Users\Domain\Model\UserName;
use App\Users\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class EditUserCommandHandlerTest extends TestCase
{
    public function testEditUser()
    {
        $id = '3c9ec32a-9c3a-4be1-b64d-0a0bb6ddf28f';
        $name = 'John Doe';
        $email = 'johndoe@awesome-project.dev';
        $plainPassword = 'WhateverPlainPassword';
        $encodedPassword = 'WhateverEncodedPassword';
        $newName = 'Jane Doe';
        $newEmail = 'janedoe@awesome-project.dev';
        $newPlainPassword = '-_-WhateverPlainPassword-_-';
        $newEncodedPassword = '-_-WhateverEncodedPassword-_-';

        $user = User::register(UserId::fromString($id), UserName::fromString($name), UserEmail::fromString($email));
        $user->setPassword(UserEncodedPassword::fromString($encodedPassword));

        $newUser = User::register(UserId::fromString($id), UserName::fromString($newName), UserEmail::fromString($newEmail));
        $newUser->setPassword(UserEncodedPassword::fromString($newEncodedPassword));

        $command = new EditUserCommand($id, $newName, $newEmail, $newPlainPassword);

        $userPasswordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);
        $userPasswordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->with($user, $newPlainPassword)
            ->willReturn($newEncodedPassword);

        $repoMock = $this->createMock(UserRepositoryInterface::class);
        $repoMock->expects($this->once())
            ->method('save')
            ->with($newUser);
        $repoMock->expects($this->once())
            ->method('get')
            ->with(UserId::fromString($id))
            ->willReturn($user);

        $handler = new EditUserCommandHandler($repoMock, $userPasswordEncoderMock);
        $handler->__invoke($command);
    }
}