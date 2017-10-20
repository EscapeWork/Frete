# EscapeWork/Frete

<p align="center">
<img src="http://www.correios.com.br/++theme++correios.site.tema/images/logo_correios.png" alt="">
</p>

<p align="center">
<a href="http://travis-ci.org/EscapeWork/Frete"><img src="https://secure.travis-ci.org/EscapeWork/Frete.png"></a>
<a href="https://packagist.org/packages/escapework/frete"><img src="https://poser.pugx.org/escapework/frete/v/stable.png"></a>
<a href="https://packagist.org/packages/escapework/frete"><img src="https://poser.pugx.org/escapework/frete/downloads.png" alt=""></a>
<a href="https://github.com/EscapeWork/Frete"><img src="https://img.shields.io/packagist/l/EscapeWork/Frete.svg?style=flat" alt="License MIT"></a>
</p>

<p align="center">Biblioteca PHP que utiliza os webservices dos correios para cálculo de frete,
rastreamento de encomendas e busca de endereços através do CEP.</p>

## Instalação

Instalação via composer.

```json
$ composer require escapework/frete:"0.5.*"
```

## Calculando preço e prazo

```php
use EscapeWork\Frete\Correios\PrecoPrazo;
use EscapeWork\Frete\Correios\Data;
use EscapeWork\Frete\FreteException;

$frete = new PrecoPrazo();
$frete->setCodigoServico(Data::SEDEX)
      ->setCodigoEmpresa('Codigo')      # opcional
      ->setSenha('Senha')               # opcional
      ->setCepOrigem('cep de origem')   # apenas numeros, sem hifen(-)
      ->setCepDestino('cep de destino') # apenas numeros, sem hifen(-)
      ->setComprimento(30)              # obrigatorio
      ->setAltura(30)                   # obrigatorio
      ->setLargura(30)                  # obrigatorio
      ->setDiametro(30)                 # obrigatorio
      ->setPeso(0.5);                   # obrigatorio

try {
    $result = $frete->calculate();

    echo $result['cServico']['Valor'];
    echo $result['cServico']['PrazoEntrega'];

    var_dump($result); // debugge o resultado!
}
catch (FreteException $e) {
    // trate o erro adequadamente (e não escrevendo na tela)
    echo $e->getMessage();
    echo $e->getCode(); // este código é o código de erro dos correios
                        // pode ser usado pra dar mensagens como CEP inválido para o cliente
}
```

#### Tipos de frete disponíveis

```php
EscapeWork\Frete\Correios\Data::SEDEX;          # sedex
EscapeWork\Frete\Correios\Data::SEDEX_A_COBRAR; # sedex a cobrar
EscapeWork\Frete\Correios\Data::SEDEX_10;       # sedex 10
EscapeWork\Frete\Correios\Data::SEDEX_HOJE;     # sedex hoje
EscapeWork\Frete\Correios\Data::PAC;            # pac
```

#### Buscando múltiplos serviços

Também é possível obter um array com vários serviços (Sedex e PAC, por exemplo) utilizando a classe `PrecoPrazo`.

```php
$frete = new PrecoPrazo();
$frete->setCodigoServico([Data::SEDEX, Data::PAC])
      ... // todo os setters igual a chamada acima

try {
    $results = $frete->calculate();

    foreach ($results as $result) {
        echo $result['cServico']['Valor'];
        echo $result['cServico']['PrazoEntrega'];

        var_dump($result); // debugge o resultado!
    }
}
catch (FreteException $e) {
    // trate o erro adequadamente (e não escrevendo na tela)
    echo $e->getMessage();
}
```

## Rastreamento de encomendas

Fazendo o rastreio de encomendas online.

```php
use EscapeWork\Frete\Correios\Rastreamento;
use EscapeWork\Frete\FreteException;

$rastreamento = new Rastreamento;
$rastreamento->setUsuario('ECT')
             ->setSenha('SRO')
             ->setObjetos('SQ458226057BR');

try {
    $result = $rastreamento->track();

    var_dump($result->delivered()); // se a entrega já foi finalizada (true ou false)
    var_dump($result->inTransit()); // se o pacote está em transito (true ou false)

    echo $result['evento']['tipo'];
    echo $result['evento']['status'];
    echo $result['evento']['data'];
    echo $result['evento']['hora'];
    echo $result['evento']['descricao'];

    var_dump($result); // debugar, debugar!
}
catch (FreteException $e) {
    // trate o erro adequadamente (e não escrevendo na tela)
    echo $e->getMessage();
}
```

#### Buscando múltiplas encomendas

Também é possível buscar múltiplas encomendas com a classe `Rastreamento`.

```php
$rastreamento = new Rastreamento;
$rastreamento->setUsuario('ECT')
             ->setSenha('SRO')
             ->setObjetos(['SQ458226057BR', 'SQ458226057BR']); // passe um array com os objetos

try {
    $results = $rastreamento->track();

    foreach ($results as $result) {
        var_dump($result->delivered());
        ...
    }
}
```

## Consulta de CEP pelo webservice dos correios

Você também pode buscar o endereço através de um CEP.

```php
use EscapeWork\Frete\Correios\ConsultaCEP;
use EscapeWork\Frete\FreteException;

try {
    $consulta = new ConsultaCEP;
    $result   = $consulta->setCep(93320080)
                         ->find();

    # ou, pra facilitar, você pode usar o método
    # ConsultaCEP::search(93320080)

    echo $result->bairro;
    echo $result->cep;
    echo $result->cidade;
    echo $result->complemento;
    echo $result->complemento2;
    echo $result->end;
    echo $result->uf;

    var_dump($result); // debugar, debugar!
}
catch (FreteException $e) {
    // trate o erro adequadamente (e não escrevendo na tela)
    echo $e->getMessage();
}
```

***

### Testes

Caso queira rodar os testes em seu computador, clone o repositório, execute um `composer install --dev`, e depois execute o seguinte comando no terminal:

```
$ vendor/bin/phpspec run
```

### Referências

Referências utilizadas para o desenvolvimento.

* [Cálculo de preço e prazo](http://www.correios.com.br/para-voce/correios-de-a-a-z/pdf/calculador-remoto-de-precos-e-prazos/manual-de-implementacao-do-calculo-remoto-de-precos-e-prazos)
* [Rastreamento online](http://www.correios.com.br/para-voce/correios-de-a-a-z/pdf/rastreamento-de-objetos/Manual_SROXML_28fev14.pdf)
* [SIGEP](http://www.corporativo.correios.com.br/encomendas/sigepweb/doc/Manual_de_Implementacao_do_Web_Service_SIGEPWEB_Logistica_Reversa.pdf)

### License

See [License](https://github.com/EscapeWork/Frete/blob/master/LICENSE)
