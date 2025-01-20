<?php
// Carregar dados do JSON
$dataFile = 'data.json';
$data = json_decode(file_get_contents($dataFile), true) ?? [];

// Verificar se um índice foi enviado para exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $index = (int)$_POST['index'];
    
    // Verificar se o índice é válido
    if (isset($data[$index])) {
        unset($data[$index]);
        $data = array_values($data); // Reindexar o array
        file_put_contents($dataFile, json_encode($data)); // Salvar no JSON
    }
}

// Redirecionar de volta à página principal
header('Location: index.php');
exit;
