<?php

require_once 'config/database.php';
use \Firebase\JWT\JWT;

class Usuario {

    public static function createUser($username, $password, $email) {
        global $pdo;
                
        $sql = "INSERT INTO usuarios (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username, ':password' => $password, ':email' => $email]);
        
        return $pdo->lastInsertId();
    }

    public static function getUserByUsername($username) {
        global $pdo;
        
        $sql = "SELECT * FROM usuarios WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // get user by id
    public static function getUserById($userId) {
        global $pdo;
        
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $userId]);
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
        
        $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], "HS256");
        return $jwt;
    }

    // get all users
    public static function getUsers() {
        global $pdo;
        
        $sql = "SELECT id, username, email FROM usuarios";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateUser($userId, $username, $password, $email) {
        global $pdo;
    
        // Inicializamos un array para almacenar las condiciones de la consulta.
        $fields = [];
        $params = [':id' => $userId];  // El parámetro id siempre es necesario
    
        // Verificamos si cada campo tiene un valor y, en caso afirmativo, agregamos a los campos y parámetros.
        if (!empty($username)) {
            $fields[] = "username = :username";
            $params[':username'] = $username;
        }
        if (!empty($password)) {
            $fields[] = "password = :password";
            $params[':password'] = $password;
        }
        if (!empty($email)) {
            $fields[] = "email = :email";
            $params[':email'] = $email;
        }
    
        // Si no hay campos para actualizar, regresamos 0 (no se hizo ninguna actualización).
        if (empty($fields)) {
            return 0;
        }
    
        // Construimos la consulta SQL con los campos a actualizar.
        $sql = "UPDATE usuarios SET " . implode(", ", $fields) . " WHERE id = :id";
    
        // Preparamos y ejecutamos la consulta.
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    
        // Retornamos el número de filas afectadas.
        return $stmt->rowCount();
    }
    

    // delete user
    public static function deleteUser($userId) {
        global $pdo;
        
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $userId]);
        
        return $stmt->rowCount();
    }
}
