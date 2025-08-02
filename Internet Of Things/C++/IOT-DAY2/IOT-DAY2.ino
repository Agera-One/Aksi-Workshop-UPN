#define TRIG_PIN D6
#define ECHO_PIN D5
#define BUZZER_PIN D2
#define LED_PIN  D1

long duration; // mengukur durasi tangkapan sensor
int distance; // satuan 


void setup() {
  pinMode(TRIG_PIN, OUTPUT); // mengeluarkan gelombang
  pinMode(ECHO_PIN, INPUT); // menangkap gelombang
  pinMode(BUZZER_PIN, OUTPUT); // menangkap gelombang
  pinMode(LED_PIN, OUTPUT); // menangkap gelombang
  Serial.begin(115200); // mengaktifkan komunikasi serial (lewat USB) antara Arduino dan komputer Anda, dengan kecepatan 115.200 bit per detik.
}


void loop() {
  digitalWrite(TRIG_PIN, LOW); // OFF
  delayMicroseconds(2); // delay selama 2 mirosecond
  digitalWrite(TRIG_PIN, HIGH); // ON
  delayMicroseconds(10); // delay selama 10 mirosecond
  digitalWrite(TRIG_PIN, LOW); // OFF


  duration = pulseIn(ECHO_PIN, HIGH); // 
  distance = duration * 0.034 / 2; //


  Serial.print("Jarak: ");
  Serial.print(distance);
  Serial.println(" cm");


  if (distance <= 10) {
    buzzerMati();
    LedMati();;
  } else {
    buzzerMatiNyala();
    ledMatiNyala();
  }

  delay(100);
}



void buzzerMati() {
  digitalWrite(BUZZER_PIN, LOW);
}

void LedMati() {
  digitalWrite(LED_PIN, LOW);
}

void buzzerNyala() {
  digitalWrite(BUZZER_PIN, HIGH);
}

void ledNyala() {
  digitalWrite(LED_PIN, HIGH);
}

void buzzerMatiNyala() {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(50);
  digitalWrite(BUZZER_PIN, LOW);
}

void ledMatiNyala() {
  digitalWrite(LED_PIN, HIGH);
  delay(50);
  digitalWrite(LED_PIN, LOW);
}

// if (distance <= 10) {
//   digitalWrite(BUZZER_PIN, LOW);
//   digitalWrite(LED_PIN, LOW);
//   delay(50);
//   digitalWrite(BUZZER_PIN, HIGH); // Buzzer mati
//   digitalWrite(LED_PIN, HIGH); // Buzzer menyala
// } else if (distance >= 10 && distance <= 20) {
//   digitalWrite(BUZZER_PIN, LOW);
//   digitalWrite(LED_PIN, LOW);
//   delay(500);
//   digitalWrite(BUZZER_PIN, HIGH); // Buzzer mati
//   digitalWrite(LED_PIN, HIGH);
// } else {
//   digitalWrite(BUZZER_PIN, LOW); // Buzzer mati
//   digitalWrite(LED_PIN, LOW);
// }
