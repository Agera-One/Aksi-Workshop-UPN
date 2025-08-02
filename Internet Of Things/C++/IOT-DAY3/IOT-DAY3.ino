// #include <ESP8266WiFi.h>
// #include <WiFiClient.h>


// // ==== WiFi Configuration ====
// const char* ssid = "Lab Sistem Informasi";
// const char* password = "b3l4n3g4r4";


// // ==== HC-SR04 Configuration ====
// #define TRIG_PIN D5
// #define ECHO_PIN D6
// #define BUZZER_PIN D2
// #define LED_STATUS D1


// WiFiServer server(80);


// float bacaJarakCM() {
//   digitalWrite(TRIG_PIN, LOW);
//   delayMicroseconds(2);
//   digitalWrite(TRIG_PIN, HIGH);
//   delayMicroseconds(10);
//   digitalWrite(TRIG_PIN, LOW);
//   long duration = pulseIn(ECHO_PIN, HIGH);
//   return duration * 0.034 / 2;
// }


// void setup() {
//   Serial.begin(115200);
//   pinMode(TRIG_PIN, OUTPUT);
//   pinMode(ECHO_PIN, INPUT);
//   pinMode(LED_STATUS, OUTPUT);
//   pinMode(BUZZER_PIN, OUTPUT);


//   WiFi.begin(ssid, password);
//   Serial.println("Menghubungkan WiFi...");


//   while (WiFi.status() != WL_CONNECTED) {
//     delay(1000);
//     Serial.print(".");
//   }


//   Serial.println("\nWiFi Tersambung!");
//   server.begin();
// }


// void loop() {
//   int jarak = bacaJarakCM();
//   Serial.print("Jarak: ");
//   Serial.println(jarak);


//   if (jarak < 10) {
//     digitalWrite(LED_STATUS, HIGH);
//     digitalWrite(BUZZER_PIN, HIGH);
//   } else {
//     digitalWrite(LED_STATUS, LOW);
//     digitalWrite(BUZZER_PIN, LOW);
//   }


//   WiFiClient client = server.accept();
//   if (client) {
//     String req = client.readStringUntil('\n');
//     req.trim();


//     if (req.indexOf("/data") != -1) {
//       String json = "{\"jarak\":" + String(jarak) + "}";
//       client.println("HTTP/1.1 200 OK");
//       client.println("Content-Type: application/json");
//       client.println("Access-Control-Allow-Origin: *");
//       client.println("Connection: close");
//       client.println("Content-Length: " + String(json.length()));
//       client.println();
//       client.println(json);
//       delay(5);
//       client.stop();
//       return;
//     }


//     client.println("HTTP/1.1 200 OK");
//     client.println("Content-Type: text/html");
//     client.println("Connection: close");
//     client.println();
//     client.println(R"=====(<!DOCTYPE html>
// <html>
// <head>
//   <title>Monitoring Jarak</title>
//   <style>
//     body { font-family: sans-serif; text-align: center; margin-top: 30px; }
//     .box { padding: 20px; border: 1px solid #ccc; display: inline-block; }
//   </style>
//   <script>
//     function fetchData() {
//       fetch('/data')
//         .then(response => response.json())
//         .then(data => {
//           document.getElementById("jarak").innerText = data.jarak + " cm";
//           if (data.jarak < 10) {
//             document.getElementById("notifikasi").innerText = "baik-baik saja";
//             document.getElementById("notifikasi").style.color = "green";
//           } else {
//             document.getElementById("notifikasi").innerText = "helppp ada copet";
//             document.getElementById("notifikasi").style.color = "red";
//           }
//         });
//     }
//     setInterval(fetchData, 2000);
//     window.onload = fetchData;
//   </script>
// </head>
// <body>
//   <h1>Monitoring Jarak HC-SR04</h1>
//   <div class="box">
//     <h2>Jarak: <span id="jarak">-</span></h2>
//     <h3><span id="notifikasi"></span></h3>
//   </div>
// </body>
// </html>)=====");


//     client.stop();
//   }


//   delay(100);
// }


// Gabungan Kontrol LED dan Monitoring Jarak
#include <ESP8266WiFi.h>
#include <WiFiClient.h>


// ==== WiFi Configuration ====
const char* ssid = "Lab Sistem Informasi";
const char* password = "b3l4n3g4r4";


// ==== HC-SR04 Configuration ====
#define TRIG_PIN D5  // Pin TRIG HC-SR04
#define ECHO_PIN D6  // Pin ECHO HC-SR04


// ==== Output ====
#define BUZZER_PIN D2     // GPIO4
#define LED_STATUS D1     // GPIO5
#define LED_CONTROL D4    // GPIO2


WiFiServer server(80);


float distance_cm = 0;


float bacaJarakCM() {
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);


  long duration = pulseIn(ECHO_PIN, HIGH);
  float distance = duration * 0.034 / 2;  // cm


  return distance;
}


void setup() {
  Serial.begin(115200);
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);


  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(LED_STATUS, OUTPUT);
  pinMode(LED_CONTROL, OUTPUT);


  WiFi.begin(ssid, password);
  Serial.print("Menghubungkan ke ");
  Serial.println(ssid);


  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }


  Serial.println("\nWiFi Tersambung!");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());


  server.begin();
}


void loop() {
  distance_cm = bacaJarakCM();
  Serial.print("Jarak: ");
  Serial.print(distance_cm);
  Serial.println(" cm");


  // LED dan buzzer ON jika jarak < 20 cm
  if (distance_cm < 10) {
    digitalWrite(LED_STATUS, HIGH);
    digitalWrite(BUZZER_PIN, LOW);
  } else {
    digitalWrite(LED_STATUS, LOW);
    digitalWrite(BUZZER_PIN, HIGH);
  }


  WiFiClient client = server.accept();
  if (client) {
    Serial.println("Client Terhubung!");
    String req = client.readStringUntil('\n');
    req.trim();
    Serial.println("Permintaan: " + req);

    // NORMAL CONDITION
    // if (req.indexOf("/led/on") != -1) digitalWrite(LED_CONTROL, HIGH);
    // if (req.indexOf("/led/off") != -1) digitalWrite(LED_CONTROL, LOW);

    // OPPOSITE CONDITION
    if (req.indexOf("/led/on") != -1) digitalWrite(LED_CONTROL, LOW);
    if (req.indexOf("/led/off") != -1) digitalWrite(LED_CONTROL, HIGH);


    if (req.indexOf("/data") != -1) {
      String json = "{\"jarak\":" + String(distance_cm) + "}";


      client.println("HTTP/1.1 200 OK");
      client.println("Content-Type: application/json");
      client.println("Connection: close");
      client.println("Access-Control-Allow-Origin: *");
      client.println("Content-Length: " + String(json.length()));
      client.println();
      client.println(json);


      delay(5);
      client.stop();
      return;
    }


    // HTML Page
    client.println("HTTP/1.1 200 OK");
    client.println("Content-Type: text/html");
    client.println("Connection: close");
    client.println();
    client.println(R"=====(<!DOCTYPE html>
<html>
<head>
  <title>Monitoring Jarak HC-SR04</title>
  <style>
    body { font-family: sans-serif; text-align: center; margin-top: 30px; }
    .box { border: 1px solid #ccc; padding: 20px; display: inline-block; }
    button { padding: 10px 20px; font-size: 16px; margin: 5px; border-radius: 10px;}
  </style>
  <script>
    function fetchData() {
      fetch('/data')
        .then(response => response.json())
        .then(data => {
          document.getElementById("jarak").innerText = data.jarak + " cm";
          if (data.jarak < 10) {
            document.getElementById("notifikasi").innerText = "SANTAI DULU KAWAN";
            document.getElementById("notifikasi").style.color = "green";
          } else {
            document.getElementById("notifikasi").innerText = "WOI ADA COPET";
            document.getElementById("notifikasi").style.color = "red";
          }
        });
    }
    setInterval(fetchData, 2000);
    window.onload = fetchData;
  </script>
</head>
<body>
  <h1>Monitoring Jarak HC-SR04</h1>
  <div class="box">
    <h2>Jarak: <span id="jarak">-</span></h2>
    <h3><span id="notifikasi"></span></h3>
  </div>


  <h3>Kontrol LED D4</h3>
  <a href="/led/on"><button style="background:green;color:white;">LED ON</button></a>
  <a href="/led/off"><button style="background:red;color:white;">LED OFF</button></a>
</body>
</html>)=====");


    client.stop();
    Serial.println("Client Disconnected.");
  }


  delay(100);
}

