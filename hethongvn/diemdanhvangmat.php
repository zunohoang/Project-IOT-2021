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
    <h1 style="text-align: center;color:#CC0099;">Điểm danh</h1>
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
                  <input type="submit" name="sub" value="Tìm kiếm">
               
                 </div>
             </form>
                 <div>
                     <p>Tìm kiếm lớp:</p>
                     <form>
                     <div style="display:flex;">
                       
                        <input type="text" name="nameclass" placeholder ="Ví dụ: 11A2">
                         <input type="submit" name="sub" value ="Tìm kiếm">
                        
                     </div>
                     </form>
                     </form>
                 
                 </div>
        		</td>
        		<td>
                    <br>
                <button onclick="window.location.href='#'">Hiển thị người Vắng Mặt</button>
                <button onclick="window.location.href='/hethongvn/diemdanh.php'">Hiển thị tất cả mọi người</button>
                <button onclick="window.location.href='/hethongvn/diemdanhcomat.php'">Hiển thị người có mặt</button>
                <div class="bang">
        		   	<table border="2" width="100%" style="text-align:center;border: 5px solid #00FA9A;
    border-collapse: collapse;">
        		   	    <tr>   
                            <th width="13%">STT</th>  		   		
        		        	<th width="18%">Họ Và Tên</th>       		   		
        		   			<th width="13%">Lớp</th>       		   		
         		   			<th width="13%">Ngày Sinh</th>      		   		
        		   			<th width="13%">Mã Học Sinh</th> 
                            <th width="10%">Điểm danh</th>
                            <th width="13%">Nhiệt độ</th> 
                            <th width="8%">Khẩu trang</th>
                            <th width="8%">Mức độ</th>    		   		
        		   	    </tr>
                         <?php
                           date_default_timezone_set('Asia/Ho_Chi_Minh');
                          $day = date('d-m-Y', time());
                          $gio = date('h:i:s a',time());
                            $conn= mysqli_connect("localhost","id16891170_admin","4102005TaoHoàng", "id16891170_hoc9choi1");
                              if ($conn->connect_error) {
                                  die("Connection failed: " . $conn->connect_error);
                              } 
                              $sql = "SELECT * FROM `hethongdb`";
                              $result = $conn->query($sql);
                              $i=0;
                              if($result->num_rows>0){
                                 while ($row = mysqli_fetch_array($result)){ 
                                    
                                    $code=$row['code'];
                                    $sql2= "SELECT * FROM `".$code."`";
                                    $result2 = $conn->query($sql2);
                                    $k=false;$nhietdo="";
                                    while ($row2 = mysqli_fetch_array($result2)){
                                       if(($row2['day']==$day)and($row2['diemdanh']=='yes')){
                                         $k=true;
                                       } 
                                     }
                            if (!$k){       $i+=1;                                                     
                                     echo "<tr>
                            <td>".$i."</td>
                            <td>".$row['name']."</td>
                            <td>".$row['class']."</td>
                            <td>".$row['ngaysinh']."</td>
                            <td>".$code."</td>
                            <td>Không</td>
                            <td>Lỗi</td>
                            <td>Không</td>
                            <td>Nghỉ học</td>
                        </tr>";
                                }  
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