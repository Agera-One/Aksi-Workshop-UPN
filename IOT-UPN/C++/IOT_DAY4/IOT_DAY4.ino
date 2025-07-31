#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>


// ==== WiFi Configuration ====
const char* ssid = "Lab Sistem Informasi";
const char* password = "b3l4n3g4r4";


// ==== HC-SR04 Configuration ====
#define TRIG_PIN D5
#define ECHO_PIN D6


// ==== Buzzer & LED Configuration
#define LED_PIN D1
#define BUZZ_PIN D8


float distance_cm = 0;


unsigned long lastSendTime = 0;
const unsigned long interval = 1 * 60 * 1000; // 1 menit


void kirimKeServer(float distance) {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;


    http.begin(client, "http://192.168.27.160/iot_day4/php/insert.php"); // sesuaikan IP
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");


    String postData = "jarak=" + String(distance);


    int httpResponseCode = http.POST(postData);


    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Server Response: " + response);
    } else {
      Serial.print("Error on sending POST: ");
      Serial.println(httpResponseCode);
    }


    http.end();
  } else {
    Serial.println("WiFi not connected");
  }
}


void kirimTelegram(String pesan) {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClientSecure client;
    client.setInsecure();  // untuk HTTPS


    HTTPClient https;
    String token = "8279818738:AAG3hM9aFalHa6hx7RxcvRgWT75VPlNMY7I";  // Ganti token bot Anda
    String chat_id = "7916170002"; // Ganti chat_id Anda


    String url = "https://api.telegram.org/bot" + token + "/sendMessage";
    String postData = "chat_id=" + chat_id + "&text=" + pesan;


    https.begin(client, url);
    https.addHeader("Content-Type", "application/x-www-form-urlencoded");


    int httpCode = https.POST(postData);
    if (httpCode > 0) {
      String payload = https.getString();
      Serial.println("Telegram Sent: " + payload);
    } else {
      Serial.println("Gagal Kirim Telegram: " + String(httpCode));
    }


    https.end();
  }
}




float bacaJarakCM() {
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);


  long duration = pulseIn(ECHO_PIN, HIGH);
  float distance = duration * 0.034 / 2;


  return distance;
}


void setup() {
  Serial.begin(115200);
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);
  pinMode(LED_PIN, OUTPUT);
  pinMode(BUZZ_PIN, OUTPUT);


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
}


void loop() {
  unsigned long currentTime = millis();


  distance_cm = bacaJarakCM();


  if (!isnan(distance_cm)) {
    Serial.print("Jarak: ");
    Serial.print(distance_cm);
    Serial.println(" cm");


    if (distance_cm > 25) {
      kirimTelegram("⚠️ Jarak terdeteksi lebih dari 25 cm! (" + String(distance_cm) + " cm)");
      digitalWrite(LED_PIN, HIGH);
      delay(100);
      digitalWrite(LED_PIN, LOW);
      delay(100);
    } else {
      digitalWrite(LED_PIN, LOW);
    }
  }


  if (currentTime - lastSendTime >= interval) {
    if (!isnan(distance_cm)) {
      kirimKeServer(distance_cm);
    }
    lastSendTime = currentTime;
  }


  delay(100); // delay kecil agar tidak membebani loop
}

