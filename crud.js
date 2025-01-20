$(document).ready(function () {
    // Carregar estados da API IBGE
    $.getJSON('https://servicodados.ibge.gov.br/api/v1/localidades/estados', function (states) {
        states.sort((a, b) => a.nome.localeCompare(b.nome));
        states.forEach(state => {
            $('#state, #filterState').append(`<option value="${state.sigla}">${state.nome}</option>`);
        });
    });

    // Carregar cidades com base no estado selecionado (Formulário e Filtros)
    $('#state, #filterState').change(function () {
        const uf = $(this).val();
        const targetCityDropdown = $(this).is('#state') ? '#city' : '#filterCity';
        $(targetCityDropdown).html('<option value="">Selecione uma cidade</option>');

        if (uf) {
            $.getJSON(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`, function (cities) {
                cities.sort((a, b) => a.nome.localeCompare(b.nome));
                cities.forEach(city => {
                    $(targetCityDropdown).append(`<option value="${city.nome}">${city.nome}</option>`);
                });
            });
        }
    });

    // Filtrar dados da tabela e do gráfico
    $('#filterCity').change(function () {
        const selectedState = $('#filterState').val();
        const selectedCity = $('#filterCity').val();

        // Atualizar tabela
        $.getJSON('data.json', function (data) {
            const filteredData = data.filter(record =>
                (!selectedState || record.state === selectedState) &&
                (!selectedCity || record.city === selectedCity)
            );

            const tableBody = $('#temperatureTable tbody');
            tableBody.empty();
            filteredData.forEach(record => {
                tableBody.append(`
                    <tr class="padbord" style="text-align:center;">
                        <td class="padbord">${record.state}</td>
                        <td class="padbord">${record.city}</td>
                        <td class="padbord">${record.date}</td>
                        <td class="padbord">${record.max_temp}</td>
                        <td class="padbord">${record.min_temp}</td>
                        <td class="padbord">
                            <form method="POST" action="delete.php" style="display:inline;">
                                <input type="hidden" name="index" value="${data.indexOf(record)}">
                                <button type="submit" name="deleteTemperature">Excluir</button>
                            </form>
                        </td>
                    </tr>
                `);
            });

            // Atualizar gráfico
            const dates = filteredData.map(record => record.date);
            const maxTemps = filteredData.map(record => record.max_temp);
            const minTemps = filteredData.map(record => record.min_temp);

            chart.data.labels = dates;
            chart.data.datasets[0].data = maxTemps;
            chart.data.datasets[1].data = minTemps;
            chart.update();
        });
    });

    // Configuração do gráfico
    const ctx = document.getElementById('temperatureChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Temperatura Máxima',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    data: []
                },
                {
                    label: 'Temperatura Mínima',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    data: []
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Dias'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Temperaturas (°C)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
});
