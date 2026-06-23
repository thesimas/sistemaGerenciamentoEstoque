<?php
    require_once __DIR__ . '/../Config/Conexao.php';
    require_once __DIR__ . '/../Model/Fornecedor.php';
    require_once __DIR__ . '/../Model/Cliente.php';

    class FornecedorDAO {

        public function inserir(Fornecedor $fornecedor){
            $conexao = Conexao::Conectar();

            $sql = "INSERT INTO fornecedores (nome_empresa, cnpj, email, telefone, id_usuario) 
                    VALUES (:nome_empresa, :cnpj, :email, :telefone, :id_usuario)";

            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(':nome_empresa', $fornecedor->getNomeEmpresa());
            $stmt->bindValue(':cnpj', $fornecedor->getCnpj());
            $stmt->bindValue(':email', $fornecedor->getEmail());
            $stmt->bindValue(':telefone', $fornecedor->getTelefone());
            // Extraímos o ID de dentro do objeto Usuario embutido no Fornecedor:
            $stmt->bindValue(':id_usuario', $fornecedor->getUsuario()->getId());

            $stmt->execute();

            $idGerado = $conexao->lastInsertId();
            $fornecedor->setId($idGerado);

            return $idGerado;
        }

        public function listarPorUsuario($id_usuario){
            $conexao = Conexao::Conectar();

            $sql = "SELECT * FROM fornecedores WHERE id_usuario = :id_usuario ORDER BY nome_empresa ASC";

            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(':id_usuario', $id_usuario);
            $stmt->execute();

            $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $listaFornecedores = [];

            foreach ($linhas as $linha) {
                $listaFornecedores[] = $this->montarFornecedor($linha, $id_usuario);
            }

            return $listaFornecedores;
        }

        public function buscarPorId($id_fornecedor, $id_usuario){
            $conexao = Conexao::Conectar();

            $sql = "SELECT * FROM fornecedores WHERE id = :id AND id_usuario = :id_usuario";

            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(':id', $id_fornecedor);
            $stmt->bindValue(':id_usuario', $id_usuario);
            $stmt->execute();

            $linha = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$linha) {
                return null; // Fornecedor não encontrado ou não pertence a este usuário
            }

            return $this->montarFornecedor($linha, $id_usuario);
        }

        public function atualizar(Fornecedor $fornecedor){
            $conexao = Conexao::Conectar();

            $sql = "UPDATE fornecedores SET nome_empresa = :nome_empresa, cnpj = :cnpj, email = :email, telefone = :telefone 
                    WHERE id = :id AND id_usuario = :id_usuario";

            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(':nome_empresa', $fornecedor->getNomeEmpresa());
            $stmt->bindValue(':cnpj', $fornecedor->getCnpj());
            $stmt->bindValue(':email', $fornecedor->getEmail());
            $stmt->bindValue(':telefone', $fornecedor->getTelefone());
            $stmt->bindValue(':id', $fornecedor->getId());
            $stmt->bindValue(':id_usuario', $fornecedor->getUsuario()->getId());

            return $stmt->execute();
        }

        public function excluir($id_fornecedor, $id_usuario){
            $conexao = Conexao::Conectar();

            $sql = "DELETE FROM fornecedores WHERE id = :id AND id_usuario = :id_usuario";

            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(':id', $id_fornecedor);
            $stmt->bindValue(':id_usuario', $id_usuario);

            return $stmt->execute();
        }

        private function montarFornecedor(array $linha, $id_usuario): Fornecedor {
            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($id_usuario);

            $fornecedor = new Fornecedor(
                $linha['nome_empresa'],
                $linha['cnpj'],
                $linha['email'],
                $linha['telefone'],
                $usuarioTenant
            );
            $fornecedor->setId($linha['id']);

            return $fornecedor;
        }
    }
?>