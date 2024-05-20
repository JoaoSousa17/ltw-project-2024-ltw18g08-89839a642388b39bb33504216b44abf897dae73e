<?php
function convertCurrency($amount, $fromCurrency, $toCurrency = 'dollar') {
    // Define as taxas de câmbio em relação ao dólar
    $exchangeRates = [
        'dollar' => 1,
        'euro' => 0.85,
        'libra' => 0.75,
        'real' => 5.5,
        'yen' => 110,
    ];

    // Verifica se as moedas fornecidas estão definidas
    if (!isset($exchangeRates[$fromCurrency]) || !isset($exchangeRates[$toCurrency])) {
        throw new Exception('Currency not supported');
    }

    // Converte o valor para dólar
    $amountInDollars = floatval($amount) / $exchangeRates[$fromCurrency];

    // Converte de dólar para a moeda de destino
    $convertedAmount = $amountInDollars * $exchangeRates[$toCurrency];

    return $convertedAmount;
}

function formatCurrency($amount, $currency) {
    switch ($currency) {
        case 'euro':
            return '€' . number_format($amount, 2);
        case 'libra':
            return '£' . number_format($amount, 2);
        case 'real':
            return 'R$' . number_format($amount, 2);
        case 'yen':
            return '¥' . number_format($amount, 2);
        case 'dollar':
        default:
            return '$' . number_format($amount, 2);
    }
}
?>
