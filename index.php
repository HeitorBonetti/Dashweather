<?php
// Carregar dados do JSON
$dataFile = 'data.json';
$data = json_decode(file_get_contents($dataFile), true) ?? [];

// Paginação
$itemsPerPage = 5;
$totalItems = count($data);
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));
$offset = ($currentPage - 1) * $itemsPerPage;

// Dados para a página atual
$pagedData = array_slice($data, $offset, $itemsPerPage);
?>
<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashweather</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<div class="container-fluid">
    <div class="row">
<div class="col-md-6">


    <h1 style="text-align: center;">Inserir Dados</h1>

    <!-- Formulário de Adição -->
    <form method="POST" action="save.php">
        <label for="state">Estado</label>
        <br>
        <select id="state" class="sizefix" name="state" required>
            <option value="">Selecione um estado</option>
        </select>
<br><br>
        <label for="city">Cidade</label>
        <br>
        <select id="city" class="sizefix" name="city" required>
            <option value="">Selecione uma cidade</option>
        </select>
        <br><br>
        <label for="date">Data</label>
        <br>
        <input type="date" class="sizefix" name="date" id="date" required>
        <br><br>
        <label for="min_temp">Temperatura Mínima</label>
        <br>
        <input type="number " class="sizefix" name="min_temp" id="min_temp" required>
        <br><br>
        <label for="max_temp">Temperatura Máxima</label>
        <br>
        <input type="number" class="sizefix" name="max_temp" id="max_temp" required>
        <br><br>
        <button type="submit" class="sizefix2" name="addTemperature">Adicionar</button>
    </form>

        <!-- Tabela de Temperaturas -->

<br>

        <!-- Filtros -->
        <h2 style="text-align: center;">Filtrar Dados</h2>
    <label for="filterState"><h5>Estado</h5></label>
    <select id="filterState" class="sizefix">
        <option value="">Selecionar</option>
    </select>
    <br> <br>
    <label for="filterCity"><h5>Cidade</h5></label>
    <select id="filterCity" class="sizefix">
        <option value="">Selecionar</option>
    </select>

</div>


<div class="col-md-6">



    <!-- Gráfico -->
    <h1 style="text-align: center;">Gráfico de Temperaturas</h1>
    <canvas id="temperatureChart"></canvas>
    <h2 style="text-align: center;" >Dados Registrados</h2>
    <table id="temperatureTable">
        <thead>
        <tr style="text-align:center; border: 1px solid black;">
                <th class="padbord">Estado</th>
                <th class="padbord">Cidade</th>
                <th class="padbord">Data</th>
                <th class="padbord">Máxima</th>
                <th class="padbord">Mínima</th>
                <th class="padbord">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagedData as $index => $record): ?>
                <tr style="text-align:center;">
                    <td class="padbord"><?= htmlspecialchars($record['state']) ?></td>
                    <td class="padbord"><?= htmlspecialchars($record['city']) ?></td>
                    <td class="padbord"><?= htmlspecialchars($record['date']) ?></td>
                    <td class="padbord"><?= htmlspecialchars($record['max_temp']) ?></td>
                    <td class="padbord"><?= htmlspecialchars($record['min_temp']) ?></td>
                    <td class="padbord">
                        <form method="POST" action="delete.php" style="display:inline;">
                            <input type="hidden" name="index" value="<?= $offset + $index ?>">
                            <button type="submit" name="deleteTemperature">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?>">&laquo; </a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" <?= $i === $currentPage ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= $currentPage + 1 ?>"> &raquo;</a>
        <?php endif; ?>
    </div>
        </div>
        </s>
        
    </div>
    <script src="crud.js"></script>

    
</body>
</html>
