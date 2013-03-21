## EscapeWork/Frete [![Build Status](https://secure.travis-ci.org/EscapeWork/Frete.png)](http://travis-ci.org/EscapeWork/Frete)

### Instalação

Instalação disponível via Composer.

```
{
    "require": {
        "escapework/frete": "0.1.*"
    }
}
```

***

### Utilização 

```php
use EscapeWork\Frete\Correios;

try {
    $frete = new Correios();
    $frete->setCodigoServico( EscapeWork\Frete\CodigoServico::SEDEX )
             ->setCepOrigem('cep de origem') # apenas numeros, sem hifen(-)
             ->setCepDestino('cep de destino')     # apenas numeros, sem hifen(-)
             ->setComprimento(30)
             ->setAltura(30)
             ->setLargura(30)
             ->setDiametro(30)
             ->setPeso(0.5);
    $frete->calcular();
    
    # opções de retorno 
    echo $frete->getCodigoXml();
    echo $frete->getValor();        # 13,20
    echo $frete->getPrazoEntrega();
    echo $frete->getValorMaoPropria();
    echo $frete->getValorAvisoRecebimento();
    echo $frete->getValorValorDeclarado();
    echo $frete->getEntregaDomiciliar();
    echo $frete->getEntregaSabado();
    echo $frete->getErro();
    echo $frete->getMsgErro();
catch(EscapeWork\Frete\FreteException $e) {
    // error handling 
}
```

### Tipos de frete disponíveis 

```php
EscapeWork\Frete\CodigoServico::SEDEX;          # sedex 
EscapeWork\Frete\CodigoServico::SEDEX_A_COBRAR; # sedex a cobrar
EscapeWork\Frete\CodigoServico::SEDEX_10;       # sedex 10
EscapeWork\Frete\CodigoServico::SEDEX_HOJE;     # sedex hoje
EscapeWork\Frete\CodigoServico::PAC;            # pac
```

***

### Referências 

- http://www.correios.com.br/webServices/PDF/SCPP_manual_implementacao_calculo_remoto_de_precos_e_prazos.pdf