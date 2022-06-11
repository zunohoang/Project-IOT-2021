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
          width: 20%;
          border: 0;
          
      }
      .bang-body #hocsinh{
        border-radius: 8px;    
         height:500px;
        margin-top:10px;
    }
    .to {
    display: grid;
    grid-template-columns: repeat(12,1fr);
    grid-template-rows: minmax(5px,auto);
}
 
.form {
    border: 1px solid #80808000;
    grid-column: 6/9;
    grid-row: 3;
    height: 470px;
    width: 292px;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    border-radius: 15px;
    box-shadow: 0px 0px 14px 0px grey;
    background-color: white;
}
h2 {
    margin-top: 40px;
    margin-bottom: 10px;
}
i.fab.fa-app-store-ios {
    display: block;
    margin-bottom: 50px;
    font-size: 28px;
}
 
label {
    margin-left: -126px;
    display: block;
    font-weight: lighter;
 
}
input{
    display: block;
    border-bottom: 2px solid #00BCD4;
    margin-top: 6px;
    margin-bottom: 10px;
    outline-style: none;
}
input[type="text"] {
    padding: 5px;
    width: 70%;
}
 
input#submit {
    padding: 7px;
    width: 50%;
    border-radius: 10px;
    border: none;
    position: absolute;
    bottom: 10px;
    cursor: pointer;
    background: linear-gradient(to right, #fc00ff, #00dbde);
}
input#submit:hover{
 
    background: linear-gradient(to right, #fc466b, #3f5efb); 
}
 
      #conhs{
          color: white;
          padding: 20px 10px;      
      }
      #thong{
          
      }
      #hocsinh:hover{
          box-shadow:  2px 2px #A8A8A8;
          border: 5px;
      }
      .abcs td,tr,th{
        border: 1px solid black;
      }
      .abcs th{
          background-color: #B0C4DE;
      }
     </style>
</head>
<body>
     <table class="bang-body">
          <tr  valign="top">
               <td id="menu" rowspan="3">   
                    <div  style="display: flex;padding: 10px 5px;" id="as"> 
                   <h2>THPT LÝ TỰ TRỌNG</h2>
                    <img src="./img/note2.png" width="10%" height="10%" style="margin-left:20%;padding:5px;">
                   </div>
                   <br><br><br><br>
                   <div>
                       			    	<table>
   			    		<td>
   			    	<tr onclick="trangchu()" style="font-size: 20px;color: #C0C0C0;height:30px;"><td style="padding: 0px 10px;">Trang Chủ</td></tr>
   			    	<tr onclick="danhsach()" style="font-size: 20px;color: #C0C0C0;height: 30px;"><td style="padding: 0px 20px;">Danh sách</td></tr>
   			    	<tr onclick="thongke()" style="font-size: 20px;height: 30px;"><td style="padding: 0px 10px;">Thống Kê</td></tr>
   			    	<tr onclick="streamvideo()" style="font-size: 20px;color: #C0C0C0;height: 30px;"><td style="padding: 0px 10px;">Stream Video</td></tr>
   			    	<tr onclick="thuvien()" style="font-size: 20px;color: #C0C0C0;height: 30px;"><td style="padding: 0px 10px;">Thư viện</td></tr>
   			    	<tr onclick="lienhe()" style="font-size: 20px;color: #C0C0C0;height: 30px;"><td style="padding: 0px 10px;">Liên Hệ</td></tr>
   			    	</td>
   			    	<script>
   			    	    function trangchu(){
   			    	         	window.location="./trang-chu.php";
   			    	    }
   			    	    function thongke(){
   			    	         	window.location="./thong-ke-tat-ca-hoc-sinh.php";
   			    	    }
   			    	    function danhsach(){
   			    	         	window.location="./danh-sach.php";
   			    	    }
   			    	    function streamvideo(){
   			    	         	window.location="./stream-video.php";
   			    	    }
   			    	    function lienhe(){
   			    	         	window.location="./lien-he.php";
   			    	    }
   			    	    function thuvien(){
   			    	        window.location="./thu-vien.php";
   			    	    }
   			    	</script>
   			    </table>
                   </div>
                   <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                   <div>
                    <img src="./img/logof.jpg" width="90%"style="border-radius:150px;">
                   </div>
               </td>
               </script>
            <td colspan="3" style="background-image:url('./img/bg0.jpg');border-radius:5px;">
                   <h1 style="text-align: center;color: white;"><br><br>
                   Hệ Thống quản Lý Học sinh</h1><br><br><br>
                   <div style="display: flex;color:#AFEEEE"><p>User: Admin</p><p style="margin-left: 85%;">Log Out</p></div>

              </td>
         </tr>
         <tr>
               <td id="center" valign="top" >
                  <h2> ADMIN Thống Kê</h2><br>
                  __________________________________<br>
                  <div>
                  <br>
               <table style="border: 1px solid black;border-collapse:collapse; text-align: center;" class="abcs">
                    <tr>
                         <th width="5%">SBD</th>
                         <th width="30%">Họ Tên</th>
                         <th width="10%">Lớp</th>
                    <th width="20%">Ngày sinh</th>
                    <th>Chú thích</th>
                    </tr>
               <?php
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
                         $i=0;
                         $comat=0;
                         $vangmat=0;
                          if($result->num_rows>0){
                                 while ($row = mysqli_fetch_array($result)){ 
                                      echo "<tr><td>".$row['code']."</td>".
                                "<td>".$row['name']."</td>".
                                "<td>".$row['class']."</td>".
                                "<td>".$row['ngaysinh']."</td>".
                                "<td>".$row['chuthich']."</td><tr>";
                                 }
                         
                         }
              	         }
              	      
                	?>
              </table>
           </div>
               </td>
               <td id="thong-ke" valgin="top">
                   <form method = "GET">
                           <div class="to">
            <div class="form">
                <h2>Thêm học sinh</h2>
                <i class="fab fa-app-store-ios"></i>
                <label style="margin-left: -150px;">Họ và tên</label>
                <input type="text" name="name">
                <label style="margin-left: -195px;"> Lớp</label>
                <input type="text" name="class">    
                <label style="margin-left: -150px;"> Ngày Sinh</label>
                <input type="text" name="ngaysinh">
                <label style="margin-left: -190px;"> SBD</label>
                <input type="text" name="mhs">
                    <?php
                       if(isset($_GET['submit'])){
                         $conn= mysqli_connect("localhost","id16891170_admin","4102005TaoHoàng", "id16891170_hoc9choi1");
                         if ($conn->connect_error) {
                           die("Connection failed: " . $conn->connect_error);
                         } 
                         $name = $_GET['name'];
                         $class = $_GET['class'];
                         $code = $_GET['mhs'];
                         $ngaysinh = $_GET['ngaysinh'];
                         $chuthich = "...";
                         $sql = "INSERT INTO `hethongdb` (`id`,`name`,`class`,`code`,`ngaysinh`,`chuthich`) VALUES (NULL, '".$name."','".$class."', '".$code."','".$ngaysinh."','".$chuthich."');";
                         if ($conn->query($sql)){
                         $code = "`".$code."`";
                         $sql2 = "CREATE TABLE `id16891170_hoc9choi1`.".$code." ( `id` INT(225) NOT NULL AUTO_INCREMENT ,  `day` VARCHAR(225) NOT NULL ,  `gio` VARCHAR(10) NOT NULL ,  `nhietdo` VARCHAR(225) NOT NULL ,  `khautrang` VARCHAR(225) NOT NULL ,  `diemdanh` VARCHAR(225) NOT NULL ,  `img` VARCHAR(225) NOT NULL ,    PRIMARY KEY  (`id`)) ENGINE = InnoDB;";
                          if ($conn->query($sql2)){
                              echo "Thêm thành công";
                          }else{echo "false1";}
                        }else{echo "Thêm thất bại";}
 

                       }
                     ?>
                <input id="submit" type="submit" name="submit" value="Thêm học sinh">
            </div>                
        </div></form>
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