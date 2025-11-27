<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Fornecedores</title>
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
        <h1>Fornecedores</h1>

        <div style="margin-bottom: 20px;">
            <a href="FornecedorController.php?acao=prepararCadastro" class="btn">
                + Novo Fornecedor
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>CNPJ</th>
                    <th>Contato</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($listaFornecedores) && count($listaFornecedores) > 0): ?>
                    <?php foreach($listaFornecedores as $forn): ?>
                        <tr>
                            <td><?php echo $forn['nome_empresa']; ?></td>
                            <td><?php echo $forn['cnpj']; ?></td>
                            <td><?php echo $forn['email']; ?></td>
                            <td>
                                <a href="FornecedorController.php?acao=prepararEdicaoFornecedor&id_fornecedor=<?php echo $forn['id']; ?>" 
                                   class="btn-acao btn-editar">Editar</a>
                                
                                <a href="FornecedorController.php?acao=excluirFornecedor&id_fornecedor=<?php echo $forn['id']; ?>" 
                                   onclick="return confirm('Tem certeza?')" 
                                   class="btn-acao btn-excluir">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center;">Nenhum fornecedor cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>