<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../View/Assets/style.css">
</head>
<body>
    <nav>
        <div class="nav-brand">
            <span class="company-name">🛡️ Admin: <?php echo $_SESSION['nome']; ?></span>
        </div>
        
        <div class="nav-links">
            <span style="color: white; font-weight: bold;">Gestão do Sistema</span>
        </div>

        <div class="nav-logout">
            <a href="AutenticaController.php?acao=logout" class="btn-logout">🚪 Sair</a>
        </div>
    </nav>

    <div class="container">
        <h1>Gestão de Usuários (Clientes)</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($listaUsuarios) && count($listaUsuarios) > 0): ?>
                    <?php foreach($listaUsuarios as $user): ?>
                        <tr>
                            <td><?php echo $user->getId(); ?></td>
                            <td>
                                <strong><?php echo $user->getNome(); ?></strong>
                            </td>
                            <td><?php echo $user->getEmail(); ?></td>
                            <td>
                                <?php 
                                    // Diferencia o tipo pela própria classe do objeto, não por uma string solta
                                    if($user instanceof Administrador){
                                        echo '<span style="color: blue; font-weight: bold;">Administrador</span>';
                                    } else {
                                        echo '<span style="color: green;">Cliente/Operador</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="AdminController.php?acao=excluirUsuario&id=<?php echo $user->getId(); ?>" 
                                   onclick="confirmarExclusaoCliente(event, this.href)"
                                   style="color: red; font-weight: bold;">
                                   [Excluir Conta]
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../View/Scripts/scripts.js"></script>
</body>
</html>