<?php
require_once __DIR__ . '/../Config/Conexao.php';
require_once __DIR__ . '/../Model/Categoria.php';
require_once __DIR__ . '/../Model/Cliente.php'; 

class CategoriaDAO {


    public function inserir(Categoria $categoria) {
        $conexao = Conexao::Conectar();
        
        $sql = "INSERT INTO categorias (nome, id_usuario) VALUES (:nome, :id_usuario)";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':nome', $categoria->getNome());
        // Extraímos o ID de dentro do objeto Usuario embutido na Categoria:
        $stmt->bindValue(':id_usuario', $categoria->getUsuario()->getId());
        
        $stmt->execute();

        // Boa prática de DAO: Injetar o ID gerado pelo MySQL de volta no objeto!
        $idGerado = $conexao->lastInsertId();
        $categoria->setId($idGerado);

        return $idGerado;
    }

    public function listarPorUsuario($id_usuario) {
        $conexao = Conexao::Conectar();
        
        $sql = "SELECT * FROM categorias WHERE id_usuario = :id_usuario ORDER BY nome ASC";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();
        
        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $listaCategorias = [];

        foreach ($linhas as $linha) {
            
            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($id_usuario);

            // Montamos o objeto Categoria completo:
            $categoria = new Categoria($linha['id'], $linha['nome'], $usuarioTenant);
            
            $listaCategorias[] = $categoria;
        }

        return $listaCategorias;
    }


    public function buscarPorId($id_categoria, $id_usuario) {
        $conexao = Conexao::Conectar();
        
        $sql = "SELECT * FROM categorias WHERE id = :id AND id_usuario = :id_usuario";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':id', $id_categoria);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();

        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$linha) {
            return null; // Categoria não encontrada ou não pertence a este usuário
        }

        $usuarioTenant = new Cliente(null, null, null, null);
        $usuarioTenant->setId($id_usuario);

        return new Categoria($linha['id'], $linha['nome'], $usuarioTenant);
    }

    public function atualizar(Categoria $categoria) {
        $conexao = Conexao::Conectar();
        
        $sql = "UPDATE categorias SET nome = :nome WHERE id = :id AND id_usuario = :id_usuario";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':nome', $categoria->getNome());
        $stmt->bindValue(':id', $categoria->getId());
        $stmt->bindValue(':id_usuario', $categoria->getUsuario()->getId());
        
        return $stmt->execute();
    }

    public function excluir($id_categoria, $id_usuario) {
        $conexao = Conexao::Conectar();
        
        $sql = "DELETE FROM categorias WHERE id = :id AND id_usuario = :id_usuario";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':id', $id_categoria);
        $stmt->bindValue(':id_usuario', $id_usuario);
        
        return $stmt->execute();
    }
}
?>