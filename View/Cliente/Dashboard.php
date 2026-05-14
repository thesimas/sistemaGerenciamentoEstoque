<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
    <style>
        /* --- CSS Específico para o Menu em Grid --- */
        .menu-grid {
            display: grid;
            /* ALTERADO: Força exatamente 3 colunas de tamanhos iguais */
            grid-template-columns: repeat(3, 1fr); 
            gap: 30px; /* Aumentei um pouco o espaço entre eles */
            margin-top: 40px;
            max-width: 1000px; /* Limita a largura para não ficar muito esticado */
            margin-left: auto;
            margin-right: auto;
        }

        .menu-card {
            background-color: white;
            padding: 30px 20px; /* Mais espaçamento interno */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 150px; /* Altura fixa para ficarem iguais */
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,123,255,0.15); /* Sombra azulada ao passar o mouse */
            border-color: #007bff;
        }

        .menu-icon {
            font-size: 3rem; /* Ícone maior */
            margin-bottom: 15px;
        }

        .menu-title {
            font-size: 1.3rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Responsividade: Em celulares, volta para 1 coluna para não quebrar */
        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
            <a href="ClienteController.php?acao=dashboard">🏠 Início</a>
        </div>
        <div class="nav-logout">
            <a href="AutenticaController.php?acao=logout" class="btn-logout">🚪 Sair</a>
        </div>
    </nav>

    <div class="container">
        <h1 style="text-align: center; margin-bottom: 10px;">Bem-vindo ao Sistema de Estoque</h1>
        <p style="text-align: center; color: #666;">Selecione uma opção abaixo:</p>

        <div class="menu-grid">
            <a href="../Controller/ProdutoController.php?acao=listarProdutos" class="menu-card">
                <div class="menu-icon">📦</div>
                <div class="menu-title">Produtos</div>
            </a>

            <a href="../Controller/CategoriaController.php?acao=listarCategorias" class="menu-card">
                <div class="menu-icon">🏷️</div>
                <div class="menu-title">Categorias</div>
            </a>

            <a href="../Controller/FornecedorController.php?acao=listarFornecedores" class="menu-card">
                <div class="menu-icon">🚛</div>
                <div class="menu-title">Fornecedores</div>
            </a>

            <a href="../Controller/RelatorioController.php?acao=prepararMenu" class="menu-card">
                <div class="menu-icon">📊</div>
                <div class="menu-title">Relatórios</div>
            </a>

            <a href="../Controller/ClienteController.php?acao=perfil" class="menu-card">
                <div class="menu-icon">👤</div>
                <div class="menu-title">Meu Perfil</div>
            </a>

            <a href="#" onclick="alert('Em breve!')" class="menu-card" style="opacity: 0.7;">
                <div class="menu-icon">⚙️</div>
                <div class="menu-title">Configurações</div>
            </a>
        </div>
    </div>
</body>
</html>