<?php

namespace App\Tests\State;

use App\Document\User;
use App\State\UserPasswordHasher;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHasherTest extends TestCase
{
    /**
     * @var ProcessorInterface&MockObject
     */
    private ProcessorInterface $processor;
    /**
     * @var UserPasswordHasherInterface&MockObject
     */
    private UserPasswordHasherInterface $hasher;
    private $userPasswordHasher;

    protected function setUp(): void
    {
        // Mock the ProcessorInterface and UserPasswordHasherInterface
        $this->processor = $this->createMock(ProcessorInterface::class);
        $this->hasher = $this->createMock(UserPasswordHasherInterface::class);

        // Initialize UserPasswordHasher with the mocked dependencies
        $this->userPasswordHasher = new UserPasswordHasher($this->processor, $this->hasher);
    }

    public function testProcessWithoutPlainPassword(): void
    {
        $user = $this->createMock(User::class);
        $operation = $this->createMock(Operation::class);

        $user->method('getPlainPassword')->willReturn(null);

        $this->processor->expects($this->once())
            ->method('process')
            ->with($user, $operation, [], [])
            ->willReturn($user);

        $result = $this->userPasswordHasher->process($user, $operation);

        $this->assertSame($user, $result);
    }

    public function testProcessWithPlainPassword(): void
    {
        $user = $this->createMock(User::class);
        $operation = $this->createMock(Operation::class);

        $user->method('getPlainPassword')->willReturn('plain_password');

        $this->hasher->expects($this->once())
            ->method('hashPassword')
            ->with($user, 'plain_password')
            ->willReturn('hashed_password');

        $user->expects($this->once())
            ->method('setPassword')
            ->with('hashed_password');

        $user->expects($this->once())
            ->method('eraseCredentials');

        $this->processor->expects($this->once())
            ->method('process')
            ->with($user, $operation, [], [])
            ->willReturn($user);

        $result = $this->userPasswordHasher->process($user, $operation);

        $this->assertSame($user, $result);
    }
}
