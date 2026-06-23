<?php
    require_once __DIR__ . '/../Config/Conexao.php';
    require_once __DIR__ . '/../Model/Produto.php';
    require_once __DIR__ . '/../Model/Cliente.php';
    require_once __DIR__ . '/../Model/Fornecedor.php';
    require_once __DIR__ . '/../Model/Categoria.php';

    class ProdutoDAO {

        public function inserir(Produto $produto){
            $conexao = Conexao::Conectar();

            $consultaSQL = "INSERT INTO produtos (sku, nome, descricao, preco, quantidade, estoque_minimo, id_usuario, id_fornecedor, id_categoria) 
                             VALUES (:sku, :nome, :descricao, :preco, :quantidade, :estoqueMinimo, :id_usuario, :id_fornecedor, :id_categoria)";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(':sku', $produto->getSku());
            $declaracao->bindValue(':nome', $produto->getNome());
            $declaracao->bindValue(':descricao', $produto->getDescricao());
            $declaracao->bindValue(':preco', $produto->getPreco());
            $declaracao->bindValue(':quantidade', $produto->getQuantidade());
            $declaracao->bindValue(':estoqueMinimo', $produto->getEstoqueMinimo());
            // Extraímos os IDs de dentro dos objetos embutidos no Produto:
            $declaracao->bindValue(':id_usuario', $produto->getUsuario()->getId());
            $declaracao->bindValue(':id_fornecedor', $produto->getFornecedor()?->getId());
            $declaracao->bindValue(':id_categoria', $produto->getCategoria()?->getId());

            $declaracao->execute();

            $idGerado = $conexao->lastInsertId();
            $produto->setId($idGerado);

            return $idGerado;
        }

        public function listarPorUsuario($id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "SELECT * FROM produtos WHERE id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->execute();

            $linhas = $declaracao->fetchAll(PDO::FETCH_ASSOC);
            $listaProdutos = [];

            foreach ($linhas as $linha) {
                $listaProdutos[] = $this->montarProduto($linha, $id_usuario);
            }

            return $listaProdutos;
        }

        public function buscarPorId($id_produto, $id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "SELECT * FROM produtos WHERE id = :id AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":id", $id_produto);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->execute();

            $linha = $declaracao->fetch(PDO::FETCH_ASSOC);

            if (!$linha) {
                return null; // Produto não encontrado ou não pertence a este usuário
            }

            return $this->montarProduto($linha, $id_usuario);
        }

        public function atualizar(Produto $produto){
            $conexao = Conexao::Conectar();

            // Nota: Não atualizamos 'quantidade' aqui, apenas via movimentação!
            $sql = "UPDATE produtos SET sku=:sku, nome=:nome, descricao=:descricao, preco=:preco, 
                    estoque_minimo=:minimo, id_categoria=:cat, id_fornecedor=:forn 
                    WHERE id=:id AND id_usuario=:id_usuario";

            $declaracao = $conexao->prepare($sql);
            $declaracao->bindValue(":sku", $produto->getSku());
            $declaracao->bindValue(":nome", $produto->getNome());
            $declaracao->bindValue(":descricao", $produto->getDescricao());
            $declaracao->bindValue(":preco", $produto->getPreco());
            $declaracao->bindValue(":minimo", $produto->getEstoqueMinimo());
            $declaracao->bindValue(":cat", $produto->getCategoria()?->getId());
            $declaracao->bindValue(":forn", $produto->getFornecedor()?->getId());
            $declaracao->bindValue(":id", $produto->getId());
            $declaracao->bindValue(":id_usuario", $produto->getUsuario()->getId());

            return $declaracao->execute();
        }

        public function atualizarQuantidade($id_produto, $novaQuantidade, $id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "UPDATE produtos SET quantidade = :quantidade WHERE id = :id AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":quantidade", $novaQuantidade);
            $declaracao->bindValue(":id", $id_produto);
            $declaracao->bindValue(":id_usuario", $id_usuario);

            return $declaracao->execute();
        }

        public function excluir($id_produto, $id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "DELETE FROM produtos WHERE id = :id AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":id", $id_produto);
            $declaracao->bindValue(":id_usuario", $id_usuario);

            return $declaracao->execute();
        }

        /**
         * Monta o objeto Produto completo a partir de uma linha do banco.
         * Os objetos relacionados (usuário/fornecedor/categoria) são
         * recriados aqui apenas com o ID, no mesmo espírito do "usuarioTenant"
         * usado na CategoriaDAO — eles servem como referência, não como
         * cópia completa do registro relacionado.
         */
        private function montarProduto(array $linha, $id_usuario): Produto {
            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($id_usuario);

            $fornecedor = null;
            if (!empty($linha['id_fornecedor'])) {
                $fornecedor = new Fornecedor(null, null, null, null, $usuarioTenant);
                $fornecedor->setId($linha['id_fornecedor']);
            }

            $categoria = null;
            if (!empty($linha['id_categoria'])) {
                $categoria = new Categoria($linha['id_categoria'], null, $usuarioTenant);
            }

            $produto = new Produto(
                $linha['sku'],
                $linha['nome'],
                $linha['descricao'],
                $linha['preco'],
                $linha['quantidade'],
                $linha['estoque_minimo'],
                $usuarioTenant,
                $fornecedor,
                $categoria
            );
            $produto->setId($linha['id']);

            return $produto;
        }
    }
?>