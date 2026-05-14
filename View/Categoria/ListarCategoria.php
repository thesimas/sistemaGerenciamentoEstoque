<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Categorias</title>
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
        <h1>Categorias</h1>

        <div style="margin-bottom: 20px;">
            <a href="CategoriaController.php?acao=prepararCadastroCategoria" class="btn">
                + Nova Categoria
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($listaCategorias) && count($listaCategorias) > 0): ?>
                    <?php foreach($listaCategorias as $cat): ?>
                        <tr>
                            <td style="width: 10%;"><?php echo $cat['id']; ?></td>
                            <td><?php echo $cat['nome']; ?></td>
                            <td style="width: 20%;">
                                <a href="CategoriaController.php?acao=prepararEdicaoCategoria&id_categoria=<?php echo $cat['id']; ?>"
                                   class="btn-acao btn-editar">Editar</a>
                                
                                <a href="CategoriaController.php?acao=excluirCategoria&id_categoria=<?php echo $cat['id']; ?>" 
                                   onclick="return confirm('Tem certeza?')" 
                                   class="btn-acao btn-excluir">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align: center;">Nenhuma categoria.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>