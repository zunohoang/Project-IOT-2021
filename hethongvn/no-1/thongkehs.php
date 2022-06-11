<?php 
  /*    if (session_id() === '') session_start();
      if (!isset($_SESSION['name'])){
          echo "Hết phiên đăng nhập";
          die();
      } */
?>
<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8">
     <title>Quản Lí</title>
     <style type="text/css">
      .bang-body{
          width: 100%;
     }
     body{
          
          font-family: Helvetica;
          background-attachment :fixed;
          background-repeat: repeat-x;
     }
    *{
       margin: 0;
       padding: 0;
     }
      .bang-body #menu{
          width: 15%;
          background-image: linear-gradient( to bottom,#000033,#000033,#0000FF);
          border: 0;
          color: white;
      }
      .bang-body #center{
          width: 55%;
          padding: 5px 5px 5px 5px;
          overflow-y: value;
      }
      .bang-body #thong-ke{
          width: 10%;
          border: 0;
      }
      .bang-body #hocsinh{
        border-radius: 8px;    
        height : 120px;
        margin-top:10px;
       /* display: inline-block;*/
    }
      #conhs{
          color: white;
          padding: 20px 10px;      
      }
      #thong{
         /*display : inline-block;*/
        /* width:70%;*/
      }
      #hocsinh:hover{
          box-shadow:  2px 2px #A8A8A8;
          border: 5px;
      }
      .abcs td,tr,th{
        border: 1px solid black;
      }
      .abcs{
        width: 95%;
      }
      .abcs th{
          background-color: #B0C4DE;
      }
      ul{
        list-style-type: none;
        background: #00A6AD;
        height:50px;
        border-bottom-right-radius: 100px;
      }
      li{
        float: left;
      }
      li a{
        display: inline-block;
        text-decoration: none;
        color: white;
        padding: 14px 16px;

      }
      a:hover{
        background:     #00676B;
      }
     </style>
</head>
<body>
     <table class="bang-body">
         <tr  valign="top">
          <!--    <td id="menu" rowspan="3">   
                    <div  style="display: flex;padding: 10px 5px;" id="as"> 
                   <h2>THPT LÝ TỰ TRỌNG</h2>
                    <img src="./img/note2.png" width="10%" height="10%" style="margin-left:20%;padding:5px;">
                   </div>
                   <br><br><br><br>
                   <div>
                    <table>
                         <td>
                    <tr onclick="trangchu()" style="font-size: 20px;color: #C0C0C0;height:30px;"><td style="padding: 0px 10px;">Trang Chủ</td></tr>                    <tr style="font-size: 20px;height: 30px;"><td style="padding: 0px 20px;">Thống Kê</td></tr>
                    <tr style="font-size: 20px;color: #C0C0C0;height: 30px;"><td style="padding: 0px 10px;">Stream Video</td></tr>
                    <tr style="font-size: 20px;color: #C0C0C0;height: 30px;"><td style="padding: 0px 10px;">Thư viện</td></tr>
                    <tr style="font-size: 20px;color: #C0C0C0;height: 30px;"><td style="padding: 0px 10px;">Liên Hệ</td></tr>
                    </td>
                    <script>
                        function trangchu(){
                              window.location="./thong-ke-tat-ca-hoc-sinh.php";
                        }
                    </script>
                   </table>
                   </div>
                   <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                   <div>
                    <img src="./img/logof.jpg" width="90%"style="border-radius:150px;">
                   </div>
               </td>-->
            <td colspan="3" style="background-image:url('./img/bg0.jpg');border-radius:5px;">
                <div style="display:inline-flex;padding:10px 10px;">
                <img src="./img/logoedu.jpg" style="width:50px; border-radius:50px;">
                   <h1 style="color: white;padding:5px 5px;">Manager</h1></div>
                   <h1 style="text-align: center;color: white;margin-top: -40px;"><br><br>
                   Hệ Thống quản lý Học sinh và Giáo viên</h1><br><br><br>
                   <div style="display: flex;color:#AFEEEE"><p>User: Admin</p><p style="margin-left: 85%;">Log Out</p></div>

              </td>
         </tr>
         <tr>
            <td>
             <div class="menu2">
                 <ul>
                     <li><a href="./trang-chu.php">Trang Chủ</a></li>
                     <li><a href="./danh-sach.php">Danh Sách</a></li>
                     <li><a href="./thong-ke-tat-ca-hoc-sinh.php">Thống Kê</a></li>
                     <li><a href="">Stream Video</a></li>
                     <li><a href="">Thư Viện</a></li>
                 </ul>
              </div>
            </td>
        </tr>
         <tr>
               <td id="center" valign="top" >
                  <h2> ADMIN Thống Kê</h2><br>
                  __________________________________<br>
                  <div>
                    <!--  <tr>
                          <td>
                              
                       
                      <div id="thong">
   				<div onclick="all()" id="hocsinh" style="background-image: linear-gradient(to bottom right,#B80000,#FF0000);
">

   				  <div id="conhs" >
   				  	
   					<p style="font-size:40px;"><?php /*echo $i */?></p>   
   					<p style="font-size: 15px;">Học sinh</p>
   					<p style="font-size:10px;padding: 2px 10px;">Student</p>
   				  </div>
   				</div>
                <div onclick="comat()" id="hocsinh" style="background-image: linear-gradient(to bottom right,#008000,#33FF66,white);">
   				  <div id="conhs">
   					<p style="font-size:40px;"><?php /*echo $comat*/ ?></p>
   					<p style="font-size: 15px;">Có mặt</p>
   					<p style="font-size:10px;padding: 2px 10px;">Present</p>
   				  </div>
   				</div>
                <div onclick="vangmat()" id="hocsinh"style="background-image: linear-gradient(to bottom right,#0000FF,#00BFFF,white);">
   				  <div id="conhs">
   					<p style="font-size:40px;"><?php /*echo $vangmat; */?></p>
   					<p style="font-size: 15px;">Vắng mặt</p>
   					<p style="font-size:10px;padding: 2px 10px;">Absent</p>
   				  </div>
   				</div>
   				<div id="hocsinh"style="background-image: linear-gradient(to bottom right,#808000,white);">
   				  <div id="conhs">
   					<p style="font-size:40px;">0</p>
   					<p style="font-size: 15px;">Không Khẩu trang</p>
   					<p style="font-size:10px;padding: 2px 10px;">No Mask</p>
   				  </div>
   				</div>
   				<div id="hocsinh"style="background-image: linear-gradient(to bottom right,#0000FF,#00FF7F,white);">
   				  <div id="conhs">
   					<p style="font-size:40px;">0</p>
   					<p style="font-size: 15px;">Nhiệt độ không ổn</p>
   					<p style="font-size:10px;padding: 2px 10px;">Unstable temperature
</p>
   				  </div>
   				</div>
                 </div>
                    </td>
                        
                      </tr>-->
                  
                  <div>
                      
                  <br><form method='GET'>
                    Ngày: <input type="text" name="date" > <input type = "submit" name="sub" value = "Kiểm tra"></form>
               <br>
                <br>
               <table style="border: 1px solid black;border-collapse:collapse; text-align: center;margin-left: 2.5%;" class="abcs">
                    <tr>
                         <th width="5%">SBD</th>
                         <th width="20%">Họ Tên</th>
                         <th width="5%">Lớp</th>
                         <th width="13%">Điểm danh</th>
                         <th width="13%">Nhiệt dộ</th>
                         <th width="13%">Khẩu trang</th>
                         <th width="10%">Ảnh</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Nguyễn Văn Hoàng</td>
                        <td>11A2</td>
                        <td>06-49-39</td>
                        <td>35.6</td>
                        <td>100</td>
                        <td><img scr='https://khkt2021ltt.000webhostapp.com/uploads/07-01-2022_06-49-30A12.jpg'></td>
                    </tr>
               <?php
              /*	  
              	  if (isset($_GET['sub'])){
              	      $day = $_GET['date'];
              	  }else{
               		date_default_timezone_set('Asia/Ho_Chi_Minh');
                         $day = date('d-m-Y', time());
                         $gio = date('h-i-s a',time());
              	  }
              	  if (isset($day)){
              	         echo $day;
                         $conn= mysqli_connect("localhost","id16891170_admin","4102005TaoHoàng", "id16891170_hoc9choi1");
                         if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                         } 
                         $sql = "SELECT * FROM `hethongdb`";
                         $result = $conn->query($sql);
                         $i=0;$comat=0;$vangmat=0;
                         if($result->num_rows>0){
                                 while ($row = mysqli_fetch_array($result)){ 
                                    $i+=1;
                                    $code=$row['code'];
                                    $sql2= "SELECT * FROM `".$code."`";
                                    $result2 = $conn->query($sql2);
                                    $k=false;
                                    $nhietdo="";
                                    $diemdanh="";
                                    while ($row2 = mysqli_fetch_array($result2)){
                                       if(($row2['day']==$day)){
                                         $nhietdo = $nhietdo."<br>".$row2['nhietdo'];
                                         $khautrang = $row2['khautrang'];
                                         $diemdanh = $diemdanh."<br>".$row2['gio'];
                                         $anh="http://khkt2021ltt.000webhostapp.com/uploads/".$row2['img'];
                                         $k=true;
                                       } 
                                     } 
                                    if($k){
                                     for($o=0;$o<=7;$o++){
                                         if($diemdanh[$o]=='-'){
                                             $diemdanh[$o]=':';
                                         }
                                     }
                                      $comat+=1;
                                       echo "<tr><td>".$row['code']."</td>".
                                "<td>".$row['name']."</td>".
                                "<td>".$row['class']."</td>".
                                "<td>".$diemdanh."</td>".
                                "<td>".$nhietdo."</td>".
                                "<td>".$khautrang."</td>".
                                "<td><img src='".$anh."' width = '100%'></td></tr>";
                                    }else{
                                        $vangmat+=1;
                                        echo "<tr><td>".$row['code']."</td>".
                                "<td>".$row['name']."</td>".
                                "<td>".$row['class']."</td>".
                                "<td>NO</td>".
                                "<td>Nghỉ</td>".
                                "<td>Nghỉ</td>".
                                "<td style='background-color:#D2691E';color:white;>Nghỉ</td></tr>";
                                    }
                             }
                         }
              	         }
              	      */
                	?>
                </table>
          </div>
           </div>
               </td>
               <td id="thong-ke">
                 <div id="thong">
                    <div onclick="tatca()" id="hocsinh" style="background-image: linear-gradient(to bottom right,#B80000,#FF0000);
">
                      <div id="conhs" >
                         
                         <p style="font-size:40px;"><?php echo $i; ?></p>   
                         <p style="font-size: 15px;">Học sinh</p>
                         <p style="font-size:10px;padding: 2px 10px;">Student</p>
                      </div>
                    </div>
                <div onclick="comat()" id="hocsinh" style="background-image: linear-gradient(to bottom right,#008000,#33FF66,white);">
                      <div id="conhs">
                         <p style="font-size:40px;"><?php echo $comat; ?></p>
                         <p style="font-size: 15px;">Có mặt</p>
                         <p style="font-size:10px;padding: 2px 10px;">Present</p>
                      </div>
                    </div>
                <div onclick="vangmat()" id="hocsinh"style="background-image: linear-gradient(to bottom right,#0000FF,#00BFFF,white);">
                      <div id="conhs">
                         <p style="font-size:40px;"><?php echo $vangmat; ?></p>
                         <p style="font-size: 15px;">Vắng mặt</p>
                         <p style="font-size:10px;padding: 2px 10px;">Absent</p>
                      </div>
                    </div>
                    <div id="hocsinh"style="background-image: linear-gradient(to bottom right,#808000,white);">
                      <div id="conhs">
                         <p style="font-size:40px;"> 0</p>
                         <p style="font-size: 15px;">Không Khẩu trang</p>
                         <p style="font-size:10px;padding: 2px 10px;">No Mask</p>
                      </div>
                    </div>
                    <div id="hocsinh"style="background-image: linear-gradient(to bottom right,#0000FF,#00FF7F,white);">
                      <div id="conhs">
                         <p style="font-size:40px;"> 0</p>
                         <p style="font-size: 15px;">Nhiệt độ không ổn</p>
                         <p style="font-size:10px;padding: 2px 10px;">Unstable temperature
</p>
                      </div>
                    </div>
                 </div>
               </td>
          </tr>
    </table>
              <script type="text/javascript">
            function tatca(){
               window.location="./thong-ke-tat-ca-hoc-sinh.php";
            }
            function comat(){
               window.location="./thong-ke-hoc-sinh-co-mat.php";
            }
            function vangmat(){
               window.location="./thong-ke-hoc-sinh-vang-mat.php";
            }
         </script>
</body>
</html>