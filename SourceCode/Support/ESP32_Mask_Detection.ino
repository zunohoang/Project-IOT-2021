/*********************************************************************
 * Author   : SHAFIQ YAACOB @ MYBOTIC www.mybotic.com.my
 * Date     : 27 July 2020
 * Project  : Face Mask Detector on ESP32-CAM Board 
 *********************************************************************/


#include "esp_camera.h"
#include <WiFi.h>
#include "esp_timer.h"
#include "img_converters.h"
#include "Arduino.h"
#include "fb_gfx.h"
#include "soc/soc.h" //disable brownout problems
#include "soc/rtc_cntl_reg.h"  //disable brownout problems
#include "esp_http_server.h"
#include "fd_forward.h"
#include "fr_forward.h"


//Replace with your network credentials
const char* ssid = "WIFI_NAME";
const char* password = "WIFI_PASSWORD";

#define PART_BOUNDARY "123456789000000000000987654321"
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

#define FACE_COLOR_RED    0x000000FF
#define FACE_COLOR_GREEN  0x0000FF00
#define FACE_COLOR_YELLOW (FACE_COLOR_RED | FACE_COLOR_GREEN)

#define DETECT_FACE_TIME 2000
#define NUM_FRAME 10
#define PERCENT_WEAR_MASK_TH 80

#define START_RUN 'a'
#define RESTART_ESP32 'r'
#define RESTART_SCAN 'n'

typedef struct {
        size_t size; //number of values used for filtering
        size_t index; //current value index
        size_t count; //value count
        int sum;
        int * values; //array to be filled with values
} ra_filter_t;

static const char* _STREAM_CONTENT_TYPE = "multipart/x-mixed-replace;boundary=" PART_BOUNDARY;
static const char* _STREAM_BOUNDARY = "\r\n--" PART_BOUNDARY "\r\n";
static const char* _STREAM_PART = "Content-Type: image/jpeg\r\nContent-Length: %u\r\n\r\n";

httpd_handle_t stream_httpd = NULL;
static mtmn_config_t mtmn_config = {0};
static int8_t detection_enabled = 1;
static ra_filter_t ra_filter;

short gsFace_Detected = 0;
short gsIsInStream = 0;
short gusRestart_Scan = 0;
unsigned long gulStart_IsInStream_Reset_Timer = 0;
short gsArrayIsFaceDetect[NUM_FRAME] = {0,0,0,0,0,0,0,0,0,0};
unsigned short gusIsMask_Detect = 0;
unsigned short gusFrame_Count = 0;
unsigned short gusIsStart = 0;


void setup() 
{
  WRITE_PERI_REG(RTC_CNTL_BROWN_OUT_REG, 0); //disable brownout detector
 
  Serial.begin(9600);
  Serial.setDebugOutput(false);
  
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
  config.frame_size = FRAMESIZE_QVGA;
  config.jpeg_quality = 10;
  config.fb_count = 1;
  
  // Camera init
  esp_err_t err = esp_camera_init(&config);
  
  if (err != ESP_OK) {
    Serial.printf("Camera init failed with error 0x%x", err);
    return;
  }
  
  sensor_t * s = esp_camera_sensor_get();
  s->set_vflip(s, 1);//flip camera
  //s->set_brightness(s, 1);//up the blightness just a bit
  //s->set_saturation(s, -2);//lower the saturation
  
  // Wi-Fi connection
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");

  delay(100);
  digitalWrite(4, LOW);
  
  Serial.print("#");
  Serial.print(WiFi.localIP());
  Serial.println(",");

  mtmn_config = mtmn_init_config();
  // Start streaming web server
  startCameraServer();
}

void loop() 
{
  // put your main code here, to run repeatedly:
  short sNum_Detect = 0;
  short sPercent_Wear_Mask = 0;
  char szData[10];
  char szTemp[10];
  
  memset(szData, '\0', sizeof(szData));
  if(usRead_Serial_Data(szData, sizeof(szData)) > 0) //read data request from Arduino
  {
    if(szData[0] == START_RUN) // start face_detection algorithm
    {
      gusIsStart = 1; 
      gusRestart_Scan = 0;
      gusFrame_Count = 0; 
    }
    else if(szData[0] == RESTART_ESP32) // restart ESP32-CAM in case of ESP32-CAM hung up
    {
      ESP.restart();
    }    
    else if(szData[0] == RESTART_SCAN)
    {
      gusRestart_Scan = 1;
    }
  }

  if(gsIsInStream == 1)
  {
     if(millis() - gulStart_IsInStream_Reset_Timer > DETECT_FACE_TIME)
     {
       gsIsInStream = 0;
     } 
  }
  else if(gsIsInStream == 0)
  {
    if(gusIsStart == 1)
    {
      vFace_Detection_Offline();
    }
  }

  if(gusFrame_Count >= NUM_FRAME) // scanning for 10 times
  {
    for(short i=0; i<NUM_FRAME; i++)
    {
      if(gsArrayIsFaceDetect[i] == 0)
      {
        sNum_Detect++; // number of face not detected
      }
    }
    sPercent_Wear_Mask = map(sNum_Detect, 0, NUM_FRAME, 0, 100);  //calculate the percentage of face not detected

    if(gusRestart_Scan == 1)
    {
      Serial.println('\0');
    }
    else 
    {
      memset(szTemp, '\0', sizeof(szTemp));
      sprintf(szTemp, "*%d,",sPercent_Wear_Mask);
      Serial.println(szTemp);       //send percentage number to Arduino  
    }
    gusFrame_Count = 0;
    gusIsStart = 0;
  }
}

void startCameraServer()
{
  httpd_config_t config = HTTPD_DEFAULT_CONFIG();
  config.server_port = 80;

  httpd_uri_t index_uri = {
    .uri       = "/",
    .method    = HTTP_GET,
    .handler   = stream_handler,
    .user_ctx  = NULL
  };

  ra_filter_init(&ra_filter, 20);
  
  mtmn_config.type = FAST;
  mtmn_config.min_face = 80;
  mtmn_config.pyramid = 0.707;
  mtmn_config.pyramid_times = 4;
  mtmn_config.p_threshold.score = 0.6;
  mtmn_config.p_threshold.nms = 0.7;
  mtmn_config.p_threshold.candidate_number = 20;
  mtmn_config.r_threshold.score = 0.7;
  mtmn_config.r_threshold.nms = 0.7;
  mtmn_config.r_threshold.candidate_number = 10;
  mtmn_config.o_threshold.score = 0.7;
  mtmn_config.o_threshold.nms = 0.7;
  mtmn_config.o_threshold.candidate_number = 1;
  
  //Serial.printf("Starting web server on port: '%d'\n", config.server_port);
  if (httpd_start(&stream_httpd, &config) == ESP_OK) {
    httpd_register_uri_handler(stream_httpd, &index_uri);
  }
}

static esp_err_t stream_handler(httpd_req_t *req)
{
    camera_fb_t * fb = NULL;
    esp_err_t res = ESP_OK;
    size_t _jpg_buf_len = 0;
    uint8_t * _jpg_buf = NULL;
    char * part_buf[64];
    dl_matrix3du_t *image_matrix = NULL;
    bool detected = false;
    int face_id = 0;

    static int64_t last_frame = 0;
    if(!last_frame)
    {
        last_frame = esp_timer_get_time();
    }

    res = httpd_resp_set_type(req, _STREAM_CONTENT_TYPE);
    if(res != ESP_OK)
    {
        return res;
    }

    httpd_resp_set_hdr(req, "Access-Control-Allow-Origin", "*");

    while(true)
    {
        gsIsInStream = 1;
        gulStart_IsInStream_Reset_Timer = millis(); //restart timer
        
        detected = false;
        face_id = 0;
        fb = esp_camera_fb_get();
        
        if (!fb) 
        {
          Serial.println("Camera capture failed");
          res = ESP_FAIL;
        } 
        else 
        {
            if(!detection_enabled || fb->width > 400)
            {
                if(fb->format != PIXFORMAT_JPEG)
                {
                    bool jpeg_converted = frame2jpg(fb, 80, &_jpg_buf, &_jpg_buf_len);
                    esp_camera_fb_return(fb);
                    fb = NULL;
                    if(!jpeg_converted)
                    {
                        Serial.println("JPEG compression failed");
                        res = ESP_FAIL;
                    }
                } 
                else 
                {
                    _jpg_buf_len = fb->len;
                    _jpg_buf = fb->buf;
                }
            }
            else 
            {
                image_matrix = dl_matrix3du_alloc(1, fb->width, fb->height, 3);

                if (!image_matrix) 
                {
                  Serial.println("dl_matrix3du_alloc failed");
                  res = ESP_FAIL;
                }
                else 
                {
                    if(!fmt2rgb888(fb->buf, fb->len, fb->format, image_matrix->item))
                    {
                      Serial.println("fmt2rgb888 failed");
                      res = ESP_FAIL;
                    } 
                    else 
                    {
                      box_array_t *net_boxes = NULL;
                      
                      if(gusIsStart == 1)
                      {
                        net_boxes = face_detect(image_matrix, &mtmn_config);
                        //gusFrame_Count++;
                      }

                      if (net_boxes || fb->format != PIXFORMAT_JPEG)
                      {
                          if(net_boxes)
                          {
                            detected = true;
                            draw_face_boxes(image_matrix, net_boxes);
                            free(net_boxes->score);
                            free(net_boxes->box);
                            free(net_boxes->landmark);
                            free(net_boxes);
                          }
                          
                          if(!fmt2jpg(image_matrix->item, fb->width*fb->height*3, fb->width, fb->height, PIXFORMAT_RGB888, 90, &_jpg_buf, &_jpg_buf_len))
                          {
                            Serial.println("fmt2jpg failed");
                            res = ESP_FAIL;
                          }
                        esp_camera_fb_return(fb);
                        fb = NULL;
                      } 
                      else 
                      {
                        _jpg_buf = fb->buf;
                        _jpg_buf_len = fb->len;
                      }
                  }
                  dl_matrix3du_free(image_matrix);
                }
            }
        }
        if(res == ESP_OK)
        {
          size_t hlen = snprintf((char *)part_buf, 64, _STREAM_PART, _jpg_buf_len);
          res = httpd_resp_send_chunk(req, (const char *)part_buf, hlen);
        }
        if(res == ESP_OK)
        {
          res = httpd_resp_send_chunk(req, (const char *)_jpg_buf, _jpg_buf_len);
        }
        if(res == ESP_OK)
        {
          res = httpd_resp_send_chunk(req, _STREAM_BOUNDARY, strlen(_STREAM_BOUNDARY));
        }
        if(fb)
        {
          esp_camera_fb_return(fb);
          fb = NULL;
          _jpg_buf = NULL;
        } 
        else if(_jpg_buf)
        {
          free(_jpg_buf);
          _jpg_buf = NULL;
        }
        if(res != ESP_OK)
        {
          break;
        }

        if(detected == 1)
        {
          gsFace_Detected = 1; //detect face  
        }
        else
        {
          gsFace_Detected = 0;
        }

        if(gusIsStart == 1)
        {
          if(gusFrame_Count < NUM_FRAME)
          {
            gsArrayIsFaceDetect[gusFrame_Count] = gsFace_Detected;
          }
          else
          {
            gusIsStart = 0; //Reset
          }
                
          gusFrame_Count++;
        
        }
        
        delay(1);
    }

    last_frame = 0;
    return res;
}
void vFace_Detection_Offline() 
{
  camera_fb_t * frame;
  frame = esp_camera_fb_get();
 
  dl_matrix3du_t *image_matrix = dl_matrix3du_alloc(1, frame->width, frame->height, 3);
  fmt2rgb888(frame->buf, frame->len, frame->format, image_matrix->item);
 
  esp_camera_fb_return(frame);
 
  box_array_t *boxes = face_detect(image_matrix, &mtmn_config);
 
  if(boxes) 
  { 
    gsFace_Detected = 1;
    free(boxes->score);
    free(boxes->box);
    free(boxes->landmark);
    free(boxes);
  }
  else
  {
    gsFace_Detected = 0; 
  }
 
  dl_matrix3du_free(image_matrix);

  if(gusFrame_Count < NUM_FRAME)
  {
    gsArrayIsFaceDetect[gusFrame_Count] = gsFace_Detected;
  }
  gusFrame_Count++;
}
static void draw_face_boxes(dl_matrix3du_t *image_matrix, box_array_t *boxes)
{
    int x, y, w, h, i;
    uint32_t color = FACE_COLOR_YELLOW;
    fb_data_t fb;
    fb.width = image_matrix->w;
    fb.height = image_matrix->h;
    fb.data = image_matrix->item;
    fb.bytes_per_pixel = 3;
    fb.format = FB_BGR888;
    for (i = 0; i < boxes->len; i++){
        // rectangle box
        x = (int)boxes->box[i].box_p[0];
        y = (int)boxes->box[i].box_p[1];
        w = (int)boxes->box[i].box_p[2] - x + 1;
        h = (int)boxes->box[i].box_p[3] - y + 1;
        fb_gfx_drawFastHLine(&fb, x, y, w, color);
        fb_gfx_drawFastHLine(&fb, x, y+h-1, w, color);
        fb_gfx_drawFastVLine(&fb, x, y, h, color);
        fb_gfx_drawFastVLine(&fb, x+w-1, y, h, color);
#if 0
        // landmark
        int x0, y0, j;
        for (j = 0; j < 10; j+=2) {
            x0 = (int)boxes->landmark[i].landmark_p[j];
            y0 = (int)boxes->landmark[i].landmark_p[j+1];
            fb_gfx_fillRect(&fb, x0, y0, 3, 3, color);
        }
#endif
    }
}
static ra_filter_t * ra_filter_init(ra_filter_t * filter, size_t sample_size){
    memset(filter, 0, sizeof(ra_filter_t));

    filter->values = (int *)malloc(sample_size * sizeof(int));
    if(!filter->values){
        return NULL;
    }
    memset(filter->values, 0, sample_size * sizeof(int));

    filter->size = sample_size;
    return filter;
}
static int ra_filter_run(ra_filter_t * filter, int value){
    if(!filter->values){
        return value;
    }
    filter->sum -= filter->values[filter->index];
    filter->values[filter->index] = value;
    filter->sum += filter->values[filter->index];
    filter->index++;
    filter->index = filter->index % filter->size;
    if (filter->count < filter->size) {
        filter->count++;
    }
    return filter->sum / filter->count;
}

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
  return i;
}
