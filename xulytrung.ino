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
void setup(void) {
  Serial.begin(9600);
  finger.begin(57600);
  myservo.attach(9);
  myservo.write(0);                
  mlx.begin();
  lcd.init();
  lcd.backlight();
}
void loop(){
  lcd.setCursor(0,0);
  lcd.print("Tiep Theo...");
  int vantay = getFingerprintIDez();
  if (vantay==-1){
    return;
  }
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Do nhiet do");
  while (digitalRead(4)==1){
  }
  delay(500);
  double t = double(mlx.readObjectTempC());
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("t* = "+String(t));
  lcd.setCursor(0,1);
  lcd.print("Van tay: "+String(vantay));
  if ((t>=34.5)and(t<=37.1)){
   if(SendTT("B"+String(vantay)+"&nhietdo="+String(t))=="true"){
      Serial.println("Diem danh thanh cong");
      Cua(true);
      int i=0;
      while((digitalRead(7)==1)or(i>1000000000000000)){i=i+1;}
      Cua(false);
    }else{ 
       Serial.println("Diem Danh That Bai");
       lcd.clear();
       lcd.setCursor(0,0);
       lcd.print("That bai"+String(vantay));
       }
  }else{
    lcd.clear();
      lcd.setCursor(0,0);
      lcd.print("Nhiet do fals");
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
  delay(2000);
  Serial.println(giatri);
  String a="";
  String mhs;
  bool k = true;
  mhs  = Serial.readString();
  while ((mhs.charAt(0)!='t')and(mhs.charAt(0)!='f')){
    mhs = Serial.readString(); 
  }
  mhs.trim(); 
  Serial.println("Thông tin gửi: "+String(giatri));
  Serial.println("Gửi thông tin "+mhs);
  delay(100);
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
      lcd.setCursor(0,0);
      lcd.print("Dong Cua...");
    myservo.write(0);
  }
}
