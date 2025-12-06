<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios</title>
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
        <h1>Central de Relatórios</h1>
        
        <div class="relatorios-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            
            <div class="card" style="border: 1px solid #ccc; padding: 20px; border-radius: 8px;">
                <h3>💰 Valor em Estoque</h3>
                <br>
                <p>Veja o valor total acumulado e a quantidade de itens.</p>
                <br>
                <a href="RelatorioController.php?acao=gerarTotalEstoque" class="btn">Visualizar</a>
            </div>

            <div class="card" style="border: 1px solid #ccc; padding: 20px; border-radius: 8px;">
                <h3>📅 Histórico de Movimentações</h3>
                <form action="RelatorioController.php" method="POST">
                    <input type="hidden" name="acao" value="gerarHistorico">
                    
                    <label>De:</label>
                    <input type="date" name="data_inicio" required>
                    
                    <label>Até:</label>
                    <input type="date" name="data_fim" required>
                    
                    <button type="submit" class="btn">Gerar Extrato</button>
                </form>
            </div>

            <div class="card" style="border: 1px solid #ccc; padding: 20px; border-radius: 8px;">
                <h3>🏆 Produtos Mais Movimentados</h3>
                <form action="RelatorioController.php" method="POST">
                    <input type="hidden" name="acao" value="gerarMaisMovimentados">
                    
                    <label>Tipo de Movimento:</label>
                    <select name="tipo" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
                        <option value="saida">Saída (Vendas)</option>
                        <option value="entrada">Entrada (Compras)</option>
                    </select>
                    
                    <button type="submit" class="btn">Visualizar Ranking</button>
                </form>
            </div>

        </div>
    </div>
</body>
</html>