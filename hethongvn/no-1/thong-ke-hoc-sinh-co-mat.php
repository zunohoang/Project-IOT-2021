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
        height: 120px;
        margin-top:10px;
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
   			    	<tr onclick="danhsach()" style="font-size: 20px;height: 30px;color: #C0C0C0;"><td style="padding: 0px 10px;">Danh sách</td></tr>
   			    	<tr onclick="thongke()" style="font-size: 20px;height: 30px;"><td style="padding: 0px 20px;">Thống Kê</td></tr>
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
   			   <br><form method='GET'>
   			   	Ngày: <input type="text" name="date" > <input type = "submit" name="sub" value = "Kiểm tra"></form>
               <br>
                <br>
               <table style="border: 1px solid black;border-collapse:collapse; text-align: center;" class="abcs">
               	<tr>
               		<th width="5%">SBD</th>
               		<th width="20%">Họ Tên</th>
               		<th width="5%">Lớp</th>
                    <th width="13%">Điểm danh</th>
                    <th width="13%">Nhiệt dộ</th>
                    <th width="13%">Khẩu trang</th>
                    <th width="10%">Ảnh</th>
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
                                    $i+=1;
                                    $code=$row['code'];
                                    $sql2= "SELECT * FROM `".$code."`";
                                    $result2 = $conn->query($sql2);
                                    $k=false;
                                    $nhietdo="";
                                    while ($row2 = mysqli_fetch_array($result2)){
                                       if(($row2['day']==$day)and($row2['diemdanh']=="yes")){
                                         $nhietdo = $nhietdo."-".$row2['nhietdo'];
                                         $khautrang = $row2['khautrang'];
                                         $anh=$row2['img'];
                                         $k=true;
                                       } 
                                     } 
                                    if($k){
                                       $comat +=1;
                                       echo "<tr><td>".$row['code']."</td>".
                                "<td>".$row['name']."</td>".
                                "<td>".$row['class']."</td>".
                                "<td>YES</td>".
                                "<td>".$nhietdo."</td>".
                                "<td>".$khautrang."</td>".
                                "<td><img src='../uploads/".$anh."' width = '100%'></td></tr>";
                                    }else $vangmat +=1;
                             }
                         }
              	         }
              	      
                	?>
              </table>
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