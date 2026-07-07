const senha = document.getElementById("senha");
const confirmarSenha = document.getElementById("confirmarSenha");
const mensagem = document.getElementById("mensagemSenha");

function verificarSenha(){
    if(confirmarSenha.value.length === 0){
        mensagem.innerHTML = "";
        return;
    }

    if(senha.value === confirmarSenha.value){
        mensagem.innerHTML = "✓ As senhas coincidem.";
        mensagem.style.color = "green";
    }else{
        mensagem.innerHTML = "✗ As senhas são diferentes.";
        mensagem.style.color = "red";
    }
}

if (senha && confirmarSenha) {
    senha.addEventListener("input", verificarSenha);
    confirmarSenha.addEventListener("input", verificarSenha);
}

function togglePassword(id, botao){
    const input = document.getElementById(id);

    if(input.type === "password"){
        input.type = "text";
    }else{
        input.type = "password";
        botao.textContent = "👁";
    }
}

function confirmarExclusao(event, urlDaAcao) {
    event.preventDefault();

    Swal.fire({
        title: "Você tem certeza?",
        text: "Você não poderá reverter isso!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, exclua!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Redireciona o navegador para o link de exclusão
            window.location.href = urlDaAcao;
        }
    });
}

function confirmarExclusaoPerfil(event, urlDaAcao) {
    event.preventDefault();
    Swal.fire({
        title: "Atenção!",
        text: "Você está prestes a excluir sua conta! TODOS os seus produtos, categorias e fornecedores serão apagados permanentemente. Deseja realmente excluir seu perfil?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, exclua!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Redireciona o navegador para o link de exclusão
            window.location.href = urlDaAcao;
        }
    });
}

function confirmarExclusaoCliente(event, urlDaAcao) {
    event.preventDefault();
    Swal.fire({
        title: "Atenção!",
        text: "Tem certeza que deseja excluir este usuário? Todos os dados dele serão apagados!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, exclua!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Redireciona o navegador para o link de exclusão
            window.location.href = urlDaAcao;
        }
    });
}