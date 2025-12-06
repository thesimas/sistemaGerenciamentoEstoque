<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ranking de Produtos</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .chart-container {
            position: relative; 
            height: 40vh; 
            width: 80%; 
            margin: 0 auto 40px auto;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.8rem;
            color: white;
        }
        
        .badge-entrada { background-color: #28a745; }
        .badge-saida { background-color: #dc3545; }

        @media print {
            nav, .no-print { display: none; }
            .container { box-shadow: none; margin: 0; width: 100%; }
            .chart-container { height: 300px; width: 100%; } /* Ajuste para caber na folha */
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-brand">
            <span class="company-name">🏢 <?php echo $_SESSION['nome']; ?></span>
        </div>
        <div class="nav-links">
            <a href="RelatorioController.php?acao=menu">⬅ Voltar ao Menu</a>
        </div>
        <div class="nav-logout">
            <a href="AutenticaController.php?acao=logout" class="btn-logout">🚪 Sair</a>
        </div>
    </nav>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1>Ranking: Produtos Mais Movimentados</h1>
                <p style="color: #666;">
                    Tipo de Operação: 
                    <?php if($tipo == 'entrada'): ?>
                        <span class="badge badge-entrada">ENTRADAS (Compras)</span>
                    <?php else: ?>
                        <span class="badge badge-saida">SAÍDAS (Vendas)</span>
                    <?php endif; ?>
                </p>
            </div>
            
            <button onclick="window.print()" class="btn no-print" style="background-color: #17a2b8;">
                🖨️ Imprimir
            </button>
        </div>

        <div class="chart-container">
            <canvas id="meuGrafico"></canvas>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>SKU</th>
                    <th>Produto</th>
                    <th>Preço Unit.</th>
                    <th>Total (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Arrays para alimentar o gráfico via JS
                    $labelsGrafico = [];
                    $dadosGrafico = [];
                    $contador = 1;
                ?>

                <?php if(isset($listaRanking) && count($listaRanking) > 0): ?>
                    <?php foreach($listaRanking as $item): ?>
                        <?php 
                            // Prepara dados para o gráfico (Top 5 apenas para não poluir)
                            if($contador <= 5){
                                $labelsGrafico[] = $item['nome'];
                                $dadosGrafico[] = $item['Valor_Total']; 
                            }
                        ?>
                        <tr>
                            <td><strong><?php echo $contador++; ?>º</strong></td>
                            <td><?php echo $item['sku']; ?></td>
                            <td><?php echo $item['nome']; ?></td>
                            <td>R$ <?php echo number_format($item['PREÇO_UNITARIO'], 2, ',', '.'); ?></td>
                            <td style="font-weight: bold; color: #333;">
                                R$ <?php echo number_format($item['Valor_Total'], 2, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center;">Nenhum registro encontrado para este tipo de movimentação.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="no-print" style="margin-top: 30px;">
            <a href="RelatorioController.php?acao=menu" class="btn" style="background-color: #6c757d;">Voltar</a>
        </div>
    </div>

    <script>
        // Pegando os dados do PHP e passando para o JS
        const labels = <?php echo json_encode($labelsGrafico); ?>;
        const dataValues = <?php echo json_encode($dadosGrafico); ?>;
        const tipoRelatorio = "<?php echo strtoupper($tipo); ?>";

        // Configuração do Chart.js
        const ctx = document.getElementById('meuGrafico').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Tipo de gráfico (barra)
            data: {
                labels: labels,
                datasets: [{
                    label: 'Valor Total Movimentado (Top 5)',
                    data: dataValues,
                    backgroundColor: tipoRelatorio === 'ENTRADA' ? 'rgba(40, 167, 69, 0.6)' : 'rgba(220, 53, 69, 0.6)', // Verde se entrada, Vermelho se saída
                    borderColor: tipoRelatorio === 'ENTRADA' ? '#28a745' : '#dc3545',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>