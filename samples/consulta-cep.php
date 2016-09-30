<?php
/*
|--------------------------------------------------------------------------
| EscapeWork\Frete - ConsultaCEP exemplo
|--------------------------------------------------------------------------
*/

use EscapeWork\Frete\Correios\ConsultaCEP;
use EscapeWork\Frete\FreteException;

require(__DIR__.'/../vendor/autoload.php');

try {
    $consulta = new ConsultaCEP;
    $result   = $consulta->setCep(93320080)
                         ->find();

    # aqui você pode consultar os acessos aos resultados
    echo 'Bairro: '        . $result->bairro . "\n";
    echo 'CEP: '           . $result->cep . "\n";
    echo 'Cidade: '        . $result->cidade . "\n";
    echo 'Complemento: '   . $result->complemento . "\n";
    echo 'Complemento 2: ' . $result->complemento2 . "\n";
    echo 'Endereço: '      . $result->end . "\n";
    echo 'UF: '            . $result->uf . "\n";
}
catch (FreteException $e) {
    echo 'Erro ao buscar o endereço: ' . $e->getMessage();
}
