<?php
    require_once __DIR__ . '/../Config/Conexao.php';

    class Relatorio{

        public function gerarTotalEstoque($id_usuario){
            $conexao = Conexao :: Conectar();

            $consultaSQL = "SELECT SUM(quantidade * preco) as Valor_Total_Estoque, COUNT(id) as Total_Itens_Cadastrados FROM produtos WHERE id_usuario = :id_usuario";

            $declaracao = $conexao -> prepare($consultaSQL);
            $declaracao->bindValue(":id_usuario", $id_usuario);

            $declaracao -> execute();

            return $declaracao->fetch(PDO::FETCH_ASSOC);
        }
        
        public function gerarHistoricoMovimentacoesPorData($data_inicio, $data_fim, $id_usuario){
            $conexao = Conexao :: Conectar();

            $consultaSQL = "SELECT 
                        m.data_movimentacao,
                        m.tipo,
                        m.quantidade,
                        m.motivo,
                        p.sku,
                        p.nome
                    FROM movimentacoes m 
                    JOIN produtos p ON m.id_produto = p.id 
                    WHERE m.data_movimentacao BETWEEN :inicio AND :fim 
                    AND m.id_usuario = :id_usuario
                    AND p.id_usuario = :id_usuario";

            $declaracao = $conexao -> prepare($consultaSQL);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->bindValue(":inicio", $data_inicio);
            $declaracao->bindValue(":fim", $data_fim);

            $declaracao -> execute();

            return $declaracao->fetchAll(PDO::FETCH_ASSOC);
        }

        public function geraProdutosMaisVendidos($id_usuario, $tipo){
            $conexao = Conexao :: Conectar();

            $consultaSQL = "SELECT p.sku, p.nome, p.preco AS PREÇO_UNITARIO, SUM(p.quantidade * p.preco) AS Valor_Total FROM movimentacoes m JOIN produtos p ON p.id = m.id_produto WHERE m.tipo = :tipo AND m.id_usuario = :id_usuario GROUP BY p.sku, p.nome, p.preco ORDER BY Valor_Total DESC";

            $declaracao = $conexao -> prepare($consultaSQL);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->bindValue(":tipo", $tipo);

            $declaracao -> execute();

            return $declaracao->fetchAll(PDO::FETCH_ASSOC);
        }
    }

?>