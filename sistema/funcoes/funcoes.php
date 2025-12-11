<?php

function CalculaPrecoComDesconto($preco, $descontoPercentual) {
    if (!is_numeric($preco) || !is_numeric($descontoPercentual)) {
        return 0;
    }
    return $preco - ($preco * ($descontoPercentual / 100));
}

?>
