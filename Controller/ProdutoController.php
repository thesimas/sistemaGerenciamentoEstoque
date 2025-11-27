<?php
    require_once __DIR__ . '/../Model/Produto.php';
    require_once __DIR__ . '/../Model/Movimentacao.php';
    require_once __DIR__ . '/../Model/Categoria.php';
    require_once __DIR__ . '/../Model/Fornecedor.php';

    class ProdutoController {
        
        public function listarProdutos(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }
            $produto = new Produto(null, null, null, null, null, null, null, null, null);
            $listaDeprodutos = $produto->listarPorUsuario($_SESSION['id']);

            require_once __DIR__ . '/../View/Produto/ListarProduto.php';
        }

        public function prepararCadastro(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $fornecedor = new Fornecedor(null, null, null, null, null);
            $listaDeFornecedores = $fornecedor->listarFornecedores($_SESSION['id']);

            $categoria = new Categoria(null, null, null);
            $listaDeCategorias = $categoria->listarCategorias($_SESSION['id']);

            require_once __DIR__ . '/../View/Produto/CadastrarProduto.php';
        }

        public function salvarProduto(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $sku = $_POST['sku'];
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $quantidade = $_POST['quantidade'];
            $estoqueMinimo = $_POST['estoque_minimo'];
            $id_usuario = $_SESSION['id'];
            $id_fornecedor = $_POST['id_fornecedor'];
            $id_categoria = $_POST['id_categoria'];

            if(empty($sku) || empty($nome) || empty($descricao) || empty($preco) || empty($quantidade) || empty($estoqueMinimo) || empty($id_fornecedor) || empty($id_categoria)){
                echo "<p>Todos os campos são obrigatórios.</p>";
                header("Refresh: 3; URL=../Controller/ProdutoController.php?acao=prepararCadastro");
                exit();
            }

            if(!is_numeric($preco) || !is_numeric($quantidade) || !is_numeric($estoqueMinimo)){
                echo "<p>Os campos preço, quantidade e estoque mínimo devem ser numéricos.</p>";
                header("Refresh: 3; URL=../Controller/ProdutoController.php?acao=prepararCadastro");
                exit();
            }

            if($preco < 0 || $quantidade < 0 || $estoqueMinimo < 0){
                echo "<p>Os campos preço, quantidade e estoque mínimo não podem ser negativos.</p>";
                header("Refresh: 3; URL=../Controller/ProdutoController.php?acao=prepararCadastro");
                exit();
            }

            $produto = new Produto($sku, $nome, $descricao, $preco, $quantidade, $estoqueMinimo, $id_usuario, $id_fornecedor, $id_categoria);
            
            $idNovoProduto = $produto->salvar();

            if($quantidade > 0){
                $movimentacao = new Movimentacao('Entrada', $quantidade, date('Y-m-d H:i:s'), "Inserção de Produtos", $idNovoProduto, $_SESSION['id']);
                $movimentacao->registrar();
            }

            header("Location: ../Controller/ProdutoController.php?acao=listarProdutos");
            exit();
        }

        public function excluirProduto(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            if(isset($_GET['id_produto'])){
                $id = $_GET['id_produto'];

                $produto = new Produto(null, null, null, null, null, null, null, null, null); 
                $produto->excluirProduto($id, $_SESSION['id']);
            }

            header("Location: ../Controller/ProdutoController.php?acao=listarProdutos");
            exit();
        }

        public function movimentar(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }
            //Recebendo os dados para criar um objeto do tipo Movimentacao.
            $id_produto = $_POST['id_produto'];
            $tipo = $_POST['tipo']; // 'entrada' ou 'saida' <enum no banco de dados>.
            $qtdMovimentada = (int)$_POST['quantidade'];
            $motivo = $_POST['motivo'];
            $id_usuario = $_SESSION['id'];
            $data = date('Y-m-d H:i:s');

            if($qtdMovimentada <= 0){
                echo "<p>O campo quantidade não pode ser zero ou negativo.</p>";
                header("Refresh: 3; URL=../Controller/ProdutoController.php?acao=movimentar");
                exit();
            }

            $produtoNoBanco = new Produto(null, null, null, null, null, null, null, null, null);
            $informacaoProdu = $produtoNoBanco->buscarPorId($id_produto, $id_usuario);

            if(!$informacaoProdu) {
                echo "<p>Produto não encontrado!</p>";
                header("Refresh: 3; URL=../Controller/ProdutoController.php?acao=movimentar");
                exit();
            }

            $saldoAtual = (int)$informacaoProdu['quantidade'];

            if(strtolower($tipo) === "entrada"){
                $novoSaldo = $saldoAtual + $qtdMovimentada;
            }else{
                if($saldoAtual >= $qtdMovimentada){
                    $novoSaldo = $saldoAtual - $qtdMovimentada;
                }else{
                    echo "<p>Estoque insuficiente para realizar a saída.</p>";
                    header("Refresh: 3; URL=../Controller/ProdutoController.php?acao=movimentar");
                    exit();
                }
            }

            $produtoNoBanco -> atualizarQuantidade($id_produto, $novoSaldo, $id_usuario);

            $registrarMovimentacao = new Movimentacao($tipo, $qtdMovimentada, $data, $motivo, $id_produto, $id_usuario);

            $registrarMovimentacao->registrar();

            header("Location: ../Controller/ProdutoController.php?acao=listarProdutos");
            exit();
        }

        public function prepararEdicaoProduto(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $id_produto = $_GET['id_produto'];
            $id_usuario = $_SESSION['id'];

            $produto = new Produto(null, null, null, null, null, null, null, null, null);
            $dadosProduto = $produto->buscarPorId($id_produto, $id_usuario);

            $categoria = new Categoria(null, null, null);
            $listaCategorias = $categoria->listarCategorias($id_usuario);

            $fornecedor = new Fornecedor(null, null, null, null, null);
            $listaFornecedores = $fornecedor->listarFornecedores($id_usuario);

            require_once __DIR__ . '/../View/Produto/EditarProduto.php';
            
        }

        public function atualizarProduto(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }
            
            $produtoAtualizado = new Produto(null, null, null, null, null, null, null, null, null);
            
            $produtoAtualizado->atualizar(
                $_POST['id'], 
                $_POST['sku'], 
                $_POST['nome'], 
                $_POST['descricao'], 
                $_POST['preco'], 
                $_POST['estoque_minimo'], 
                $_POST['id_categoria'], 
                $_POST['id_fornecedor'], 
                $_SESSION['id']
            );

            header("Location: ../Controller/ProdutoController.php?acao=listarProdutos");
            exit();
        }
    }

    if(isset($_REQUEST['acao'])){
        $controller = new ProdutoController();
        switch($_REQUEST['acao']){
            case 'listarProdutos':
                $controller->listarProdutos();
                break;
            case 'prepararCadastro':
                $controller->prepararCadastro();
                break;
            case 'salvarProduto':
                $controller->salvarProduto();
                break;
            case 'movimentar':
                $controller->movimentar();
                break;
            case 'excluirProduto':
                $controller->excluirProduto();
                break;
            case 'prepararEdicaoProduto':
                $controller->prepararEdicaoProduto();
                break;
            case 'atualizarProduto':
                $controller->atualizarProduto();
                break;
            default:
                $controller->listarProdutos();
                break;
        }
    }else{
        $controller = new ProdutoController();
        $controller->listarProdutos();
    }
?>