<?php
    require_once __DIR__ . '/../../Config/Conexao.php';
    require_once __DIR__ . '/../Usuario.php';
    require_once __DIR__ . '/../Cliente.php';
    require_once __DIR__ . '/../Administrador.php';

    class UsuarioDAO {

        public function inserir(Usuario $usuario){
            $conexao = Conexao::Conectar();

            $tipo = $this->tipoUsuario($usuario);
            $senhaHash = password_hash($usuario->getSenha(), PASSWORD_DEFAULT);
            $nomeEmpresa = ($usuario instanceof Cliente) ? $usuario->getNomeEmpresa() : null;
            $respTecnico = ($usuario instanceof Cliente) ? $usuario->getResponsavelTecnico() : null;

            // REMOVIDO a coluna 'nome' do INSERT
            $consultaSql = "INSERT INTO usuarios (email, senha, foto_perfil, tipo, nome_empresa, responsavel_tecnico) 
                             VALUES (:email, :senha, :foto_perfil, :tipo, :nome_empresa, :responsavel_tecnico)";

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':email', $usuario->getEmail());
            $declaracao->bindValue(':senha', $senhaHash);
            $declaracao->bindValue(':foto_perfil', $usuario->getFotoPerfil());
            $declaracao->bindValue(':tipo', $tipo);
            $declaracao->bindValue(':nome_empresa', $nomeEmpresa);
            $declaracao->bindValue(':responsavel_tecnico', $respTecnico);

            $declaracao->execute();

            $idGerado = $conexao->lastInsertId();
            $usuario->setId($idGerado);

            return $idGerado;
        }

        public function verificarLogin($email, $senha){
            $conexao = Conexao::Conectar();

            $consultaSql = "SELECT * FROM usuarios WHERE email = :email";
            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':email', $email);
            $declaracao->execute();

            if ($declaracao->rowCount() > 0) {
                $linha = $declaracao->fetch(PDO::FETCH_ASSOC);
                if (password_verify($senha, $linha['senha'])) {
                    return $this->montarUsuario($linha);
                }
            }

            return null;
        }

        public function emailExiste($email){
            $conexao = Conexao::Conectar();
            $sql = "SELECT id FROM usuarios WHERE email = :email";
            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(":email",$email);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }

        public function buscarPorId($id){
            $conexao = Conexao::Conectar();
            $consultaSql = "SELECT * FROM usuarios WHERE id = :id";
            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':id', $id);
            $declaracao->execute();

            $linha = $declaracao->fetch(PDO::FETCH_ASSOC);

            if (!$linha) {
                return null;
            }

            return $this->montarUsuario($linha);
        }

        public function listarTodosExceto($idAdmin){
            $conexao = Conexao::Conectar();
            $consultaSql = "SELECT * FROM usuarios WHERE id != :id";
            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':id', $idAdmin);
            $declaracao->execute();

            $linhas = $declaracao->fetchAll(PDO::FETCH_ASSOC);
            $usuarios = [];

            foreach ($linhas as $linha) {
                $usuarios[] = $this->montarUsuario($linha);
            }

            return $usuarios;
        }

        public function atualizarPerfil(Usuario $usuario){
            $conexao = Conexao::Conectar();
            $senhaHash = password_hash($usuario->getSenha(), PASSWORD_DEFAULT);

            $consultaSql = "UPDATE usuarios SET email = :email, senha = :senha, foto_perfil = :foto_perfil, 
                    nome_empresa = :nome_empresa, responsavel_tecnico = :responsavel_tecnico 
                     WHERE id = :id";

            $nomeEmpresa = ($usuario instanceof Cliente) ? $usuario->getNomeEmpresa() : null;
            $respTecnico = ($usuario instanceof Cliente) ? $usuario->getResponsavelTecnico() : null;

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':id', $usuario->getId());
            $declaracao->bindValue(':email', $usuario->getEmail());
            $declaracao->bindValue(':senha', $senhaHash);
            $declaracao->bindValue(':foto_perfil', $usuario->getFotoPerfil());
            $declaracao->bindValue(':nome_empresa', $nomeEmpresa);
            $declaracao->bindValue(':responsavel_tecnico', $respTecnico);

            return $declaracao->execute();
        }

        public function excluir($idAlvo){
            $conexao = Conexao::Conectar();
            $consultaSql = "DELETE FROM usuarios WHERE id = :id";
            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':id', $idAlvo);
            return $declaracao->execute();
        }

        private function tipoUsuario(Usuario $usuario){
            return ($usuario instanceof Cliente) ? 'cliente' : 'administrador';
        }

        private function montarUsuario(array $linha): Usuario {
            if ($linha['tipo'] === 'cliente') {
                $usuario = new Cliente(
                    $linha['nome_empresa'],
                    $linha['email'],
                    $linha['senha'],
                    $linha['nome_empresa'] ?? null,
                    $linha['responsavel_tecnico'] ?? null,
                    $linha['foto_perfil'] ?? null
                );
            } else {
                $usuario = new Administrador(
                    $linha['responsavel_tecnico'], 
                    $linha['email'],
                    $linha['senha'],
                    $linha['foto_perfil'] ?? null
                );
            }

            $usuario->setId($linha['id']);
            return $usuario;
        }
    }
?>