<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Fornecedor</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
</head>
<body>
    <nav>
        <div class="nav-brand">
            <div style="display: flex; align-items: center; gap: 10px;">
                <?php if($_SESSION['foto_perfil']): ?>
                    <img src="../View/Assets/Imagens/Uploads/<?php echo $_SESSION['foto_perfil']; ?>" alt="Foto de Perfil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                <?php else: ?>
                    <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #ddd; display: flex; align-items: center; justify-content: center;">👤</div>
                <?php endif; ?>
                <span class="company-name"><?php echo $_SESSION['nome']; ?></span>
            </div>
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
        <h1>Cadastrar Fornecedor</h1>
        <?php if(isset($_SESSION['erro'])): ?>
            <script>
                alert("Aviso do Sistema:\n\n<?php echo addslashes($_SESSION['erro']); ?>");
            </script>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>            
        <form action="FornecedorController.php" method="POST">
            <fieldset>
                <label>Nome da Empresa:</label>
                <input type="text" name="nome_empresa" required>

                <label>CNPJ:</label>
                <input type="text" name="cnpj" placeholder="00.000.000/0000-00">

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Telefone:</label>
                <input type="text" name="telefone">

                <input type="hidden" name="acao" value="salvarFornecedor">

                <div style="margin-top: 15px;">
                    <button type="submit" class="btn">Salvar</button>
                    <a href="FornecedorController.php?acao=listarFornecedores" class="btn-link" style="background-color: #6c757d;">Cancelar</a>
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>