<?php
$conn = new mysqli("localhost", "root", "", "monitoring_iot");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

setlocale(LC_TIME, 'id_ID.utf8');
date_default_timezone_set("Asia/Jakarta");

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM data_sensor ORDER BY waktu DESC LIMIT $start, $limit";
$result = $conn->query($sql);

$totalQuery = $conn->query("SELECT COUNT(*) as total FROM data_sensor");
$totalData = $totalQuery->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

$chartQuery = $conn->query("SELECT * FROM data_sensor ORDER BY waktu");
$labels = [];
$dataPoints = [];

while ($row = $chartQuery->fetch_assoc()) {
  $labels[] = date("d-m-Y H:i", strtotime($row['waktu']));
  $dataPoints[] = $row['jarak_cm'];
}

$labels = array_reverse($labels);
$dataPoints = array_reverse($dataPoints);

// Cek apakah ada data lebih dari 25 cm
$showWarning = false;
$whatsappTriggered = false;
$whatsappMessage = '';
$dataRows = [];

while ($row = $result->fetch_assoc()) {
  $dataRows[] = $row;
  if (!$whatsappTriggered && $row['jarak_cm'] > 25) {
    $showWarning = true;
    $whatsappTriggered = true;
    $whatsappMessage = "⚠️ Peringatan! Jarak melebihi batas: " . $row['jarak_cm'] . " cm";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Monitoring Jarak Ultrasonik</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <div class="container">
    <header>
      <h1><i class="fas fa-ruler-vertical"></i> Monitoring Jarak Ultrasonik</h1>
      <p>Sistem Pemantauan Real-time dengan ESP8266 dan MySQL</p>
    </header>

    <?php if ($showWarning): ?>
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
          <strong>Peringatan!</strong> Jarak melebihi batas aman 25 cm. Segera periksa perangkat Anda.
        </div>
      </div>
    <?php endif; ?>

    <div class="status-card">
      <div class="status-item">
        <div class="status-label">Total Data</div>
        <div class="status-value"><?= $totalData ?></div>
        <div class="status-desc"><i class="fas fa-database"></i> Data tersimpan</div>
      </div>
      <div class="status-item">
        <div class="status-label">Jarak Terakhir</div>
        <div class="status-value"><?= $dataRows[0]['jarak_cm'] ?> cm</div>
        <div class="status-desc"><i class="fas fa-clock"></i> <?= (new DateTime($dataRows[0]['waktu']))->format('H:i') ?></div>
      </div>
      <div class="status-item">
        <div class="status-label">Status Sistem</div>
        <div class="status-value">
          <?php if ($showWarning): ?>
            <span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> Warning</span>
          <?php else: ?>
            <span style="color: var(--success);"><i class="fas fa-check-circle"></i> Normal</span>
          <?php endif; ?>
        </div>
        <div class="status-desc"><i class="fas fa-server"></i> Online</div>
      </div>
    </div>

    <div class="card">
      <h3 class="card-title"><i class="fas fa-chart-line"></i> Grafik Jarak (20 Data Terbaru)</h3>
      <canvas id="chartJarak"></canvas>
    </div>

    <div class="card">
      <h3 class="card-title"><i class="fas fa-table"></i> Data Sensor</h3>
      <div class="last-update">Terakhir diperbarui: <?= date('d M Y H:i:s') ?></div>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Jarak (cm)</th>
            <th>Waktu</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = $start + 1; ?>
          <?php foreach ($dataRows as $row): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['jarak_cm'] ?></td>
              <td><?= (new DateTime($row['waktu']))->format('d M Y H:i') ?></td>
              <td>
                <?php if ($row['jarak_cm'] > 25): ?>
                  <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Bahaya</span>
                <?php else: ?>
                  <span style="color: var(--success);"><i class="fas fa-check-circle"></i> Aman</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>"><i class="fas fa-chevron-left"></i></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $page + 1 ?>"><i class="fas fa-chevron-right"></i></a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php if ($showWarning): ?>
    <div class="toast-container">
      <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong><i class="fas fa-bell"></i> Peringatan</strong>
          <small><?= date("H:i") ?></small>
        </div>
        <div class="toast-body">
          <?= $whatsappMessage ?>
        </div>
      </div>
    </div>

    <script>
      const whatsappLink = "https://api.whatsapp.com/send?phone=6285230081586&text=<?= urlencode($whatsappMessage) ?>";
      setTimeout(() => {
        window.open(whatsappLink, "_blank");
      }, 2000);
    </script>
  <?php endif; ?>

  <script>
    const ctx = document.getElementById('chartJarak').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Jarak (cm)',
          data: <?= json_encode($dataPoints) ?>,
          borderColor: '#4361ee',
          backgroundColor: 'rgba(67, 97, 238, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: '#4361ee',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
          pointHitRadius: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              color: '#212529',
              font: {
                family: 'Poppins',
                size: 13,
                weight: '500'
              },
              padding: 20,
              boxWidth: 12,
              usePointStyle: true
            }
          },
          tooltip: {
            backgroundColor: '#212529',
            titleColor: '#ffffff',
            bodyColor: '#ffffff',
            titleFont: {
              family: 'Poppins',
              size: 13,
              weight: 'normal'
            },
            bodyFont: {
              family: 'Poppins',
              size: 12
            },
            padding: 10,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
              label: function(context) {
                return 'Jarak: ' + context.parsed.y + ' cm';
              }
            }
          },
          annotation: {
            annotations: {
              dangerLine: {
                type: 'line',
                yMin: 25,
                yMax: 25,
                borderColor: '#f72585',
                borderWidth: 2,
                borderDash: [6, 6],
                label: {
                  content: 'Batas Aman',
                  enabled: true,
                  position: 'left',
                  backgroundColor: 'rgba(247, 37, 133, 0.8)',
                  color: '#ffffff',
                  font: {
                    family: 'Poppins',
                    size: 11,
                    weight: '500'
                  },
                  padding: {
                    top: 4,
                    bottom: 4,
                    left: 8,
                    right: 8
                  },
                  borderRadius: 4
                }
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false,
              drawBorder: false
            },
            ticks: {
              color: '#6c757d',
              font: {
                family: 'Poppins',
                size: 11
              },
              maxRotation: 45,
              minRotation: 45
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)',
              drawBorder: false
            },
            ticks: {
              color: '#6c757d',
              font: {
                family: 'Poppins',
                size: 11
              },
              padding: 10
            },
            title: {
              display: true,
              text: 'Jarak (cm)',
              color: '#6c757d',
              font: {
                family: 'Poppins',
                size: 12,
                weight: '500'
              },
              padding: {
                top: 10,
                bottom: 20
              }
            }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index'
        },
        elements: {
          line: {
            cubicInterpolationMode: 'monotone'
          }
        }
      },
      plugins: [{
        id: 'customCanvasBackgroundColor',
        beforeDraw: (chart, args, options) => {
          const {
            ctx,
            chartArea: {
              left,
              top,
              width,
              height
            }
          } = chart;
          ctx.save();
          ctx.globalCompositeOperation = 'destination-over';
          ctx.fillStyle = 'white';
          ctx.fillRect(left, top, width, height);
          ctx.restore();
        }
      }]
    });
  </script>
</body>

</html>
