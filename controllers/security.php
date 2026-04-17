<?php

function sanitizar(string $dado, string $tipo = 'texto'): string {
    $dado = trim($dado);
    $dado = preg_replace('/[\x00-\x1F\x7F]/u', '', $dado);
    $dado = strip_tags($dado);
    switch ($tipo) {

        case 'nome':
            // Permite apenas letras (incluindo acentos), espaços e hífens
            $dado = preg_replace('/[^a-zA-ZÀ-ÿ\s\-]/u', '', $dado);
            // Colapsa espaços múltiplos em um só
            $dado = preg_replace('/\s+/', ' ', $dado);
            break;

        case 'email':
            // Remove tudo que não faz parte de um e-mail
            $dado = filter_var($dado, FILTER_SANITIZE_EMAIL);
            break;

        case 'inteiro':
            // Mantém apenas dígitos e sinal negativo
            $dado = preg_replace('/[^0-9\-]/', '', $dado);
            break;

        case 'texto':
        default:
            // Já passou pelas etapas
            break;
    }
    if($tipo === 'nome'){
        $tamanho = mb_strlen($dado, 'UTF-8');
        
        if($tamanho >= 2 && $tamanho <= 50){
            }else{
                $_SESSION['erro'][] =  "Nome: Nome deve ter entre 2 e 50 caracteres.";
        }
    }

    return $dado;
}


function validarSenha(string $senha): void {
    if (strlen($senha) < 6)
        $_SESSION['erro'][] =  "Senha: Mínimo 6 caracteres";

    if (!preg_match('/[A-Z]/', $senha))
        $_SESSION['erro'][] =  "Senha: Pelo menos uma letra maiúscula";

    if (!preg_match('/[0-9]/', $senha))
        $_SESSION['erro'][] =  "Senha: Pelo menos um número";

}
function confirmarSenha(string $senha, string $confirmar_senha){
    if($senha !== $confirmar_senha){
        $_SESSION['erro'][] = 'As senhas não coincidem.';
    }
}

function traduz_data_para_exibir($data)
{
    if ($data == "" or $data == "0000-00-00") {
        return "";
    }
    $dados = explode("-", $data);
    $data_exibir = "{$dados[2]}/{$dados[1]}/{$dados[0]}";
    return $data_exibir;
}


?>
