<?php

require_once 'config/database.php';
use \Firebase\JWT\JWT;
use \Dotenv\Dotenv;

class Usuario {

    public static function createUser($username, $password, $email) {
        global $pdo;
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO usuarios (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username, ':password' => $hashedPassword, ':email' => $email]);
        
        return $pdo->lastInsertId();
    }

    public static function getUserByUsername($username) {
        global $pdo;
        
        $sql = "SELECT * FROM usuarios WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function generateJWT($userId, $username) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // jwt valid for 1 hour from the issued time
        $payload = array(
            "iss" => "usuarios-api",
            "iat" => $issuedAt,
            "exp" => $expirationTime,
            "userId" => $userId,
            "username" => $username
        );
        
        $jwt = JWT::encode($payload, $_ENV['JWT_SECRET']);
        return $jwt;
    }
}
