<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Trang chủ - Hệ Thống Quản Lý</title>
	<style type="text/css">
	    body{
	    	font-family: Verdana;
            background-image: url("https://thuthuatnhanh.com/wp-content/uploads/2020/01/background-powerpoint-dep.jpg");
            background-attachment :fixed;
	    	
	    }

		ul{
            margin-top:-1%;
            margin-left: -3%;
	    }
		li{
			background-color:#00CCFF;
		    float: left;
		    list-style-type: none;
		    text-align: center;
		    padding: 20px 25px;
        }
        a{
            text-decoration-line: none;
            padding: 19px 24px;
        }
        a:hover{
        	background-color: #000000;
        	color: white;
        }
        .bang{
            background-color:white;

        }
        .left{
            background-color:#00BFFF;
            border-radius: 10px;
        }
        .trungtam{
          
        }
	</style>
</head>
<body>
	<div class="head">
    <ul>
    	<li><a href="#">Trang chủ</a></li>
    	<li><a href="#">Quản lí HS</a></li>
    	<li><a href="#">Điểm danh</a></li>
        <li><a href="#">Liên hệ</a></li>
    </ul>
    <h1 style="text-align: center;color:#CC0099;">Danh Sách Học Sinh</h1>
    </div>
    <div class="trungtam">
        <table  width="100%">
        	<tr>
        		<td width="20%">
                 <div class="left">
                 <p style="margin-top:10px;">Tìm kiếm học sinh:</p>
                 <form>
                 <div style="display: flex;">
                
                  <input type="text" name="namestudent" placeholder="Nguyễn Văn A">
                  <input type="submit" name="sub3" value="Tìm kiếm">
               
                 </div>
             </form>
                 <div>
                     <p>Tìm kiếm lớp:</p>
                     <form>
                     <div style="display:flex;">
                       
                        <input type="text" name="nameclass" placeholder ="Ví dụ: 11A2">
                         <input type="submit" name="sub2" value ="Tìm kiếm">
                        
                     </div>
                     </form>
                     </form>
                 <div>
                     <p>Thêm học sinh:</p>
                     <form method="GET">
                     <div>
                       <div>
                        
                        <input type="text" name="name" placeholder ="Ví dụ: Nguyễn Văn A">
                        
                        <input type="text" name="class" placeholder ="Ví dụ: 11A2">
                        
                        <input type="text" name="ngaysinh" placeholder ="Ví dụ: 01/01/2000">
                
                        <input type="text" name="mhs" placeholder ="Ví dụ: 11111">
                        
                        <input type="text" name="chuthich" placeholder="Chú thích">
                        <input type="submit" name="sub" value ="Thêm">
                       </div>
                     </div>
                     </form>
                     <?php
                       if(isset($_GET['sub'])){
                         $conn= mysqli_connect("localhost","id16891170_admin","4102005TaoHoàng", "id16891170_hoc9choi1");
                         if ($conn->connect_error) {
                           die("Connection failed: " . $conn->connect_error);
                         } 
                         $name = $_GET['name'];
                         $class = $_GET['class'];
                         $code = $_GET['mhs'];
                         $ngaysinh = $_GET['ngaysinh'];
                         $chuthich = $_GET['chuthich'];
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
                 </div>
                 </div>
        		</td>
        		<td><button onclick="window.location.href='/hethongvn/diemdanh.php'">Hiển thị tất cả mọi người</button>
                <div class="bang">
        		   	<table border="2" width="100%" style="text-align:center;border: 5px solid #00FA9A;
    border-collapse: collapse;">
        		   	    <tr>   
                            <th width="18%">STT</th>  		   		
        		        	<th width="18%">Họ Và Tên</th>       		   		
        		   			<th width="18%">Lớp</th>       		   		
         		   			<th width="18%">Ngày Sinh</th>      		   		
        		   			<th width="18%">Mã Học Sinh</th> 
                            <th width="18%">Chú Thích</th>     		   		
        		   	    </tr>
                            <?php
                              $conn= mysqli_connect("localhost","id16891170_admin","4102005TaoHoàng", "id16891170_hoc9choi1");
                              if ($conn->connect_error) {
                                  die("Connection failed: " . $conn->connect_error);
                              } 
                              $sql = "SELECT * FROM `hethongdb`";
                              $result = $conn->query($sql);
                              $i=0;
                              if($result->num_rows>0){
                                 while ($row = mysqli_fetch_array($result)){
                                    $i+=1;
                                    echo "<tr><td>".$i."</td>
                            <td>".$row['name']."</td>
                            <td>".$row['class']."</td>
                            <td>".$row['ngaysinh']."</td>
                            <td>".$row['code']."</td>
                            <td>...</td></tr>";
                                 }
                              }
                            ?>
                  	</table>
                 </div>
        		</td>
        	</tr>
        </table>
    </div>
    <br>
    <div style="text-align: center;">
    	Coppy Right by HoangChuot
    </div>
</body>
</html>