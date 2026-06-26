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

senha.addEventListener("input", verificarSenha);

confirmarSenha.addEventListener("input", verificarSenha);


function togglePassword(id, botao){

    const input = document.getElementById(id);

    if(input.type === "password"){

        input.type = "text";

        botao.textContent = "🙈";

    }else{

        input.type = "password";

        botao.textContent = "👁";

    }

}