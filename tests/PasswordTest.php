<?php

namespace App\Tests;

use App\Domain\UseCase\PasswordUseCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PasswordTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $iteration = 1000000;
        $passwordList = [];
        for ($i = 0; $i < $iteration; $i++) {
            $password = PasswordUseCase::generatePassword();
            $this->assertNotContains($password,['L','l','i','I']);
            $this->assertSame(8, strlen($password));
            $passwordList[] = $password;
        }
        $this->assertTrue(count(array_unique($passwordList)) === $iteration, 'Array is not unique');
    }

    public function testSomethingElse(): void
    {
        PasswordUseCase::verifyPassword('test', 'test');
    }
}
