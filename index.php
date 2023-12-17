<?php

$resultadoEspcex = json_decode(file_get_contents(__DIR__ . '/data/espcex/resultado masculino.json'), true);
$classEsa = json_decode(file_get_contents(__DIR__ . '/data/esa/classificados masculino cota.json'), true);

foreach ($classEsa as $k => $v) {
    foreach ($resultadoEspcex as $j => $v2) {
        if ($v['nome'] == $v2['nome']) {
            $classEsa[$k]['espcex'] = $v2;
        }
    }
}
ob_start();
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classificados ESA Geral Masculino Cota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container mb-2">
        <h2 class="text-center">Classificados ESA Geral Masculino</h2>
        <div style="overflow-x: auto;">
            <span class="badge text-bg-info">* Classificação Inicial na ESA</span>
            <span class="badge text-bg-info">** Classificação Final (Retirados os aprovados na EsPCEx)</span>
            <table class="table table-bordered table-hover" style="min-width: 768px;">
                <thead>
                    <tr>
                        <th colspan="4" class="text-center">ESA</th>
                        <th colspan="3" class="text-center">EsPCEx</th>
                    </tr>
                    <tr>
                        <th>INSCRIÇÃO</th>
                        <th>NOME</th>
                        <th data-bs-toggle="tooltip" data-bs-title="Classificação Inicial na ESA">CI*</th>
                        <th data-bs-toggle="tooltip" data-bs-title="Classificação Final (Retirados os aprovados na EsPCEx)">CF**</th>
                        <th>INSCRIÇÃO</th>
                        <th data-bs-toggle="tooltip" data-bs-title="Posição na EsPCEx">Class</th>
                        <th data-bs-toggle="tooltip" data-bs-title="Posição na EsPCEx">Cota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $position = 1; ?>
                    <?php  foreach ($classEsa as $v) : ?>
                    <tr class="<?= array_key_exists('espcex', $v) ? 'table-active' : '' ?>">
                        <td><?= $v['inscricao'] ?></td>
                        <td><?= $v['nome'] ?></td>
                        <td><?= $v['classificacao'] ?>º</td>
                        <td><?= $position ?>º</td>
                        <?php if (array_key_exists('espcex', $v)) : ?>
                        <td><?= $v['espcex']['inscricao'] ?></td>
                        <td><?= $v['espcex']['classificacao'] ?>º</td>
                        <td><?= $v['espcex']['cotista'] ? 'Sim' : 'Não' ?></td>
                        <?php else : ?>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php $position++; ?>
                        <?php endif; ?>
                    </tr>
                    <?php  endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        // Initialize Tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents(__DIR__ . '/estatisticas/area-geral-masculino-cota.html', $html);
echo $html;
?>