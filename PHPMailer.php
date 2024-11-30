<?php
class PHPMailer{

  
   public function guiMail($token) {
      require("./PHPMailer-master/src/PHPMailer.php");
      require("./PHPMailer-master/src/SMTP.php");
      require("./PHPMailer-master/src/Exception.php");
       
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP(); // enable SMTP
    
        $mail->SMTPDebug = 1; 
        $mail->SMTPAuth = true; 
        $mail->SMTPSecure = 'ssl'; 
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; 
        $mail->IsHTML(true);
        $mail->Username = "datcongh43@gmail.com";
        $mail->Password = "khvgnasyftpcggwo";
        $mail->SetFrom("datcongh43@gmail.com");
        $mail->Subject = "Reset password";
        $mail->Body = <<<END
            <p>Nhấn <a href="http://localhost:3000/xampp/htdocs/DACN_CNPM/index.php?controller=login&action=hienThiThayDoiMK&token=$token">ở đây</a> để lấy lại mật khẩu.</p>
            <p>Hạn 30 phút.</p>
        END;
        $mail->AddAddress("datcongh53@gmail.com");
    
         if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
         } else {
            echo "Message has been sent";
         }
   }
}
  
?>