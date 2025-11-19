<?php
require 'conexao.php';

$email = 'admin@portal.com';
$senha = '123456';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    echo "Usuário encontrado: " . $usuario['nome'] . "<br>";
    echo "Hash no banco: " . $usuario['senha'] . "<br>";
    echo "Senha testada: " . $senha . "<br>";
    
    if (password_verify($senha, $usuario['senha'])) {
        echo "✅ SENHA CORRETA! Login funcionaria!";
    } else {
        echo "❌ SENHA INCORRETA! Hash não bate.";
    }
} else {
    echo "❌ Usuário não encontrado!";
}
?>