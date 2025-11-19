<?php
$host = 'localhost';
$port = '3307';  
$db   = 'portal_ambiental';
$user = 'root';
$pass = '';  

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // CRIAR ADMIN SE NÃO EXISTIR (IGUAL DO SEU AMIGO)
    try {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = 'admin@portal.com'");
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            // Criar admin com senha SEM HASH
            $stmt = $pdo->prepare(
                "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'admin')"
            );
            $stmt->execute(['Administrador', 'admin@portal.com', 'admin123']);
            echo "<!-- Admin criado automaticamente -->";
        }
    } catch (PDOException $e) {
        // Ignora erro se já existir
    }
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>