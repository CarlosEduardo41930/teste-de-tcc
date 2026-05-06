<?php
function validarCPF($cpf) {
    // Remove tudo que não for número
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verifica se tem 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }

    // Elimina CPFs inválidos (todos iguais)
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
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
        return false;
    }
}

$dado = "111.111.111-01";
echo var_dump(validarCPF($dado));
if (validarCPF($dado)) {
    echo "CPF válido";
} else {
    echo "CPF inválido";
}