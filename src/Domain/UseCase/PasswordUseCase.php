<?php

namespace App\Domain\UseCase;

class PasswordUseCase
{
    public static function generatePassword(int $length = 8): string
    {
        $validChars =
            "abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789";
        $password = "";
        $charsLength = strlen($validChars);

        while (strlen($password) < $length) {
            $num = rand(0, $charsLength - 1);
            $password .= $validChars[$num];
        }

        return $password;
    }

    public static function verifyPassword(
        string $user_password,
        string $database_hash
    ): bool {
        if (empty($user_password) || empty($database_hash)) {
            return false;
        }
        $user_hash = password_hash($user_password, PASSWORD_DEFAULT, [
            "cost" => 32,
        ]);

        return hash_equals($user_hash, $database_hash);
    }
}
