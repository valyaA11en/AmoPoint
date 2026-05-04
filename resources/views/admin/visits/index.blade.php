<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Visit Statistics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
        }

        .card-title {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .card-value {
            font-size: 32px;
            font-weight: bold;
        }

        .charts {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .chart-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
        }

        .chart-card h2 {
            margin: 0 0 16px;
            font-size: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            border: 0;
            background: #111827;
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .logout-button:hover {
            background: #374151;
        }

        @media (max-width: 768px) {
            body {
                padding: 16px;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="topbar">
            <div class="header">
                <h1>Visit Statistics</h1>
                <p>Статистика посещений за последние 24 часа</p>
            </div>

            <form class="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-button" type="submit">Logout</button>
            </form>
        </div>

        <div class="cards">
            <div class="card">
                <div class="card-title">Всего посещений</div>
                <div class="card-value">{{ $statistics['total_visits'] }}</div>
            </div>

            <div class="card">
                <div class="card-title">Уникальных посетителей</div>
                <div class="card-value">{{ $statistics['unique_visitors'] }}</div>
            </div>
        </div>

        <div class="charts">
            <div class="chart-card">
                <h2>Уникальные посещения по часам</h2>
                <canvas id="visitsByHourChart"></canvas>
            </div>

            <div class="chart-card">
                <h2>Посещения по городам</h2>
                <canvas id="visitsByCityChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const visitsByHour = @json($statistics['visits_by_hour']);
        const visitsByCity = @json($statistics['visits_by_city']);

        new Chart(document.getElementById('visitsByHourChart'), {
            type: 'bar',
            data: {
                labels: visitsByHour.map((item) => item.hour),
                datasets: [
                    {
                        label: 'Уникальные посещения',
                        data: visitsByHour.map((item) => item.unique_visits),
                    },
                ],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Количество уникальных посещений',
                        },
                        ticks: {
                            precision: 0,
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Время',
                        },
                    },
                },
            },
        });

        new Chart(document.getElementById('visitsByCityChart'), {
            type: 'pie',
            data: {
                labels: visitsByCity.map((item) => item.city),
                datasets: [
                    {
                        label: 'Посещения',
                        data: visitsByCity.map((item) => item.total),
                    },
                ],
            },
            options: {
                responsive: true,
            },
        });
    </script>
</body>

</html>