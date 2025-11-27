<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Categoria</title>
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
        <h1>Cadastrar Nova Categoria</h1>

        <form action="CategoriaController.php" method="POST">
            <fieldset>
                <label>Nome da Categoria:</label>
                <input type="text" name="nome" required placeholder="Ex: Eletrônicos">
                
                <input type="hidden" name="acao" value="salvarCategoria">
                
                <div style="margin-top: 15px;">
                    <button type="submit" class="btn">Salvar</button>
                    <a href="CategoriaController.php?acao=listarCategorias" class="btn-link" style="background-color: #6c757d;">Cancelar</a>
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>