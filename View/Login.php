<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estoque</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
</head>
<body class="login-page">
    <div class="login-card">
        <h1 class="titulo-login">📦 Acesso ao Sistema</h1>

        <?php
            session_start();
            if(isset($_SESSION['erro_login'])){
                echo "<div style='background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb;'>";
                echo "<strong>Erro:</strong> " . htmlspecialchars($_SESSION['erro_login']);
                echo "</div>";
                unset($_SESSION['erro_login']);
            }
        ?>

        <form action="../Controller/AutenticaController.php" method="POST">
            <div style="text-align: left;">
                <label>Email: </label>
                <input type="email" name="email" id="email" required placeholder="admin@sistema.com">
                
                <label>Senha: </label>
                <input type="password" name="senha" id="senha" required placeholder="••••••••">
            </div>

            <input type="hidden" name="acao" value="logar">
            
            <button type="submit" class="btn">Entrar</button>
        </form>
        <br>
        <p>Não possui cadastro? <a class="gerencia" href="../View/Cadastro.php">Cadastre-se</a></p>
    </div>
</body>
</html>