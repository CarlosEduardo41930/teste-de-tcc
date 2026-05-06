<?php
function validarCPF($cpf) {
    // Remove tudo que não for número
    try{
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verifica se tem 11 dígitos
    if (strlen($cpf) != 11) {
        throw new Exception("CPF deve conter 11 dígitos");
    }

    // Elimina CPFs inválidos (todos iguais)
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        throw new Exception("CPF inválido");
    }
    

    // Primeiro dígito verificador
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += $cpf[$i] * (10 - $i);
    }

    $dig1 = ($soma * 10) % 11;
    if ($dig1 == 10) {
        $dig1 = 0;
    }

    // Segundo dígito verificador
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += $cpf[$i] * (11 - $i);
    }

    $dig2 = ($soma * 10) % 11;
    if ($dig2 == 10) {
        $dig2 = 0;
    }


    // Verifica se os dígitos batem
    
    if ($cpf[9] == $dig1 && $cpf[10] == $dig2) {
        return true;
    } else {
        throw new Exception("CPF inválido");
    }
    }catch(Exception $e){
        $_SESSION['erro'][]= "Erro ao cadastrar: " . $e->getMessage();
    }
}

$dado = validarCPF("972.044.238-79");
if ($dado) {
    echo "CPF válido";
} else {
    echo $_SESSION['erro'];
}