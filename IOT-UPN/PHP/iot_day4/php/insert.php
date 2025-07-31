<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$koneksi = new mysqli("localhost", "root", "", "monitoring_iot");

if ($koneksi->connect_error) {
  die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $koneksi->connect_error]));
}

if (isset($_POST['jarak'])) {
  // Bersihkan input untuk mencegah SQL injection
  $jarak = $koneksi->real_escape_string($_POST['jarak']);
 
  $sql = "INSERT INTO data_sensor (jarak_cm) VALUES ('$jarak')";
 
  if ($koneksi->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Data berhasil disimpan"]);
  } else {
    echo json_encode(["status" => "error", "message" => "Gagal: " . $koneksi->error]);
  }
} else {
  echo json_encode(["status" => "error", "message" => "Parameter 'jarak' tidak diterima"]);
}

$koneksi->close();
?>
