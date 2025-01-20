<?php
// Carregar dados do JSON
$dataFile = 'data.json';
$data = json_decode(file_get_contents($dataFile), true) ?? [];

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['state'], $_POST['city'], $_POST['date'], $_POST['max_temp'], $_POST['min_temp'])) {
    $newRecord = [
        'state' => htmlspecialchars($_POST['state']),
        'city' => htmlspecialchars($_POST['city']),
        'date' => htmlspecialchars($_POST['date']),
        'max_temp' => (float)$_POST['max_temp'],
        'min_temp' => (float)$_POST['min_temp'],
    ];

    // Adicionar novo registro ao array de dados
    $data[] = $newRecord;

    // Salvar os dados no arquivo JSON
    file_put_contents($dataFile, json_encode($data));

    // Redirecionar de volta para a página principal
    header('Location: index.php');
    exit;
}

// Caso algo esteja errado, redirecionar para a página principal
header('Location: index.php');
exit;
