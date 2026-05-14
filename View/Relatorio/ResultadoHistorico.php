<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Histórico</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
    
    <style>
        @media print {
            nav, .no-print { display: none; }
            .container { width: 100%; box-shadow: none; margin: 0; }
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
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Extrato de Movimentações</h1>
            <button onclick="window.print()" class="btn no-print" style="background-color: #007bff;">🖨️ Imprimir / Salvar PDF</button>
        </div>

        <p><strong>Período:</strong> <?php echo date('d/m/Y', strtotime($inicio)); ?> até <?php echo date('d/m/Y', strtotime($fim)); ?></p>

        <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead style="background-color: #eee;">
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Tipo</th>
                    <th>Qtd</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($listaMovimentacoes) && count($listaMovimentacoes) > 0): ?>
                    
                    <?php foreach($listaMovimentacoes as $movimentacao): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($movimentacao['data_movimentacao'])); ?></td>
                        <td><?php echo $movimentacao['nome']; ?></td> <td><?php echo strtoupper($movimentacao['tipo']); ?></td>
                        <td><?php echo $movimentacao['quantidade']; ?></td>
                        <td><?php echo $movimentacao['motivo']; ?></td>
                    </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr><td colspan="5">Nenhuma movimentação neste período.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <a href="RelatorioController.php?acao=prepararMenu" class="btn no-print" style="background-color: gray;">Voltar</a>
    </div>
</body>
</html>