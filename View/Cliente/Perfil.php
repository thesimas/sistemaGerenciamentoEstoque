<?php
    require_once __DIR__ . '/../../Model/Usuario.php';
    /** @var Usuario $dadosCliente */
    /** @var Cliente $dadosCliente */
?>
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
        <h1>Meu Perfil</h1>
        <p><strong>Responsável Técnico:</strong> <?php echo $dadosCliente->getResponsavelTecnico(); ?></p>
        <p><strong>Nome da Empresa:</strong> <?php echo $dadosCliente->getNomeEmpresa(); ?></p>
        <p><strong>Email:</strong> <?php echo $dadosCliente->getEmail(); ?></p>
        <p><strong>Foto de Perfil: <br><br>
        <?php if($dadosCliente->getFotoPerfil()): ?>
            <img src="../View/Assets/Imagens/Uploads/<?php echo $dadosCliente->getFotoPerfil(); ?>" alt="Foto de Perfil" style="max-width: 200px; max-height: 200px;">
        <?php else: ?>
            <p>Foto de Perfil não disponível.</p>
        <?php endif; ?>
        <br></strong></p>
        <a href="ClienteController.php?acao=dashboard" class="btn" style="margin-top: 20px;">Voltar ao Menu</a>
        <a href="ClienteController.php?acao=prepararEdicaoPerfil" class="btn" style="margin-top: 20px; background-color: #6c757d;">Editar Perfil</a>
        <a href="ClienteController.php?acao=excluirPerfil" class="btn" style="margin-top: 20px; background-color: #dc3545;" onclick="confirmarExclusaoPerfil(event, this.href)">Excluir Minha Conta</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../View/Scripts/scripts.js"></script>
</body>
</html>