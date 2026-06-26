<?php
    require_once __DIR__ . '/../../Model/DAO/RelatorioDAO.php';
    /** @var RelatorioDAO[] $listaEstoque */
    /** @var array $listaEstoque */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro de Estoque</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
    
    <style>
        /* Estilos específicos para este relatório */
        .cards-container {
            display: flex;
            gap: 30px;
            margin-top: 30px;
            justify-content: center;
        }

        .card-resumo {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 30px;
            width: 45%;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .card-resumo h2 {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .valor-destaque {
            font-size: 2.5rem;
            font-weight: bold;
            color: #28a745; /* Verde dinheiro */
        }
        
        .qtd-destaque {
            font-size: 2.5rem;
            font-weight: bold;
            color: #007bff; /* Azul padrão */
        }

        @media print {
            nav, .no-print { display: none; }
            .container { width: 100%; border: none; box-shadow: none; margin: 0; }
            .card-resumo { border: 1px solid #000; box-shadow: none; }
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
            <a href="RelatorioController.php?acao=menu">⬅ Voltar ao Menu</a>
        </div>
        <div class="nav-logout">
            <a href="AutenticaController.php?acao=logout" class="btn-logout">🚪 Sair</a>
        </div>
    </nav>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 20px;">
            <div>
                <h1>Relatório de Valor de Estoque</h1>
                <p style="color: #666;">Posição atual do inventário em: <strong><?php echo date('d/m/Y H:i'); ?></strong></p>
            </div>
            
            <button onclick="window.print()" class="btn no-print" style="background-color: #17a2b8;">
                🖨️ Imprimir
            </button>
        </div>

        <div class="cards-container">
            <div class="card-resumo">
                <h2>Valor Total em Produtos</h2>
                <div class="valor-destaque">
                    <?php 
                        // Se vier nulo (estoque vazio), mostra 0
                        $total = $listaEstoque['Valor_Total_Estoque'] ?? 0;
                        echo "R$ " . number_format($total, 2, ',', '.'); 
                    ?>
                </div>
                <p style="margin-top: 10px; color: #888;">Custo acumulado do estoque atual</p>
            </div>

            <div class="card-resumo">
                <h2>Total de Itens Cadastrados</h2>
                <div class="qtd-destaque">
                    <?php echo $listaEstoque['Total_Itens_Cadastrados'] ?? 0; ?>
                </div>
                <p style="margin-top: 10px; color: #888;">Produtos únicos na base de dados</p>
            </div>
        </div>

        <div style="margin-top: 50px; text-align: center; color: #999; font-size: 0.9rem;">
            <p>Relatório gerado pelo Sistema de Estoque - Uso exclusivo de <?php echo $_SESSION['nome']; ?></p>
        </div>
        
        <div class="no-print" style="margin-top: 30px;">
            <a href="RelatorioController.php?acao=menu" class="btn" style="background-color: #6c757d;">Voltar</a>
        </div>
    </div>
</body>
</html>