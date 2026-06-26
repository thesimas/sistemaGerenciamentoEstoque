<?php
require_once __DIR__ . '/../../Model/Categoria.php'; // Apresenta a classe para a IDE
/** @var Categoria $categoria */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
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
        <h1>Editar Categoria</h1>

        <form action="CategoriaController.php" method="POST">
            <fieldset>
                <label>Nome da Categoria: </label>
                <input type="text" name="nome" value="<?php echo $categoria->getNome(); ?>" required>
                
                <input type="hidden" name="id_categoria" value="<?php echo $categoria->getId(); ?>">
                <input type="hidden" name="acao" value="atualizarCategoria">
                
                <div style="margin-top: 15px;">
                    <button type="submit" class="btn">Atualizar</button>
                    <a href="CategoriaController.php?acao=listarCategorias" class="btn" style="background-color: #6c757d;">Cancelar</a>
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>