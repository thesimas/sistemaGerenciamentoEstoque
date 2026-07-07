<?php
    require_once __DIR__ . '/../../Model/Produto.php';
    /** @var Produto[] $listaDeprodutos */
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque - Produtos</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h1>Gerenciamento de Estoque</h1>
        <?php if(isset($_SESSION['erro'])): ?>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Aviso do Sistema",
                    text: "<?php echo addslashes($_SESSION['erro']); ?>"
                });
            </script>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>
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
                    <?php foreach($listaDeprodutos as $produto): ?>
                        <?php 
                            // Lógica visual: Verifica se estoque está baixo
                            $alerta = $produto->getQuantidade() < $produto->getEstoqueMinimo();
                            $classeLinha = $alerta ? 'style="background-color: #fff3cd;"' : ''; 
                            $textoStatus = $alerta ? '<span style="color:red; font-weight:bold;">BAIXO!</span>' : '<span style="color:green;">OK</span>';
                        ?>
                        <tr <?php echo $classeLinha; ?>>
                            <td><?php echo $produto->getSku(); ?></td>
                            <td>
                                <strong><?php echo $produto->getNome(); ?></strong><br>
                                <small><?php echo $produto->getDescricao(); ?></small>
                            </td>
                            <td>R$ <?php echo number_format($produto->getPreco(), 2, ',', '.'); ?></td>
                            
                            <td style="font-size: 1.2rem; font-weight: bold;">
                                <?php echo $produto->getQuantidade(); ?>
                            </td>
                            
                            <td><?php echo $textoStatus; ?></td>

                            <td>
                                <form action="ProdutoController.php" method="POST" style="display:flex; gap:5px; margin:0; padding:0; background:none; box-shadow:none;">
                                    <input type="hidden" name="acao" value="movimentar">
                                    <input type="hidden" name="id_produto" value="<?php echo $produto->getId(); ?>">
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
                                <a href="ProdutoController.php?acao=prepararEdicaoProduto&id_produto=<?php echo $produto->getId(); ?>" 
                                 class="btn-acao btn-editar">Editar</a>
                                <a href="ProdutoController.php?acao=excluirProduto&id_produto=<?php echo $produto->getId(); ?>" 
                                   onclick="confirmarExclusao(event, this.href)" 
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../View/Scripts/scripts.js"></script>
</body>
</html>