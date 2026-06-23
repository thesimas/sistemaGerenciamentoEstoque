<?php
    require_once __DIR__ . '/../Config/Conexao.php';
    require_once __DIR__ . '/../Model/Movimentacao.php';
    require_once __DIR__ . '/../Model/Produto.php';
    require_once __DIR__ . '/../Model/Cliente.php';

    class MovimentacaoDAO {

        public function registrar(Movimentacao $movimentacao){
            $conexao = Conexao::Conectar();

            $consultaSQL = "INSERT INTO movimentacoes (tipo, quantidade, data_movimentacao, motivo, id_produto, id_usuario) 
                             VALUES (:tipo, :quantidade, :data, :motivo, :id_produto, :id_usuario)";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(':tipo', $movimentacao->getTipo());
            $declaracao->bindValue(':quantidade', $movimentacao->getQuantidade());
            $declaracao->bindValue(':data', $movimentacao->getData());
            $declaracao->bindValue(':motivo', $movimentacao->getMotivo());
            // Extraímos os IDs de dentro dos objetos embutidos na Movimentacao:
            $declaracao->bindValue(':id_produto', $movimentacao->getProduto()->getId());
            $declaracao->bindValue(':id_usuario', $movimentacao->getUsuario()->getId());

            $declaracao->execute();

            $idGerado = $conexao->lastInsertId();
            $movimentacao->setId($idGerado);

            return $idGerado;
        }

        public function listarPorUsuario($id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "SELECT 
                        m.id, m.tipo, m.quantidade, m.data_movimentacao, m.motivo,
                        p.id AS id_produto, p.sku, p.nome
                    FROM movimentacoes m
                    JOIN produtos p ON m.id_produto = p.id
                    WHERE p.id_usuario = :id_usuario
                    ORDER BY m.data_movimentacao DESC";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->execute();

            $linhas = $declaracao->fetchAll(PDO::FETCH_ASSOC);
            $listaMovimentacoes = [];

            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($id_usuario);

            foreach ($linhas as $linha) {
                // Produto "leve": só os campos que o relatório de movimentação precisa exibir.
                $produto = new Produto($linha['sku'], $linha['nome'], null, null, null, null, $usuarioTenant);
                $produto->setId($linha['id_produto']);

                $movimentacao = new Movimentacao(
                    $linha['tipo'],
                    $linha['quantidade'],
                    $linha['data_movimentacao'],
                    $linha['motivo'],
                    $produto,
                    $usuarioTenant
                );
                $movimentacao->setId($linha['id']);

                $listaMovimentacoes[] = $movimentacao;
            }

            return $listaMovimentacoes;
        }
    }
?>