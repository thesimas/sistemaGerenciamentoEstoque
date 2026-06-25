<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
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
        <h1>Editar Produto</h1>

        <form action="ProdutoController.php" method="POST">
            <fieldset>
                <legend>Dados Principais</legend>
                
                <label>SKU:</label>
                <input type="text" name="sku" value="<?php echo $dadosProduto->getSku(); ?>" required>

                <label>Nome:</label>
                <input type="text" name="nome" value="<?php echo $dadosProduto->getNome(); ?>" required>

                <label>Descrição:</label>
                <input type="text" name="descricao" value="<?php echo $dadosProduto->getDescricao(); ?>" required>

                <label>Preço (R$):</label>
                <input type="number" name="preco" step="0.01" value="<?php echo $dadosProduto->getPreco(); ?>" required>
                
                <label>Estoque Mínimo:</label>
                <input type="number" name="estoque_minimo" value="<?php echo $dadosProduto->getEstoqueMinimo(); ?>" required>
                
                </fieldset>

            <fieldset>
                <legend>Classificação</legend>

                <label>Categoria:</label>
                <select name="id_categoria" required>
                    <?php foreach($listaCategorias as $categoria): ?>
                        <?php $selected = ($categoria->getId() == $dadosProduto->getCategoria()?->getId()) ? 'selected' : ''; ?>
                        <option value="<?php echo $categoria->getId(); ?>" <?php echo $selected; ?>>
                            <?php echo $categoria->getNome(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Fornecedor:</label>
                <select name="id_fornecedor" required>
                    <?php foreach($listaFornecedores as $fornecedor): ?>
                        <?php $selected = ($fornecedor->getId() == $dadosProduto->getFornecedor()?->getId()) ? 'selected' : ''; ?>
                        <option value="<?php echo $fornecedor->getId(); ?>" <?php echo $selected; ?>>
                            <?php echo $fornecedor->getNomeEmpresa(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <input type="hidden" name="id" value="<?php echo $dadosProduto->getId(); ?>">
            <input type="hidden" name="acao" value="atualizarProduto">
            
            <div style="margin-top: 15px;">
                <button type="submit" class="btn">Salvar Alterações</button>
                <a href="ProdutoController.php?acao=listarProdutos" class="btn" style="background-color: #6c757d;">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>