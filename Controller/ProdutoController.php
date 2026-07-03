<?php
    require_once __DIR__ . '/../Model/DAO/ProdutoDAO.php';
    require_once __DIR__ . '/../Model/DAO/CategoriaDAO.php';
    require_once __DIR__ . '/../Model/DAO/FornecedorDAO.php';
    require_once __DIR__ . '/../Model/DAO/MovimentacaoDAO.php';
    require_once __DIR__ . '/../Model/Produto.php';
    require_once __DIR__ . '/../Model/Categoria.php';
    require_once __DIR__ . '/../Model/Fornecedor.php';
    require_once __DIR__ . '/../Model/Movimentacao.php';
    require_once __DIR__ . '/../Model/Cliente.php';

    class ProdutoController {

        private ProdutoDAO $produtoDAO;
        private CategoriaDAO $categoriaDAO;
        private FornecedorDAO $fornecedorDAO;
        private MovimentacaoDAO $movimentacaoDAO;

        public function __construct() {
            $this->produtoDAO = new ProdutoDAO();
            $this->categoriaDAO = new CategoriaDAO();
            $this->fornecedorDAO = new FornecedorDAO();
            $this->movimentacaoDAO = new MovimentacaoDAO();
        }

        public function listarProdutos(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $listaDeprodutos = $this->produtoDAO->listarPorUsuario($_SESSION['id']);

            require_once __DIR__ . '/../View/Produto/ListarProduto.php';
        }

        public function prepararCadastro(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $listaDeFornecedores = $this->fornecedorDAO->listarPorUsuario($_SESSION['id']);
            $listaDeCategorias = $this->categoriaDAO->listarPorUsuario($_SESSION['id']);

            require_once __DIR__ . '/../View/Produto/CadastrarProduto.php';
        }

        public function salvarProduto() {
            session_start();
            if(!isset($_SESSION['id'])){ header("Location: ../index.php"); exit(); }

            $sku = $_POST['sku'];
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $quantidade = $_POST['quantidade'];
            $estoqueMinimo = $_POST['estoque_minimo'];
            $id_categoria = $_POST['id_categoria'];
            $id_fornecedor = $_POST['id_fornecedor'];

            if(empty($sku) || empty($nome) || empty($descricao) || $preco === '' || $quantidade === '' || $estoqueMinimo === '' || empty($id_fornecedor) || empty($id_categoria)){
                $_SESSION['erro'] = "Todos os campos são obrigatórios.";
                header("Location: ../Controller/ProdutoController.php?acao=prepararCadastro");
                exit();
            }

            if(!is_numeric($preco) || !is_numeric($quantidade) || !is_numeric($estoqueMinimo)){
                $_SESSION['erro'] = "Os campos preço, quantidade e estoque mínimo devem ser numéricos.";
                header("Location: ../Controller/ProdutoController.php?acao=prepararCadastro");
                exit();
            }

            if($preco < 0 || $quantidade < 0 || $estoqueMinimo < 0){
                $_SESSION['erro'] = "Os campos preço, quantidade e estoque mínimo não podem ser negativos.";
                header("Location: ../Controller/ProdutoController.php?acao=prepararCadastro");
                exit();
            }

            $preco = (float)$preco;
            $quantidade = (int)$quantidade;
            $estoqueMinimo = (int)$estoqueMinimo;

            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($_SESSION['id']);

            $categoria = new Categoria($id_categoria, null, $usuarioTenant);

            $fornecedor = new Fornecedor(null, null, null, null, $usuarioTenant);
            $fornecedor->setId($id_fornecedor);

            $produto = new Produto($sku, $nome, $descricao, $preco, $quantidade, $estoqueMinimo, $usuarioTenant, $fornecedor, $categoria);

            $this->produtoDAO->inserir($produto);

            if($quantidade > 0){
                $movimentacao = new Movimentacao('entrada', $quantidade, date('Y-m-d H:i:s'), "Inserção de Produtos", $produto, $usuarioTenant);
                $this->movimentacaoDAO->registrar($movimentacao);
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
                $this->produtoDAO->excluir($_GET['id_produto'], $_SESSION['id']);
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

            $id_produto = $_POST['id_produto'];
            $tipo = strtolower($_POST['tipo']); // 'entrada' ou 'saida' <enum no banco de dados>.
            $qtdMovimentada = (int)$_POST['quantidade'];
            $motivo = $_POST['motivo'];
            $id_usuario = $_SESSION['id'];
            $data = date('Y-m-d H:i:s');

           if($qtdMovimentada <= 0){
                $_SESSION['erro'] = "O campo quantidade não pode ser zero ou negativo.";
                header("Location: ../Controller/ProdutoController.php?acao=listarProdutos");
                exit();
            }

            $produto = $this->produtoDAO->buscarPorId($id_produto, $id_usuario);

            if(!$produto) {
                $_SESSION['erro'] = "Produto não encontrado!";
                header("Location: ../Controller/ProdutoController.php?acao=listarProdutos");
                exit();
            }

            $saldoAtual = (int)$produto->getQuantidade();

            if($tipo === "entrada"){
                $novoSaldo = $saldoAtual + $qtdMovimentada;
            } else {
                if($saldoAtual >= $qtdMovimentada){
                    $novoSaldo = $saldoAtual - $qtdMovimentada;
                } else {
                    // CORREÇÃO DO ESTOQUE INSUFICIENTE:
                    $_SESSION['erro'] = "Estoque insuficiente para realizar a saída (Saldo atual: {$saldoAtual}).";
                    header("Location: ../Controller/ProdutoController.php?acao=listarProdutos");
                    exit();
                }
            }

            $this->produtoDAO->atualizarQuantidade($id_produto, $novoSaldo, $id_usuario);

            // Movimentacao exige objetos (Produto e Usuario), não IDs crus —
            // o objeto Produto já vem pronto da busca acima.
            $registrarMovimentacao = new Movimentacao($tipo, $qtdMovimentada, $data, $motivo, $produto, $produto->getUsuario());
            $this->movimentacaoDAO->registrar($registrarMovimentacao);

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

            $dadosProduto = $this->produtoDAO->buscarPorId($id_produto, $id_usuario);

            if (!$dadosProduto) {
                header("Location: ../Controller/ProdutoController.php?acao=listarProdutos");
                exit();
            }

            $listaCategorias = $this->categoriaDAO->listarPorUsuario($id_usuario);
            $listaFornecedores = $this->fornecedorDAO->listarPorUsuario($id_usuario);

            require_once __DIR__ . '/../View/Produto/EditarProduto.php';
        }

        public function atualizarProduto(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($_SESSION['id']);

            $categoria = new Categoria($_POST['id_categoria'], null, $usuarioTenant);

            $fornecedor = new Fornecedor(null, null, null, null, $usuarioTenant);
            $fornecedor->setId($_POST['id_fornecedor']);

            // Construímos o Produto já com as associações corretas no construtor,
            // em vez de instanciar "vazio" e ir setando campo por campo.
            $produtoAtualizado = new Produto(
                $_POST['sku'],
                $_POST['nome'],
                $_POST['descricao'],
                (float)$_POST['preco'],
                null, // quantidade não é alterada por aqui — só via movimentação
                (int)$_POST['estoque_minimo'],
                $usuarioTenant,
                $fornecedor,
                $categoria
            );
            $produtoAtualizado->setId($_POST['id']);

            $this->produtoDAO->atualizar($produtoAtualizado);

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
