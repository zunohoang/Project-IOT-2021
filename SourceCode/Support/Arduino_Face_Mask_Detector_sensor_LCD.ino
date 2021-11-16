/*
 * Author   : Shafiq Yaacob @ MYBOTIC mybotic.com.my
 * Date     : 12/03/21
 * Project  : Arduino Face Mask Detector 
 * 
 * 
 * 10/08/21 
 * *updated esp32 cam reset function
 */
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#include <Servo.h>

#define IR_SENSOR_PIN                A1
#define SERVO_PIN                     9
#define LED_GREEN                     7
#define LED_RED                       5
#define PUSH_BUTTON_PIN               8

#define START_RUN                     'a'
#define RESTART_ESP32                 'r'
#define RESTART_SCAN                  'n'

#define CLOSE_DOOR                    180
#define OPEN_DOOR                     90
#define DOOR_LOCK                     0
#define OPEN_DOOR_TIME                4000
#define CLOSE_DOOR_TIME               1000
#define RESTART_TIME                  5000

#define ON                            0
#define OFF                           1

#define MASK_DETECT_TH                80 //in percent %
#define SCAN_ERROR_COUNT              3

#define SCAN_NO_OBJECT                 0
#define SCAN_PASS                      1
#define SCAN_ERROR                     2

LiquidCrystal_I2C lcd(0x27,16,2);  // set the LCD address to 0x27 for a 16 chars and 2 line display
Servo myServo;

unsigned short gusMask_Detect = 0;
char gszIP_Add[20];
unsigned short gusIsSensor_Detect_Bef = 0;
unsigned short gusIsSensor_Detect_Time_Bef = 0;
unsigned long glStart_Timer_LCD = 0;
unsigned long glRestart_Timer = 0;
unsigned short gusLCD_Index = 0;
unsigned short gusIsNeedDisp = 1;
unsigned short gusIsNeed_Restart = 0;
unsigned short gusIsSend_Request = 0;

void setup()
{
  char szData[30];
  unsigned short usExit = 0;
  unsigned short usData_Len = 0;
  short a = 0;
  
  Serial.begin(9600);
  
  lcd.init();                      // initialize the lcd 
  lcd.backlight();
  
  pinMode(IR_SENSOR_PIN,INPUT_PULLUP);
  pinMode(PUSH_BUTTON_PIN,INPUT_PULLUP);
  pinMode(LED_GREEN,OUTPUT);
  pinMode(LED_RED,OUTPUT);

  vLED_Control(SCAN_NO_OBJECT);
  vServo_Control(DOOR_LOCK);
  
  lcd.clear();
  lcd.print("Wifi");
  lcd.setCursor(0,1);
  lcd.print("connecting...");

  memset(szData, '\0', sizeof(szData));
  memset(gszIP_Add, '\0', sizeof(gszIP_Add));
 
  do
  { 
    usData_Len = usRead_Serial_Data(szData, sizeof(szData));
    
    if(usData_Len > 0)
    {
      for(short i=0; i<usData_Len; i++)
      {
        if(szData[i] == '#')
        {
          i++;
          while(szData[i] != ',')
          {
            gszIP_Add[a] = szData[i++];
            a++;
          }
          usExit = 1;
          break;
        }
      }
    }
    else
    {
      if(gusIsNeed_Restart == 0)
      {
        Serial.println(RESTART_ESP32);

        gusIsNeed_Restart = 1;
      }
      
      if((millis() - glRestart_Timer) > RESTART_TIME)
      {
        gusIsNeed_Restart = 0;
        glRestart_Timer = millis();
      }
    }
  }while(usExit == 0);

  vLCD_Disp_Ip(gszIP_Add);
  glStart_Timer_LCD = millis();
}

void loop()
{   
  char szData[30];
  unsigned short usExit = 0;
  unsigned short usData_Len = 0;
  unsigned short usIsNeed_Rescan = 0;
  short sMask_Percent = 0;
  
  if(digitalRead(IR_SENSOR_PIN) == ON)
  { 
    if(gusIsSensor_Detect_Bef == 0)
    {
      vDisp_Scanning(); 
      memset(szData, '\0', sizeof(szData));   
        
      do
      {
        if(digitalRead(IR_SENSOR_PIN) == ON)
        { 
          if(gusIsSend_Request == 0)
          { 
            Serial.println(START_RUN); //send request to ESP32-CAM to scan face  
            gusIsSend_Request = 1;
          }
          
          usData_Len = usRead_Serial_Data(szData, sizeof(szData)); //Read data from ESP32-CAM
          if(usData_Len > 0)
          {
            if(szData[0] == '*')
            {
              sscanf(szData, "*%d,", &sMask_Percent);// ESP32-CAM will return mask percent to Arduino
              usIsNeed_Rescan = 0;
              gusIsSend_Request = 0;
              usExit = 1;
            }
          }
        }
        else
        {
          usIsNeed_Rescan = 1;
          gusIsSend_Request = 0;
          usExit = 1; 
        }   
      }while(usExit == 0);

      if(usIsNeed_Rescan == 0)
      {
        vLCD_Disp_Status(sMask_Percent);
        
        if(sMask_Percent >= MASK_DETECT_TH) //if the percentage is higher than MASK_DETECT_TH (80%), door will open
        {
          vLED_Control(SCAN_PASS);
          vServo_Control(OPEN_DOOR);
          delay(OPEN_DOOR_TIME);
          
          vServo_Control(CLOSE_DOOR);
          delay(CLOSE_DOOR_TIME);
        }
        else 
        {
          vServo_Control(DOOR_LOCK);
          vLED_Control(SCAN_ERROR);
          delay(1000);
        }
      }
      else if(usIsNeed_Rescan == 1)
      {
        short a = 0;
        vLCD_Disp_Error_Scan();
        vLED_Control(SCAN_ERROR);
        memset(szData, '\0', sizeof(szData));
        Serial.println(RESTART_ESP32);
        
        do
        {
          usData_Len = usRead_Serial_Data(szData, sizeof(szData)); //Read data from ESP32-CAM, and dump previous data.
          if(usData_Len > 0)
          {
            gusIsSend_Request = 0;
            for(short i=0; i<usData_Len; i++)
            {
              if(szData[i] == '#')
              {
                i++;
                while(szData[i] != ',')
                {
                  gszIP_Add[a] = szData[i++];
                  a++;
                }
                usExit = 0;
                break;
              }
            }
          }
        }while(usExit == 1);
      }
      gusIsSensor_Detect_Bef = 1;
    }
  }
  else
  {
    vLED_Control(SCAN_NO_OBJECT);
    gusIsSensor_Detect_Bef = 0;
    
    if(gusIsNeedDisp == 1)
    {
      vLCD_Disp_Ip(gszIP_Add);
      gusIsNeedDisp = 0;
    }
  }
  vLCD_Disp_Timer_Index();
  sRead_But();
}
void vDisp_Scanning()
{
  lcd.clear();
  lcd.print("SCANNING..."); 
  lcd.setCursor(0,1);
  lcd.print("Pls wait & hold.");
}
//PERCENTAGE OF FACE DETECTED, MORE FACE DETECTED, LOW PERCENTAGE, NO MASK
void vLCD_Disp_Status(short sMask_Percent)
{
  lcd.clear();
  lcd.print("Mask: ");
  lcd.print(sMask_Percent);
  lcd.print("%");
  lcd.setCursor(0,1);

  if(sMask_Percent >= MASK_DETECT_TH)
  {
    lcd.print("Enter Allowed.");
  }
  else
  {
    lcd.print("PLEASE WEAR MASK");
  }
}
void vLCD_Disp_Ip(char *szIp)
{
  lcd.clear();
  
  if(gusLCD_Index == 0)
  {
    lcd.setCursor(3,0);
    lcd.print("WELCOME TO ");
    lcd.setCursor(4,1);
    lcd.print("MYBOTIC");
  }
  else if(gusLCD_Index == 1)
  {
    lcd.setCursor(2,0);
    lcd.print("PLEASE SCAN");
    lcd.setCursor(2,1);
    lcd.print(" YOUR FACE");
  }  
  else
  { 
    lcd.setCursor(2,0);
    lcd.print("CONNECTED TO:");
    lcd.setCursor(1,1);
    lcd.print(szIp);
  }
}
void vLCD_Disp_Error_Scan()
{
  lcd.clear();
  lcd.print("Take a step back"); 
  lcd.setCursor(0,1);
  lcd.print("and rescan again");
}
void vLED_Control(short sScan_Status)
{
  if(sScan_Status == SCAN_PASS)
  {
    digitalWrite(LED_GREEN,HIGH);
    digitalWrite(LED_RED,LOW);
  }
  else if(sScan_Status == SCAN_ERROR)
  {
    digitalWrite(LED_GREEN,LOW);
    digitalWrite(LED_RED,HIGH);
  }
  else 
  {
    digitalWrite(LED_GREEN,LOW);
    digitalWrite(LED_RED,LOW);
  }
}
void vLCD_Disp_Timer_Index()
{
  if((millis() - glStart_Timer_LCD) >= 1000)
  {
    gusLCD_Index++;

    if(gusLCD_Index > 2)
    {
      gusLCD_Index = 0;
    }
    gusIsNeedDisp = 1;
    glStart_Timer_LCD = millis();
  }
}
short sRead_But()
{
  short sBut_Status = 0;

  sBut_Status = digitalRead(PUSH_BUTTON_PIN);

  if(sBut_Status == HIGH)
  {
    vServo_Control(OPEN_DOOR);
    vLED_Control(SCAN_PASS);
    vServo_Control(CLOSE_DOOR);
    
    gusLCD_Index = 0;
    glStart_Timer_LCD = millis();
    gusIsNeedDisp = 1;
  }
}
//CONTROL SERVO BASED ON RESULTS
void vServo_Control(int sDoor_Status)
{
  myServo.attach(SERVO_PIN);
  
  if(sDoor_Status == OPEN_DOOR) //open door
  {
    for(int i = CLOSE_DOOR; i > OPEN_DOOR; i--)
    {
      myServo.write(i);
      delay(20);
    }
  }
  else if(sDoor_Status == CLOSE_DOOR)
  {
    for(int i = OPEN_DOOR; i < CLOSE_DOOR; i++)
    {
      myServo.write(i);
      delay(20);
    }
  }
  else if(sDoor_Status == DOOR_LOCK)
  {
    myServo.write(CLOSE_DOOR);
  }

  myServo.detach();
}
//READ SERIAL DATA FROM ESP32-CAM
unsigned short usRead_Serial_Data(char *szData, short sDataSize)
{
  short i=0;
  
  if(Serial.available())
  {
    *(szData+i) = Serial.read();
    i++;
    delay(2);

    while(Serial.available())
    {
      *(szData+i) = Serial.read();
      i++;
      
      if(i >= sDataSize)
      {
        break;  
      }
      delay(2);      
    }
  }
  
  Serial.flush();

  return i;
}
