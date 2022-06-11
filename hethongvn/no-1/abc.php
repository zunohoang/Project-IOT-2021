<?php session_start();
      echo '1';
      if (isset($_SESSION['quyen'])){
         if($_SESSION['quyen']!="quyen"){
            echo "Bạn không đủ quyền";
            die();
        }
      }else{
          echo "Hết phiên đăng nhập";
          die();
      } 
?>