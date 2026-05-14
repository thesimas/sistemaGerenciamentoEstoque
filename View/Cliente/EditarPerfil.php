<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
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
        <h1>Editar Perfil</h1>
        <form action="ClienteController.php?acao=atualizarPerfil" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome da Empresa:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $dadosCliente['nome']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $dadosCliente['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="foto_perfil">Foto de Perfil: </label>
                <input type="file" id="foto_perfil" name="foto_perfil" value="<?php echo $dadosCliente['foto_perfil']; ?>">
            </div>
            <button type="submit" class="btn">Atualizar Perfil</button>
            <button type="button" class="btn" style="background-color: #6c757d;" onclick="window.location.href='ClienteController.php?acao=perfil'">Voltar ao Perfil</button>
        </form>
    </div>


    
</body>
</html>