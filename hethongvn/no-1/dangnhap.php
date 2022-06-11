<?php session_start();
    $conn= mysqli_connect("localhost","id16891170_admin","4102005TaoHoàng", "id16891170_hoc9choi1");
     if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                         } 
   $pass = $_POST['password'];
   $user = $_POST['username'];
   $sql =  "SELECT * FROM `taikhoan` WHERE `user` LIKE '".$user."';";
   $result = mysqli_query($conn,$sql);
   $k = true;
   if (!$result)
   {
    echo "Sai tài khoản hoặc mật khẩu";
   }
   else
   {
    $row = mysqli_fetch_array($result);
    if ( $row['pass'] == $pass ){
      $_SESSION['name'] = $row['name'];
      $_SESSION['quyen'] = $row['quyen'];
      echo "<script type='text/javascript'>
  alert('Chào mừng bạn Enter Để tiếp tục');
  window.location='https://hoc9choi1.000webhostapp.com/hethongvn/no-1/thong-ke-tat-ca-hoc-sinh.php';
</script>";
    }
    else
    {
      echo "Sai tài khoản hoặc mật khẩu";
      echo "Quay lại sau 5s";
    }
   }
?>
