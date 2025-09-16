<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Custom System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif; 
            background-color: #2f3640;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4, h5, h6, p, li {
            font-family: 'Cairo', sans-serif; 
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-around;
            margin: 20px;
        }

        .card {
            width: 30%; 
            background-color: #f1f2f6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s;
            margin-bottom: 20px;
        }

        .charts {
            display: flex;
            flex-wrap: nowrap;
            gap: 20px;
            justify-content: center;
            margin: 20px;
        }

        .chart-container {
            width: 100%;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-card {
            width: 48%;
            background-color: #f1f2f6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-10px);
        }
        h2.chart-title {
            font-size: 1.2rem; 
            font-weight: bold;
            color: #333;
            text-align: right;
        }


        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .bg-success { background-color: #27ae60; }
        .bg-primary { background-color: #2980b9; }
        .bg-pink { background-color: #e84393; }
        .bg-indigo { background-color: #8e44ad; }
        .bg-orange { background-color: #e67e22; }
        .bg-teal { background-color: #1abc9c; }
        .bg-gray { background-color: #7f8c8d; }
        .bg-yellow { background-color: #f39c12; }

        .card-content h5, .card-content p {
            margin: 0;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        #map {
            height: 300px;
            border-radius: 8px;
        }
        .info-box {
    background-color: #f1f2f6;
    border-radius: 5px; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    padding: 20px;
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
  }

  .info-box-icon {
    background-color: #6c757d; 
    border-radius: 50%;
    padding: 15px; 
    color: white; 
  }

  .info-box-content {
    color: #333; 
  }

  .info-box-number {
    font-size: 1.25rem; 
    font-weight: bold;
  }

  .info-box-text {
    font-size: 1rem; 
    font-weight: 500; 
  }
  .progress-group {
    margin-bottom: 15px;
}

.progress-bar {
    height: 12px;
    border-radius: 10px;
}

.text-center {
    margin-bottom: 10px;
    font-weight: bold;
    font-size: 1.2rem;
}

.float-end {
    font-weight: bold;
    font-size: 1.1rem;
}

    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Main Statistics Cards -->
        <div class="card">
            <div class="icon-box bg-success">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h5><?= Yii::t('common', 'Employee Count') ?></h5>
                <p><?= $employeeCount ?? 0; ?></p>
            </div>
        </div>
        <div class="card">
            <div class="icon-box bg-primary">
                <i class="fas fa-building"></i>
            </div>
            <div class="card-content">
                <h5><?= Yii::t('common', 'Department Count') ?></h5>
                <p><?= $departmentCount ?? 0; ?></p>
            </div>
        </div>
        <div class="card">
            <div class="icon-box bg-pink">
                <i class="fas fa-female"></i>
            </div>
            <div class="card-content">
                <h5><?= Yii::t('common', 'Female Employees') ?></h5>
                <p><?= $femaleEmployeeCount ?? 0; ?></p>
            </div>
        </div>
        <div class="card">
            <div class="icon-box bg-indigo">
                <i class="fas fa-male"></i>
            </div>
            <div class="card-content">
                <h5><?= Yii::t('common', 'Male Employees') ?></h5>
                <p><?= $maleEmployeeCount ?? 0; ?></p>
            </div>
        </div>

        <!-- Additional Statistic Cards -->
        <div class="card">
            <div class="icon-box bg-orange">
                <i class="fas fa-box"></i>
            </div>
            <div class="card-content">
                <h5><?= Yii::t('common', 'Products Sold') ?></h5>
                <p><?= $productsSold ?? 0; ?></p>
            </div>
        </div>
        <div class="card">
            <div class="icon-box bg-teal">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="card-content">
                <h5><?= Yii::t('common', 'Contracts Signed') ?></h5>
                <p><?= $contractsSigned ?? 0; ?></p>
            </div>
        </div>
       
    </div>

    <div class="charts">
        <!-- Monthly Reports Chart -->
        <div class="chart-card">
            <h2 class="chart-title"><?= Yii::t('common','Monthly Reports')?></h2>
            <div class="chart-container">
                <canvas id="monthlyReportsChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="chart-card">
            <h2 class="chart-title"><?= yii::t('common','Categories Distribution')?></h2>
            <div class="chart-container">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>
    
                        
 <!-- Info boxes -->
 <div class="row">
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-primary shadow-sm"><i class="fas fa-cog"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text"><?= yii::t('common','CPU Traffic')?></span>
                    <span class="info-box-number">
                      10
                      <small>%</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-danger shadow-sm"><i class="fas fa-thumbs-up"></i></span>

                  <div class="info-box-content">
                  <span class="info-box-text"><?= Yii::t('common','Likes')?></span>
                    <span class="info-box-number">41,410</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success shadow-sm"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('common','Sales')?></span>
                    <span class="info-box-number">760</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning shadow-sm"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= Yii::t('common','New Members')?></span>
                    <span class="info-box-number">2,000</span>
                </div>
            </div>
        </div>
    </div>

    <div class="map-container">
        <h2 class="chart-title"><?= Yii::t('common','Map')?></h2>
        <div id="map"></div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        // Monthly Reports Chart
        const ctx = document.getElementById('monthlyReportsChart').getContext('2d');
        const monthlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April'],
                datasets: [{
                    label: 'Monthly Reports',
                    data: [75, 50, 100, 90],
                    backgroundColor: '#2980b9',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#333' // Dark text for better visibility
                        }
                    },
                    x: {
                        ticks: {
                            color: '#333' // Dark text for better visibility
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#333' // Dark text for legend
                        }
                    }
                }
            }
        });

        // Doughnut Chart
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        const doughnutChart = new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Category 1', 'Category 2', 'Category 3'],
                datasets: [{
                    label: 'Categories',
                    data: [20, 50, 30],
                    backgroundColor: ['#f39c12', '#8e44ad', '#27ae60'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#333' // Dark text for legend
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                }
            }
        });

        // Leaflet Map Setup
        const map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add marker to map
        L.marker([51.5, -0.09]).addTo(map)
            .bindPopup('Hello world!')
            .openPopup();
            
    </script>
   
           
                 
           
</body>
</html>

