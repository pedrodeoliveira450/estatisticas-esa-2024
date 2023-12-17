<?php

$inputFile = __DIR__ . '/../unworken data/classificados esa masculino geral.txt';
$outputFile = __DIR__ . '/../data/esa/classificados masculino geral2.json';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$txtEspcex = file_get_contents($inputFile);
$tokens = explode('CLASSIFICAÇÃO INSCRIÇÃO', $txtEspcex);
$tableLegends = ['INSCRIÇÃO', 'NOME', 'COTA', 'NFEI', 'CLASS', 'CLASSIFICAÇÃO INSCRIÇÃO', 
'PORTUGUÊS MATEMÁTICA', "HISTÓRIA E\nGEOGRAFIA", 'HISTÓRIA E', 'GEOGRAFIA', 'INGLÊS', 
"DATA DE\nNASCIMENTO", 'DATA DE', 'NASCIMENTO', "NOTA\nFINAL", 'NOTA', 'FINAL'];
$data = [];
foreach ($tokens as $keyToken => $token) {
    if ($token == '' || in_array($token, $tableLegends)) continue;
    $page = [];

    $mtokens = preg_split('/\\n\\n/', $token);
    foreach ($mtokens as $mtokenKey => $mtoken) {
        if ($mtoken == '' || in_array($mtoken, $tableLegends)) continue;
        
        $ptokens = preg_split('/\\n/', $mtoken);
        $lineCounter = 0;
        foreach ($ptokens as $ptokenKey => $ptoken) {
            if ($ptoken == '' || in_array($ptoken, $tableLegends)) continue;
            
            if (false == isset($page[$lineCounter])) $page[$lineCounter] = [];

            if (preg_match('/^(\d+)º$/', $ptoken, $matches))
                $page[$lineCounter]['classificacao'] = intval($matches[1]);
            else if (preg_match('/^(\d{10})$/', $ptoken, $matches))
                $page[$lineCounter]['inscricao'] = $matches[1];
            else if (preg_match('/^(\d{2}\/\d{2}\/\d{4})$/', $ptoken, $matches))
                $page[$lineCounter]['data_nascimento'] = $matches[1];
            else if (preg_match('/^([A-ZÁ-ÚÇ\\\' ]+)$/', $ptoken, $matches))
                $page[$lineCounter]['nome'] = $matches[1];
            else if (strpos($ptoken, ',') !== false || (is_numeric($ptoken) && false === isset($page[$lineCounter]['nota'])))
                $page[$lineCounter]['nota'] = floatval(str_replace(',', '.', $ptoken));
            else
                $page[$lineCounter]['desconhecido'] = $ptoken;

            $lineCounter++;
        }
    }

    $data = array_merge($data, $page);
}
header('Content-type: application/json');
file_put_contents($outputFile, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode($data, JSON_PRETTY_PRINT);