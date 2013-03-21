<?php namespace EscapeWork\Frete;

class CodigoServico
{

    /**
     * Sedex varejo
     */
    const SEDEX = 40010;

    /**
     * Sedex a cobrar varejo
     */
    const SEDEX_A_COBRAR = 40045;

    /**
     * Sedex 10 varejo
     */
    const SEDEX_10 = 40215;

    /**
     * Sedex hoje varejo
     */
    const SEDEX_HOJE = 40290;

    /**
     * PAC Varejo
     */
    const PAC = 41106;

    /**
     * Array com todos os códigos
     */
    public static $codigos = array(
        40010, 40045, 40215, 40290, 41106
    );
}