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
    </div>

</body>
</html>