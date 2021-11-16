#include <WiFi.h>
#include <WiFiMulti.h>
#include <HTTPClient.h>
 #include "esp_camera.h"
#include "Arduino.h"
//#include "FS.h"                // SD Card ESP32
//#include "SD_MMC.h"            // SD Card ESP32
#include "soc/soc.h"// Disable brownour problems
#include "soc/rtc_cntl_reg.h"  // Disable brownour problems
#include "driver/rtc_io.h"
#include <NTPClient.h>
#include <WiFiUdp.h>
// define the number of bytes you want to access
#define EEPROM_SIZE 1           
// Pin definition for CAMERA_MODEL_AI_THINKER
#define PWDN_GPIO_NUM     32
#define RESET_GPIO_NUM    -1
#define XCLK_GPIO_NUM      0
#define SIOD_GPIO_NUM     26
#define SIOC_GPIO_NUM     27
#define Y9_GPIO_NUM       35
#define Y8_GPIO_NUM       34
#define Y7_GPIO_NUM       39
#define Y6_GPIO_NUM       36
#define Y5_GPIO_NUM       21
#define Y4_GPIO_NUM       19
#define Y3_GPIO_NUM       18
#define Y2_GPIO_NUM        5
#define VSYNC_GPIO_NUM    25
#define HREF_GPIO_NUM     23
#define PCLK_GPIO_NUM     22
const char* ssid = "Hoai Nam_plus";
const char* password = "31081977";
String mhs;
String serverName = "hoc9choi1.000webhostapp.com";
String serverPath = "/upload.php";
const int serverPort = 80;
WiFiClient client;
bool bol;
void setup() {
  WRITE_PERI_REG(RTC_CNTL_BROWN_OUT_REG, 0);
  Serial.begin(9600);
  WiFi.begin(ssid,password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(100);}
  camera_config_t config;
  config.ledc_channel = LEDC_CHANNEL_0;
  config.ledc_timer = LEDC_TIMER_0;
  config.pin_d0 = Y2_GPIO_NUM;
  config.pin_d1 = Y3_GPIO_NUM;
  config.pin_d2 = Y4_GPIO_NUM;
  config.pin_d3 = Y5_GPIO_NUM;
  config.pin_d4 = Y6_GPIO_NUM;
  config.pin_d5 = Y7_GPIO_NUM;
  config.pin_d6 = Y8_GPIO_NUM;
  config.pin_d7 = Y9_GPIO_NUM;
  config.pin_xclk = XCLK_GPIO_NUM;
  config.pin_pclk = PCLK_GPIO_NUM;
  config.pin_vsync = VSYNC_GPIO_NUM;
  config.pin_href = HREF_GPIO_NUM;
  config.pin_sscb_sda = SIOD_GPIO_NUM;
  config.pin_sscb_scl = SIOC_GPIO_NUM;
  config.pin_pwdn = PWDN_GPIO_NUM;
  config.pin_reset = RESET_GPIO_NUM;
  config.xclk_freq_hz = 20000000;
  config.pixel_format = PIXFORMAT_JPEG; 
    if(psramFound()){
    config.frame_size = FRAMESIZE_VGA; // FRAMESIZE_ + QVGA|CIF|VGA|SVGA|XGA|SXGA|UXGA
    config.jpeg_quality = 15;
    config.fb_count = 2;
  } else {
    config.frame_size = FRAMESIZE_CIF;
    config.jpeg_quality = 15;
    config.fb_count = 1;
  }
  esp_err_t err = esp_camera_init(&config);
  if (err != ESP_OK) {
    Serial.printf("Camera init failed with error 0x%x", err);
    return;
  }
}

void loop() {
   String s = DocTT();
   s.trim();
   String payload = "false";
   String url = "http://hoc9choi1.000webhostapp.com/"+s;
   Serial.println(url);
   if(WiFi.status()== WL_CONNECTED){
      HTTPClient http;
      String serverPath = url + s;
      http.begin(serverPath.c_str());
      int httpResponseCode = http.GET();
      if (httpResponseCode>0) {
        payload = http.getString();
        payload.trim();
      }
      http.end();
    }
  if (payload != "false"){
    if (sendpic(payload)){
      Serial.println("true");                                                                                                   
    }else Serial.println("false");
  }else Serial.println("false");
}
String DocTT(){
  String a="";
  mhs = "";
  do{
    if (Serial.available()>0){
      mhs = Serial.readString();
      mhs.trim();
    }
  }while(mhs.charAt(0)!='A');
  for(int i = 0; i<= mhs.length()-1;i++){
    a +=  mhs.charAt(i+1);
  }
  return "d.php?codeid="+a; 
}
/*String gettime(){
   while(!timeClient.update()) {
     timeClient.forceUpdate();
  }
  
  return xulytime(timeClient.getFormattedDate());
}
String xulytime(String a){
  int i=0;int n=19;
  String b;
  while(i<=n){
    if(a.charAt(i)==':'){
      b+='-';
    }else{b+=a.charAt(i);}
    i++;
  }
  return b;
}*/
bool sendpic(String payload) {
  String pay ="false";
  camera_fb_t * fb = NULL;
  fb = esp_camera_fb_get();
  if(!fb) {
   return false;
  }
  mhs = payload;
  if (client.connect(serverName.c_str(), serverPort)) { 
    String head = "--RandomNerdTutorials\r\nContent-Disposition: form-data; name=\"imageFile\"; filename=\""+mhs+".jpg\"\r\nContent-Type: image/jpeg\r\n\r\n";
    String tail = "\r\n--RandomNerdTutorials--\r\n";

    uint32_t imageLen = fb->len;
    uint32_t extraLen = head.length() + tail.length();
    uint32_t totalLen = imageLen + extraLen;
  
    client.println("POST " + serverPath + " HTTP/1.1");
    client.println("Host: " + serverName);
    client.println("Content-Length: " + String(totalLen));
    client.println("Content-Type: multipart/form-data; boundary=RandomNerdTutorials");
    client.println();
    client.print(head);
  
    uint8_t *fbBuf = fb->buf;
    size_t fbLen = fb->len;
    for (size_t n=0; n<fbLen; n=n+1024) {
      if (n+1024 < fbLen) {
        client.write(fbBuf, 1024);
        fbBuf += 1024;
      }
      else if (fbLen%1024>0) {
        size_t remainder = fbLen%1024;
        client.write(fbBuf, remainder);
      }
    }   
    client.print(tail);
    delay(2000);
    esp_camera_fb_return(fb);
    client.stop();
  }
  return true;
}
/*bool saveandcappic(){
  mhs = mhs +"-" + gettime(); 
  camera_fb_t * fb = NULL;
  esp_err_t res = ESP_OK;
  fb = esp_camera_fb_get();  
  if(!fb) {
    Serial.println("Camera capture failed");
    return false;
  }
  digitalWrite(4,LOW);
  String path = "/" + mhs +".jpg";
  fs::FS &fs = SD_MMC; 

  
  File file = fs.open(path.c_str(), FILE_WRITE);
  if(!file){
  } 
  else {
    file.write(fb->buf, fb->len); // payload (image), payload length
  }
  file.close();
  esp_camera_fb_return(fb); 
  return true;
}*/
