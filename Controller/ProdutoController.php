<?php
    require_once __DIR__ . '/../Model/DAO/ProdutoDAO.php';
    require_once __DIR__ . '/../Model/Categoria.php';
    require_once __DIR__ . '/../Model/Fornecedor.php';
    require_once __DIR__ . '/../Model/Movimentacao.php';
    require_once __DIR__ . '/../Model/DAO/CategoriaDAO.php';
    require_once __DIR__ . '/../Model/DAO/FornecedorDAO.php';

    class ProdutoController {
        
        public function listarProdutos(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }
            $produtoDao = new ProdutoDAO();
            $listaDeprodutos = $produtoDao->listarPorUsuario($_SESSION['id']);

            require_once __DIR__ . '/../View/Produto/ListarProduto.php';
        }

        public function prepararCadastro(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $fornecedorDao = new FornecedorDAO();
            $listaDeFornecedores = $fornecedorDao->listarPorUsuario($_SESSION['id']);

            $categoriaDao = new CategoriaDAO();
            $listaDeCategorias = $categoriaDao->listarPorUsuario($_SESSION['id']);

            require_once __DIR__ . '/../View/Produto/CadastrarProduto.php';
        }

        public function salvarProduto() {
            session_start();
            if(!isset($_SESSION['id'])){ header("Location: ../index.php"); exit(); }

            $sku = $_POST['sku'];
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $preco = (float)$_POST['preco'];
            $quantidade = (int)$_POST['quantidade'];
            $estoqueMinimo = (int)$_POST['estoque_minimo'];
            
            $id_categoria = $_POST['id_categoria'];
            $id_fornecedor = $_POST['id_fornecedor'];

            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($_SESSION['id']);

            $categoria = null;
            if (!empty($id_categoria)) {
                $categoria = new Categoria($id_categoria, null, $usuarioTenant);
            }

            $fornecedor = null;
            if (!empty($id_fornecedor)) {
                $fornecedor = new Fornecedor(null, null, null, null, $usuarioTenant);
                $fornecedor->setId($id_fornecedor);
            }

            $produto = new Produto($sku, $nome, $descricao, $preco, $quantidade, $estoqueMinimo, $usuarioTenant, $fornecedor, $categoria);
            
            $produtoDAO = new ProdutoDAO();
            $produtoDAO->inserir($produto);

            if($quantidade > 0){
                $movimentacao = new Movimentacao('entrada', $quantidade, date('Y-m-d H:i:s'), "Inserção de Produtos", $produto, $usuarioTenant);
                $movimentacaoDAO = new MovimentacaoDAO();
                $movimentacaoDAO->registrar($movimentacao);
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

                $produto = new ProdutoDAO();
                $produto->excluir($id, $_SESSION['id']);
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

            $produtoNoBanco = new ProdutoDAO();
            $informacaoProduto = $produtoNoBanco->buscarPorId($id_produto, $id_usuario);

            if(!$informacaoProduto) {
                echo "<p>Produto não encontrado!</p>";
                header("Refresh: 3; URL=../Controller/ProdutoController.php?acao=movimentar");
                exit();
            }

            $saldoAtual = (int)$informacaoProduto['quantidade'];

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

            $movimentacaoDAO = new MovimentacaoDAO();
            $movimentacaoDAO->registrar($registrarMovimentacao);

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

            $produto = new ProdutoDAO();
            $dadosProduto = $produto->buscarPorId($id_produto, $id_usuario);

            $categoria = new CategoriaDAO();
            $listaCategorias = $categoria->listarPorUsuario($id_usuario);

            $fornecedor = new FornecedorDAO();
            $listaFornecedores = $fornecedor->listarPorUsuario($id_usuario);

            require_once __DIR__ . '/../View/Produto/EditarProduto.php';
            
        }

        public function atualizarProduto(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }
            
            $produtoDAO = new ProdutoDAO();
            
            $produtoAtualizado = new Produto(null, null, null, null, null, null, null, null, null);
            $produtoAtualizado->setId($_POST['id']);
            $produtoAtualizado->setSku($_POST['sku']);
            $produtoAtualizado->setNome($_POST['nome']);
            $produtoAtualizado->setDescricao($_POST['descricao']);
            $produtoAtualizado->setPreco((float)$_POST['preco']);
            $produtoAtualizado->setEstoqueMinimo((int)$_POST['estoque_minimo']);

            $categoria = new Categoria($_POST['id_categoria'], null, new Cliente(null, null, null, null));

            $fornecedor = new Fornecedor(null, null, null, null, new Cliente(null, null, null, null));
            $fornecedor->setId($_POST['id_fornecedor']);

            $produtoAtualizado->setCategoria($categoria);
            $produtoAtualizado->setFornecedor($fornecedor);
            $cliente = new Cliente(null, null, null, null);
            $cliente->setId($_SESSION['id']);
            $produtoAtualizado->setUsuario($cliente);

            $produtoDAO->atualizar($produtoAtualizado);

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
    }
?>