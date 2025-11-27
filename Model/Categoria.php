<?php 
    require_once __DIR__ . '/../Config/Conexao.php';
    class Categoria {
        private $id;
        private $nome; 
        private $id_usuario;

        public function __construct($id, $nome, $id_usuario){
            $this->id = $id;
            $this->nome = $nome;
            $this->id_usuario = $id_usuario;
        }

        public function buscarPorIdCategoria($id_categoria, $id_usuario){
            $conexao = Conexao :: Conectar();

            $consultaSql = "SELECT * FROM categorias WHERE id_usuario = :id_usuario AND id = :id_categoria";

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao -> bindValue(':id_usuario', $id_usuario);
            $declaracao -> bindValue(':id_categoria', $id_categoria);

            $declaracao->execute();

            return $declaracao->fetch(PDO::FETCH_ASSOC);
        }

        public function atualizar($id_categoria, $id_usuario, $nome){
            $conexao = Conexao :: Conectar();

            $consultaSql = "UPDATE categorias SET nome = :nome WHERE id = :id_categoria AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao -> bindValue(':id_usuario', $id_usuario);
            $declaracao -> bindValue(':id_categoria', $id_categoria);
            $declaracao -> bindValue(':nome', $nome);

            return $declaracao->execute();
        }

        public function salvar(){
            $conexao = Conexao::Conectar();

            $consultaSql = "INSERT INTO categorias (nome, id_usuario) 
                            VALUES (:nome, :id_usuario)";         

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':nome', $this->nome);
            $declaracao->bindValue(':id_usuario', $this->id_usuario);

            return $declaracao->execute();
        }

        public function listarCategorias($id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSql = "SELECT * FROM categorias WHERE id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':id_usuario', $id_usuario);
            $declaracao->execute();

            return $declaracao->fetchAll(PDO::FETCH_ASSOC);
        }

        public function excluirCategoria($id_usuario, $id_categoria){
            $conexao = Conexao::Conectar();

            $consultaSql = "DELETE FROM categorias WHERE id_usuario = :id_usuario AND id = :id_categoria";
            $declaracao = $conexao -> prepare($consultaSql);
            $declaracao->bindValue(':id_usuario', $id_usuario);
            $declaracao->bindValue(':id_categoria', $id_categoria);
            
            return $declaracao->execute();
        }

        public function getId(){
            return $this->id;
        }
        public function getNome(){
            return $this->nome;
        }
        public function getIdUsuario(){
            return $this->id_usuario;
        }
    }

?>