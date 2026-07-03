<?php
    require_once __DIR__ . '/../../Model/Categoria.php';
    /** @var Categoria[] $listaDeCategorias */
    require_once __DIR__ . '/../../Model/Fornecedor.php';
    /** @var Fornecedor[] $listaDeFornecedores */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Produto</title>
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
        <h1>Cadastrar Novo Produto</h1>
        <?php if(isset($_SESSION['erro'])): ?>
            <script>
                alert("Aviso do Sistema:\n\n<?php echo addslashes($_SESSION['erro']); ?>");
            </script>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>
        <form action="ProdutoController.php" method="POST">
            <fieldset>
                <legend>Dados Principais</legend>
                
                <label>SKU (Código Único):</label>
                <input type="text" name="sku" required placeholder="Ex: PROD-001">

                <label>Nome do Produto:</label>
                <input type="text" name="nome" required placeholder="Ex: Notebook Dell">

                <label>Descrição:</label>
                <input type="text" name="descricao" required>

                <label>Preço (R$):</label>
                <input type="number" name="preco" step="0.01" required placeholder="0.00">
            </fieldset>

            <fieldset>
                <legend>Estoque Inicial</legend>
                
                <label>Quantidade Atual:</label>
                <input type="number" name="quantidade" required value="0">

                <label>Estoque Mínimo (Para Alerta):</label>
                <input type="number" name="estoque_minimo" required value="5">
            </fieldset>

            <fieldset>
                <legend>Classificação</legend>

                <label>Categoria:</label>
                <select name="id_categoria" required>
                    <option value="">Selecione...</option>
                    <?php foreach($listaDeCategorias as $categoria): ?>
                        <option value="<?php echo $categoria->getId(); ?>">
                            <?php echo $categoria->getNome(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small><a href="CategoriaController.php?acao=listarCategorias" class="gerencia">Gerenciar Categorias</a></small>
                <br><br>

                <label>Fornecedor:</label>
                <select name="id_fornecedor" required>
                    <option value="">Selecione...</option>
                    <?php foreach($listaDeFornecedores as $fornecedor): ?>
                        <option value="<?php echo $fornecedor->getId(); ?>">
                            <?php echo $fornecedor->getNomeEmpresa(); ?> (CNPJ: <?php echo $fornecedor->getCnpj(); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <small><a href="FornecedorController.php?acao=listarFornecedores" class="gerencia">Gerenciar Fornecedores</a></small>
            </fieldset>

            <input type="hidden" name="acao" value="salvarProduto">
            <br>
            <button type="submit" class="btn">Salvar Produto</button>
            <a href="ProdutoController.php?acao=listarProdutos" class="btn-link" style="background-color: #6c757d;">Cancelar</a>
        </form>
    </div>
</body>
</html>