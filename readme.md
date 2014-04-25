## EscapeWork/Frete [![Build Status](https://secure.travis-ci.org/EscapeWork/Frete.png)](http://travis-ci.org/EscapeWork/Frete) [![Latest Stable Version](https://poser.pugx.org/escapework/frete/v/stable.png)](https://packagist.org/packages/escapework/frete) [![Total Downloads](https://poser.pugx.org/escapework/frete/downloads.png)](https://packagist.org/packages/escapework/frete)

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

### License

The MIT License (MIT)

Copyright (c) 2013 Escape Criativação LTDA

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
