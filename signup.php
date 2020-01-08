<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ユーザー登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//POST送信されていた場合
if(!empty($_POST)){
    
    //変数にユーザー情報を代入
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $year = $_POST['birth_year'];
    $month = $_POST['birth_month'];
    $day = $_POST['birth_day'];
    $email = $_POST['email'];
    $email_re = $_POST['email_re'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];
    
    //未入力チェック
    validRequired($username, 'username');
    validRequired($email, 'email');
    validRequired($email_re, 'email_re');
    validRequired($pass, 'pass');
    validRequired($pass_re, 'pass_re');
    validRequired($gender,'gender');
    validRequired($birthday,'birthday');
    
    if(empty($err_msg)){
        
        //emailの形式チェック
        validEmail($email, 'email');
        //emailの最大文字数チェック
        validMaxLen($email, 'email');
        //email重複チェック
        validEmailDup($email);
        
        //パスワードチェック
        validPass($pass, 'pass');
        
        if(empty($err_msg)){
            
            //EmailとEmailの再入力が合っているかチェック
            validMatch($email, $email_re, 'email_re');
            //パスワードとパスワードの再入力が合っているかチェック
            validMatch($pass, $pass_re, 'pass_re');
            
            if(empty($err_msg)){
                
                //例外処理
                try{
                    //DBへ接続
                    $dbh = dbConnect();
                    //SQL文作成
                    $sql = 'INSERT INTO users(username,gender,birth_year,birth_month,birth_day,email,password,login_time,create_date) VALUES(:username,:gender,:birth_year,:birth_month,:birth_day,:email,:pass,:login_time,:create_date)';
                    $data = array(':username' => $username, ':gender' => $gender, ':birth_year' => $year, ':birth_month' => $month, ':birth_day' => $day, ':email' => $email, ':pass' => password_hash($pass,PASSWORD_DEFAULT),
                                 ':login_time' => date('Y-m-d H:i:s'),
                                 ':create_date' => date('Y-m-d H:i:s'));
                    //クエリ実行
                    $stmt = queryPost($dbh, $sql, $data);
                    
                    //クエリ成功の場合
                    if($stmt){
                        //ログイン有効期限（デフォルトを１時間とする）
                        $sesLimit = 60*60;
                        //最終ログイン日時を現在日時に
                        $_SESSION['login_date'] = time();
                        $_SESSION['login_limit'] = $sesLimit;
                        //ユーザーIDを格納
                        $_SESSION['user_id'] = $dbh->lastInsertId();
                        
                        debug('セッション変数の中身：'.print_r($_SESSION,true));
                        
                        header("Location:mypage.php");
                    }
                }catch(Exception $e){
                    error_log('エラー発生：'.$e->getMessage());
                    $err_msg['common'] = MSG07;
                }
            }
        }
    }
}

?>


<?php
    $siteTitle = '新規登録';
    require('head.php');
?>
    



    <body class="page-login page-1colum">
       
       <!--ヘッダー-->
         <?php
            require('header.php'); 
         ?>
        
        <!--メイン-->
        <div id="contents" class="site-width">
            
            <!--Main-->
            <section id="main">
                <div class="form-container">
                   <form action="" method="post" class="form">
                    <h2 class="title">ユーザー登録</h2>
                    
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['common'])) echo $err_msg['common'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['username'])) echo 'err'; ?>">
                        名前
                        <input type="text" name="username" value="<?php if(!empty($_POST['username'])) echo $_POST['username']; ?>" required>
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['username'])) echo $err_msg['username'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['gender'])) echo 'err';?> " >
                        性別<br>
                        <input type="radio" name="gender" value="1" required <?php echo array_key_exists('gender', $_POST) && $_POST['gender'] == '1' ? 'checked' : ''; ?>>男性
                        <input type="radio" name="gender" value="2"<?php echo array_key_exists('gender', $_POST) && $_POST['gender'] == '2' ? 'checked' : ''; ?>>女性
                        <input type="radio" name="gender" value="3" <?php echo array_key_exists('gender', $_POST) && $_POST['gender'] == '3' ? 'checked' : ''; ?>>非選択
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['gender'])) echo $err_msg['gender'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['birthday'])) echo 'err';?>">
                        生年月日<br>
                        <select name="birth_year" id="" value="<?php if(!empty($_POST['birth_year'])) echo $_POST['birth_year']; ?>" style="width:20%; display:inline; margin-right:5px;">
                           <option value="" style='display:none;' ></option>
                            <?php
                            $now = date("Y");
                            for($i = 1950; $i <= $now; $i++){ ?>
                               
                                <option value="<?php echo $i; ?>" <?php echo array_key_exists('birth_year', $_POST) && $_POST['birth_year'] == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php } ?>
                        </select>年
                        <select name="birth_month" id="" value="<?php if(!empty($_POST['birth_month'])) echo $_POST['birth_month']; ?>" style="width:15%; display:inline; margin-left:10px; margin-right:5px;">
                          <option value="" style='display:none;'></option>
                           <?php for($i = 1; $i <= 12; $i++){ ?>
                           <option value="<?php echo $i; ?>" <?php echo array_key_exists('birth_month', $_POST) && $_POST['birth_month'] == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                           <?php } ?>
                        </select>月
                        <select name="birth_day" id="" value="<?php if(!empty($_POST['birth_day'])) echo $_POST['birth_day']; ?>" style="width:15%; display:inline; margin-left:10px; margin-right:5px;">
                          <option value="" style='display:none;' ></option>
                           <?php for($i = 1; $i <= 31; $i++){ ?>
                           <option value="<?php echo $i; ?>" <?php echo array_key_exists('birth_day', $_POST) && $_POST['birth_day'] == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                           <?php } ?>
                        </select>日
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['birthday'])) echo $err_msg['birthday'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?> ">
                        E-mail
                        <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['email'])) echo $err_msg['email'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['email_re'])) echo 'err';?>">
                        E-mail 確認用
                        <input type="text" name="email_re" value="<?php if(!empty($_POST['email_re'])) echo $_POST['email_re']; ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['email_re'])) echo $err_msg['email_re'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['pass'])) echo 'err';?>">
                        Password <span style="font-size:12px">※半角英数字６文字以上</span>
                        <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['pass'])) echo $err_msg['pass'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err';?>">
                        Password 確認用
                        <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
                        ?>
                    </div>
                    
                    <div class="btn-container">
                        <input type="submit" class="btn btn-mid" value="登録する">
                    </div>
                   
                   </form>
                </div>
                
                
            </section>
            
        </div>
        
        <!-- footer -->
        <?php
        require('footer.php'); 
        ?>
        
        
    
    
    



