                                                                                                                                                                                                                       #include <Servo.h> // Thư viện động cơ Servo
#include <Adafruit_MLX90614.h> // Thư viện cảm biến nhiệt độ MLX90614
Adafruit_MLX90614 mlx = Adafruit_MLX90614();
Servo myservo; 
#include <Adafruit_Fingerprint.h>
SoftwareSerial mySerial(2, 3);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
LiquidCrystal_I2C lcd(0x27,20,4);
int vantay;
unsigned long time;
void setup(void) {
  Serial.begin(9600);
  finger.begin(57600);
  time = millis();
  myservo.attach(9);
  myservo.write(0);                
  mlx.begin();
  lcd.init();
  lcd.backlight();
}
void loop(){
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Tiep Theo...");
  int vantay = getFingerprintIDez();
  if (vantay==-1){
    return;
  }
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Do nhiet do");
  while ((digitalRead(4)==1)and(millis()-time<5000)){}
  delay(500);
  double t = double(mlx.readObjectTempC());
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("t* = "+String(t));
  lcd.setCursor(0,1);
  lcd.print("Van tay: "+String(vantay));
  if ((t>=34.5)and(t<=37.1)){
   if(SendTT("A"+String(vantay)+"&nhietdo="+String(t))=="true"){
      Cua(true);
      time = millis();
      while((digitalRead(7)==1)and(millis()-time>3000)){}
      Cua(false);
    }else{ 
         lcd.clear();
         lcd.setCursor(1,0);
         lcd.print("Thất bại");
         delay(2000);
       }
  }else{
    lcd.clear();
      lcd.setCursor(0,0);
      lcd.print("Nhiet do false");     
      delay(2000);
  }
}
int getFingerprintIDez() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK)  return -1;
  return finger.fingerID;
}
String SendTT(String giatri){
  giatri.trim();
  delay(200);
  Serial.println(giatri);
  String mhs;
  do{
    if (Serial.available()>0){
      mhs = Serial.readString();
      mhs.trim();
    }
  }while((mhs!="false")and(mhs!="true"));
  lcd.clear();
  lcd.setCursor(1,0);
  lcd.print("-"+mhs);
  delay(2000);
  return mhs;
}
void Cua(bool a){ // Mở đóng cửa;
  if(a){
    lcd.clear();
      lcd.setCursor(0,0);
      lcd.print("Mo Cua...");
    myservo.write(135);
  }
  else{
    lcd.clear();
      lcd.setCursor(0,1);
      lcd.print("...Dong Cua...");
    myservo.write(0);
  }
}
