<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque - Produtos</title>
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
        <h1>Gerenciamento de Estoque</h1>
        
        <a href="ProdutoController.php?acao=prepararCadastro" class="btn">
            + Novo Produto
        </a>

        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Saldo</th>
                    <th>Status</th>
                    <th>Movimentação Rápida</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($listaDeprodutos) > 0): ?>
                    <?php foreach($listaDeprodutos as $prod): ?>
                        <?php 
                            // Lógica visual: Verifica se estoque está baixo
                            $alerta = $prod['quantidade'] <= $prod['estoque_minimo'];
                            $classeLinha = $alerta ? 'style="background-color: #fff3cd;"' : ''; 
                            $textoStatus = $alerta ? '<span style="color:red; font-weight:bold;">BAIXO!</span>' : '<span style="color:green;">OK</span>';
                        ?>
                        <tr <?php echo $classeLinha; ?>>
                            <td><?php echo $prod['sku']; ?></td>
                            <td>
                                <strong><?php echo $prod['nome']; ?></strong><br>
                                <small><?php echo $prod['descricao']; ?></small>
                            </td>
                            <td>R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></td>
                            
                            <td style="font-size: 1.2rem; font-weight: bold;">
                                <?php echo $prod['quantidade']; ?>
                            </td>
                            
                            <td><?php echo $textoStatus; ?></td>

                            <td>
                                <form action="ProdutoController.php" method="POST" style="display:flex; gap:5px; margin:0; padding:0; background:none; box-shadow:none;">
                                    <input type="hidden" name="acao" value="movimentar">
                                    <input type="hidden" name="id_produto" value="<?php echo $prod['id']; ?>">
                                    <input type="hidden" name="motivo" value="Ajuste Rápido">
                                    
                                    <select name="tipo" style="width: 80px; padding: 5px;">
                                        <option value="entrada">Entrada</option>
                                        <option value="saida">Saída</option>
                                    </select>
                                    
                                    <input type="number" name="quantidade" placeholder="Qtd" style="width: 60px; padding: 5px;" min="1" required>
                                    
                                    <button type="submit" style="padding: 5px 10px;" class="btn">OK</button>
                                </form>
                            </td>

                            <td>
                                <a href="ProdutoController.php?acao=prepararEdicaoProduto&id_produto=<?php echo $prod['id']; ?>" 
                                 class="btn-acao btn-editar">Editar</a>
                                <a href="ProdutoController.php?acao=excluirProduto&id_produto=<?php echo $prod['id']; ?>" 
                                   onclick="return confirm('Tem certeza que deseja excluir?')" 
                                 class="btn-acao btn-excluir">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Nenhum produto cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>