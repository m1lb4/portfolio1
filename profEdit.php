<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　プロフィール編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');



//================================
// 画面処理
//================================
// DBからユーザーデータを取得
$dbFormData = getUser($_SESSION['user_id']);

debug('取得したユーザー情報：'.print_r($dbFormData,true));

//POST送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST,true));
    debug('FILE情報：'.print_r($_FILES,true));
    
    //変数にユーザー情報を代入
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $year = $_POST['birth_year'];
    $month = $_POST['birth_month'];
    $day = $_POST['birth_day'];
    $tel = $_POST['tel'];
    $zip = $_POST['zip'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    
    //DBの情報と入力情報が異なる場合にバリデーションを行う
        if($dbFormData['username'] !== $username){
            //名前の最大文字数チェック
            validMaxLen($username, 'username');
        }
        if(!empty($_POST['tel'])){
            if($dbFormData['tel'] !== $tel){
            //TEL形式チェック
            validTel($tel,'tel');
            }
        }
        if(!empty($_POST['address'])){
            if($dbFormData['address'] !== $address){
            //住所の最大文字数チェック
            validMaxLen($address,'address');
            }
        }
        if(!empty($_POST['zip'])){
            if($dbFormData['zip'] !== $zip ){
            //DBデータをint型にキャスト（型変換）して比較
            //郵便番号形式チェック
            validZip($zip,'zip');
            }
        }
        if($dbFormData['email'] !== $email){
            //emailの最大文字数チェック
            validMaxLen($email,'email');
            if(empty($err_msg['email'])){
                //emailの重複チェック
                validEmailDup($email);
            }
            //emailの形式チェック
            validEmail($email,'emial');
            //emailの未入力チェック
            validRequired($emial,'email');
        }
    
    if(empty($err_msg)){
        debug('バリデーションOKです');
        
        //例外処理
        try{
            //DBへ接続
            $dbh = dbConnect();
            //SQL文作成
            $sql = 'UPDATE users SET username = :u_name, gender = :gender, birth_year = :birth_year, birth_month = :birth_month, birth_day = :birth_day, tel = :tel, zip = :zip, address = :address, email =:email WHERE id = :u_id';
            $data = array(':u_name' => $username, ':gender' => $gender, ':birth_year' => $year, ':birth_month' => $month, ':birth_day' =>$day, ':tel' =>$tel, ':zip' => $zip, ':address' => $address, ':email' => $email, ':u_id' => $dbFormData['id']);
            //クエリ実行
            $stmt = queryPost($dbh,$sql,$data);
            
            //クエリ成功の場合
            if($stmt){
                $_SESSION['msg_success'] = SUC02;
                debug('マイページへ遷移します。');
                header("Location:mypage.php");
                exit;
            }
            
        }catch(Exception $e){
            error_log('エラー発生：'. $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>

<?php
$siteTitle = 'プロフィール編集';
require('head.php');
?>

<body class="page-profEdit page-2colum page-logined">
    
    <!--メニュー-->
    <?php
    require('header.php');
    ?>
    
    <!--メインコンテンツ-->
    <div id="contents" class="site-width">
       
       <!--Main-->
       <section id="main">
       <h1 class="page-title">プロフィール編集</h1>
        <div class="form-container">
            <form action="" method="post" class="form" enctype="multipart/form-data">
                <div class="area-msg">
                    <?php
                    if(!empty($err_msg['common'])) echo $err_msg['common'];
                    ?>
                </div>
                    <label class="<?php if(!empty($err_msg['username'])) echo 'err'; ?>">
                        名前
                        <input type="text" name="username" value="<?php echo getFormData('username'); ?>" required>
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['username'])) echo $err_msg['username'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['gender'])) echo 'err';?> " >
                        性別<br>
                        <input type="radio" name="gender" value="1" <?php if(getFormData('gender') === '1') echo 'checked'; ?> required>男性
                        <input type="radio" name="gender" value="2" <?php if(getFormData('gender') === '2') echo 'checked'; ?> required>女性
                        <input type="radio" name="gender" value="3" <?php if(getFormData('gender') === '3') echo 'checked'; ?> required>非選択
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['gender'])) echo $err_msg['gender'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['birthday'])) echo 'err';?> ">
                        生年月日<br>
                        <select name="birth_year" id="" value="<?php echo getFormData('birth_year') ; ?>" style="width:20%; display:inline; margin-right:5px;">
                           <option value="" style='display:none;'><?php if(!empty(getFormData('birth_year'))) echo getFormData('birth_year') ?></option>
                            <?php
                            $now = date("Y");
                            for($i = 1950; $i <= $now; $i++){ ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>
                        </select>年
                        <select name="birth_month" id="" value="<?php echo getFormData('birth_month'); ?>" style="width:15%; display:inline; margin-left:10px; margin-right:5px;">
                          <option value="" style='display:none;'><?php if(!empty(getFormData('birth_month'))) echo getFormData('birth_month'); ?></option>
                           <?php for($i = 1; $i <= 12; $i++){ ?>
                           <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                           <?php } ?>
                        </select>月
                        <select name="birth_day" id="" value="<?php echo getFormData('birth_day'); ?>" style="width:15%; display:inline; margin-left:10px; margin-right:5px;">
                          <option value="" style='display:none;'><?php if(!empty(getFormData('birth_day'))) echo getFormData('birth_day'); ?></option>
                           <?php for($i = 1; $i <= 31; $i++){ ?>
                           <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                           <?php } ?>
                        </select>日
                    
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['birthday'])) echo $err_msg['birthday'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['tel'])) echo 'err'; ?>">
                        TEL<span style="font-size:12px; maegin-left:5px;">※ハイフンなしで入力してください</span>
                        <input type="text" name="tel" value="<?php echo getFormData('tel'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['tel'])) echo $err_msg['tel'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['zip'])) echo 'err'; ?>">
                        郵便番号<span style="font-size:12px; maegin-left:5px;">※ハイフンなしで入力してください</span>
                        <input type="text" name="zip" value="<?php echo getFormData('zip'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['zip'])) echo $err_msg['zip'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['address'])) echo 'err'; ?>">
                        住所
                        <input type="text" name="address" value="<?php echo getFormData('address'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['address'])) echo $err_msg['address'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?> ">
                        E-mail
                        <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['email'])) echo $err_msg['email'];
                        ?>
                    </div>
                    
                    <div class="btn-container">
                        <input type="submit" class="btn btn-mid" value="変更する">
                    </div>
            </form>
        </div>
    </section>
    
    <!--サイドバー-->
    <?php
        require('sidebar_mypage.php');
    ?>
    
    </div>
    
    <!--footer-->
    <?php
    require('footer.php');
    ?>





















