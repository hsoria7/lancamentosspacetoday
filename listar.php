<?php
require_once 'conexao.php'; 

// Chama a função getConexao para obter a conexão com o banco de dados
$connection = getConexao();

// Consulta para listar os dados da tabela
$sql = "SELECT Nome_live, Foguete, Data, Partiu, Scrub, Explodiu FROM `lancamentos`;";
$result = $connection->query($sql);

// Consulta para somar os totais das colunas
$sqlTotais = "
    SELECT 
        COUNT(CASE WHEN Partiu = 'Sim' THEN 1 END) AS total_partiu,
        COUNT(CASE WHEN Scrub = 'Sim' THEN 1 END) AS total_scrub,
        COUNT(CASE WHEN Explodiu = 'Sim' THEN 1 END) AS total_explodiu
    FROM `lancamentos`;
";
$resultTotais = $connection->query($sqlTotais);

if ($resultTotais->num_rows > 0) {
    $totais = $resultTotais->fetch_assoc();
    $totalPartiu = $totais['total_partiu'] ?? 0;
    $totalScrub = $totais['total_scrub'] ?? 0;
    $totalExplodiu = $totais['total_explodiu'] ?? 0;
} else {
    $totalPartiu = $totalScrub = $totalExplodiu = 0;
}

// Fecha a conexão com o banco de dados
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela Lançamentos Today</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 60px; /* Aumenta a margem inferior para separar da tabela */
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        table th {
            background-color:rgb(172, 176, 182);
            text-align: left;
        }

        canvas {
            display: block;
            margin: 0 auto;
            width: 1000px; /* ajuste a largura desejada */
            height: 700px; /* ajuste a altura desejada */
        }

        .titulo-centralizado {
            text-align: center; /* Centraliza o título */
            font-size: 24px; /* Aumenta o tamanho da fonte do título */
        }
    </style>
</head>
<body>
    <h1>Tabela Lançamentos Space Today</h1>

    <!-- Tabela de dados -->
    <table>
        <thead>
            <tr>
                <th>Nome_live</th>
                <th>Foguete</th>
                <th>Data</th>
                <th>Partiu</th>
                <th>Scrub</th>
                <th>Explodiu</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Nome_live']) ?></td>
                        <td><?= htmlspecialchars($row['Foguete']) ?></td>
                        <td><?= htmlspecialchars($row['Data']) ?></td>
                        <td><?= $row['Partiu'] ?></td>
                        <td><?= $row['Scrub'] ?></td>
                        <td><?= $row['Explodiu'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum dado encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Gráfico -->
    <h2 class="titulo-centralizado">Status dos Lançamentos</h2>
    <canvas id="grafico"></canvas>

    <script>
        // Dados para o gráfico
        const data = {
            labels: ['Partiu', 'Scrub', 'Explodiu'],
            datasets: [{
                label: 'Total',
                data: [<?= $totalPartiu ?>, <?= $totalScrub ?>, <?= $totalExplodiu ?>],
                backgroundColor: ['#00ff00', '#ffff00', '#ff0000'],
                borderColor: ['#00ff00', '#ffff00', '#ff0000'],
                borderWidth: 1
            }]
        };

        // Configuração do gráfico
        const config = {
            type: 'bar', // Tipo de gráfico (barra)
            data: data,
            options: {
                plugins: {
                    legend: { display: false } // Remove a legenda
                },
                scales: {
                    y: {
                        beginAtZero: true // Começa do zero no eixo Y
                    }
                }
            }
        };

        // Renderiza o gráfico
        const grafico = new Chart(
            document.getElementById('grafico'),
            config
        );
    </script>
</body>
</html>
