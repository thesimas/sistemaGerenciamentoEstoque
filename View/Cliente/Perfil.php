<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
</head>
<body>
    <nav>
        <div class="nav-brand">
            <span class="company-name">🏢 <?php echo $_SESSION['nome']; ?></span>
        </div>
        <div class="nav-links">
            <a href="ClienteController.php?acao=dashboard">🏠 Menu</a>
            <a href="ProdutoController.php?acao=listarProdutos">📦 Produtos</a>
            <a href="FornecedorController.php?acao=listarFornecedores">🚛 Fornecedores</a>
            <a href="CategoriaController.php?acao=listarCategorias">🏷️ Categorias</a>
        </div>
        <div class="nav-logout">
            <a href="AutenticaController.php?acao=logout" class="btn-logout">🚪 Sair</a>
        </div>
    </nav>

    <div class="container">
        <h1>Meu Perfil</h1>
        <p><strong>Nome da Empresa:</strong> <?php echo $dadosCliente['nome']; ?></p>
        <p><strong>Email:</strong> <?php echo $dadosCliente['email']; ?></p>
        <p><strong>Foto de Perfil: <br><br>
        <?php if($dadosCliente['foto_perfil']): ?>
            <img src="../View/Assets/Imagens/Uploads/<?php echo $dadosCliente['foto_perfil']; ?>" alt="Foto de Perfil" style="max-width: 200px; max-height: 200px;">
        <?php else: ?>
            <p>Foto de Perfil não disponível.</p>
        <?php endif; ?>
        <br></strong></p>
        <a href="ClienteController.php?acao=dashboard" class="btn" style="margin-top: 20px;">Voltar ao Menu</a>
        <a href="ClienteController.php?acao=prepararEdicaoPerfil" class="btn" style="margin-top: 20px; background-color: #6c757d;">Editar Perfil</a>
    </div>

</body>
</html>