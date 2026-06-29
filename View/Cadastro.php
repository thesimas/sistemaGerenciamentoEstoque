<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Estoque</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
</head>

<body class="register-page">

<div class="register-card">
    <h1 class="titulo-register">📦 Cadastro</h1>

    <?php
        session_start();

        if(isset($_SESSION['erro_cadastro'])){
            echo "<div style='background-color:#f8d7da;color:#721c24;padding:12px;border-radius:4px;margin-bottom:15px;border:1px solid #f5c6cb;'>";
            echo "<strong>Erro:</strong> "
                . htmlspecialchars($_SESSION['erro_cadastro']);

            echo "</div>";
            unset($_SESSION['erro_cadastro']);
        }
    ?>

    <form action="../Controller/AutenticaController.php"
          method="POST"
          enctype="multipart/form-data">
        <div style="text-align:left;">

            <label>Nome do Titular</label>
            <input
                type="text"
                name="nome"
                required
                placeholder="Nome completo">
                
            <label>Nome da Empresa</label>
            <input
                type="text"
                name="nome_empresa"
                required
                placeholder="Minha Empresa">

            <label>Email</label>
            <input
                type="email"
                name="email"
                required
                placeholder="email@empresa.com">


            <label>Foto de Perfil</label>
            <input
                type="file"
                name="foto_perfil"
                accept=".jpg,.jpeg,.png">

            <label>Senha</label>

            <div class="password-container">
                <input
                    type="password"
                    name="senha"
                    id="senha"
                    required
                    placeholder="********">

                <span class="toggle-password"
                    onclick="togglePassword('senha', this)">
                    👁
                </span>
            </div>

            <label>Repita a senha</label>
            <div class="password-container">
                <input
                    type="password"
                    id="confirmarSenha"
                    required
                    placeholder="********">

                <span class="toggle-password"
                    onclick="togglePassword('confirmarSenha', this)">
                    👁
                </span>
            </div>

            <small id="mensagemSenha"></small>

        </div>
        <input
            type="hidden"
            name="acao"
            value="cadastrar">

        <button class="btn" type="submit">
            Cadastrar
        </button>
    </form>
    <br>
    <p>Já possui cadastro? <a class="gerencia" href="../View/Login.php">Faça Login</a></p>
</div>

<script src="Assets/Scripts/scripts.js"></script>
</body>
</html>