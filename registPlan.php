<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　商品出品登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');


//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
//GETデータを格納
$s_id = (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
//DBから商品データを取得
$dbFormData = (!empty($s_id)) ? getShiori($_SESSION['user_id'], $s_id) : '';
//新規登録画面か編集画面か判別用フラグ
$edit_flg = (empty($dbFormData)) ? false : true;
//DBから都道府県データを取得
$dbDepartureData = getDeparturePrefecture();
$dbArrivalData = getArrivalPrefecture();
//DBから旅行日数データを取得
$dbTimeData = getTime();

debug('しおりID：'.$s_id);

// パラメータ改ざんチェック
//================================
// GETパラメータはあるが、改ざんされている場合、正しい商品データが取れないのでマイページへ遷移させる
if(isset($_POST['action'])){
    debug('マイページへ遷移します。');
    header("Location:mypage.php");
}elseif(!empty($s_id) && empty($dbFormData)){
    debug('GETパラメータの商品IDが違います。マイページへ遷移します。');
    header("Location:mypage.php");
    exit;
}


// POST送信時処理
//================================
if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST,true));
    debug('FILE情報：'.print_r($_FILES,true));
    
    //変数にユーザー情報を代入
    $title = $_POST['title'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $time = $_POST['time'];
    $cost = (!empty($_POST['cost'])) ? $_POST['cost'] : 0;
    
    //画像をアップロードし、パスを格納
    $main_pic = (!empty($_FILES['main_pic']['name'])) ? uploadImg($_FILES['main_pic'],'main_pic') : '';
    $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'],'pic1') : '';
    $pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'],'pic2') : '';
    $pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILES['pic3'],'pic3') : '';
    $pic4 = (!empty($_FILES['pic4']['name'])) ? uploadImg($_FILES['pic4'],'pic4') : '';
    $pic5 = (!empty($_FILES['pic5']['name'])) ? uploadImg($_FILES['pic5'],'pic5') : '';
    
    //画像をPOSTしてないがすでにDBに登録されている場合、DBのパスを入れる
    $main_pic = (empty($main_pic) && !empty($dbFormData['main_pic'])) ? $dbFormData['main_pic'] : $main_pic;
    $pic1 = (empty($pic1) && !empty($dbFormData['pic1'])) ? $dbFormData['pic1'] : $pic1;
    $pic2 = (empty($pic2) && !empty($dbFormData['pic2'])) ? $dbFormData['pic2'] : $pic2;
    $pic3 = (empty($pic3) && !empty($dbFormData['pic3'])) ? $dbFormData['pic3'] : $pic1;
    $pic4 = (empty($pic4) && !empty($dbFormData['pic4'])) ? $dbFormData['pic4'] : $pic4;
    $pic5 = (empty($pic5) && !empty($dbFormData['pic5'])) ? $dbFormData['pic5'] : $pic5;
    
    
    $comment1 = $_POST['comment1'];
    $comment2 = $_POST['comment2'];
    $comment3 = $_POST['comment3'];
    $comment4 = $_POST['comment4'];
    $comment5 = $_POST['comment5'];
    $link1 = $_POST['link1'];
    $link2 = $_POST['link2'];
    $link3 = $_POST['link3'];
    $link4 = $_POST['link4'];
    $link5 = $_POST['link5'];
    $hotel = $_POST['hotel'];
    $food1 = $_POST['food1'];
    $food2 = $_POST['food2'];
    $food3 = $_POST['food3'];
    $gift1 = $_POST['gift1'];
    $gift2 = $_POST['gift2'];
    $gift3 = $_POST['gift3'];
    $point = $_POST['point'];
    
    //更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
    if(empty($dbFormData)){
        //未入力チェック
        validRequired($title,'title');
        //最大文字数チェック
        validMaxLen($title,'title');
        //未入力チェック
        validRequired($departure,'departure');
        validRequired($arrival,'arrival');
        validRequired($time,'time');
        validRequired($cost,'cost');
        //半角数字チェック
        validNumber($cost,'cost');
        //未入力チェック
        validRequired($comment1,'comment1');
        //最大文字数チェック
        validMaxLen($hotel,'hotel');
        validMaxLen($food1,'food1');
        validMaxLen($food2,'food2');
        validMaxLen($food3,'food3');
        validMaxLen($gift1,'gift1');
        validMaxLen($gift2,'gift2');
        validMaxLen($gift3,'gift3');
        validMaxLen($point,'point');
        
    }else{
        if($dbFormData['title'] !== $title){
            validRequired($title,'title');
            validMaxLen($title,'title');
        }
        if($dbFormData['departure'] !== $departure){
            validRequired($departure,'departure');
        }
        if($dbFormData['arrival'] !== $arrival){
            validRequired($arrival,'arrival');
        }
        if($dbFormData['time'] !== $time){
            validRequired($time,'time');
        }
        if($dbFormData['cost'] !== $cost){
            validRequired($cost,'cost');
            validNumber($cost,'cost');
        }
        if($dbFormData['comment1'] !== $comment1){
            validRequired($comment1,'comment1');
        }
        if($dbFormData['hotel'] !== $hotel){
            validMaxLen($hotel,'hotel');
        }
        if($dbFormData['food1'] !== $food1){
            validMaxLen($food1,'food1');
        }
        if($dbFormData['food2'] !== $food2){
            validMaxLen($food2,'food2');
        }
        if($dbFormData['food3'] !== $food3){
            validMaxLen($food3,'food3');
        }
        if($dbFormData['gift1'] !== $gift1){
            validMaxLen($gift1,'gift1');
        }
        if($dbFormData['gift2'] !== $gift2){
            validMaxLen($gift2,'gift2');
        }
        if($dbFormData['gift3'] !== $gift3){
            validMaxLen($gift3,'gift3');
        }
        if($dbFormData['point'] !== $point){
            validMaxLen($point,'point');
        }
    }
    
    if(empty($err_msg) && empty($_POST['action'])){
        debug('バリデーションOKです。');
        
        //例外処理
        try{
            //DBへ接続
            $dbh = dbConnect();
            //SQL文作成
            //編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を生成
            if($edit_flg){
                debug('DB更新です。');
                $sql = 'UPDATE shiori SET title = :title, departure = :departure, arrival = :arrival, time = :time, cost = :cost, main_pic = :main_pic, comment1 = :comment1, pic1 = :pic1, link1 = :link1, comment2 = :comment2, pic2 = :pic2, link2 = :link2, comment3 = :comment3, pic3 = :pic3, link3 = :link3, comment4 = :comment4, pic4 = :pic4, link4 = :link4, comment5 = :comment5, pic5 = :pic5, link5 = :link5, hotel = :hotel, food1 = :food1, food2 = :food2, food3 = :food3, gift1 = :gift1, gift2 = :gift2, gift3 = :gift3, point = :point WHERE user_id = :u_id AND id = :s_id';
                $data = array(':title' => $title, ':departure' => $departure, ':arrival' => $arrival, ':time' => $time, ':cost' => $cost, ':main_pic' => $main_pic, ':comment1' => $comment1, ':pic1' => $pic1, ':link1' => $link1, ':comment2' => $comment2, ':pic2' => $pic2, ':link2' => $link2, ':comment3' => $comment3, ':pic3' => $pic3, ':link3' => $link3, ':comment4' => $comment4, ':pic4' => $pic4, ':link4' => $link4, ':comment5' => $comment5, ':pic5' => $pic5, ':link5' => $link5, ':hotel' => $hotel, ':food1' => $food1, ':food2' => $food2, ':food3' => $food3, ':gift1' => $gift1, ':gift2' => $gift2, ':gift3' => $gift3, ':point' => $point, ':u_id' => $_SESSION['user_id'], ':s_id' => $s_id);
                
            }else{
                debug('DB新規登録です。');
                $sql = 'INSERT INTO shiori (title,departure,arrival,time,cost,main_pic,comment1,pic1,link1,comment2,pic2,link2,comment3,pic3,link3,comment4,pic4,link4,comment5,pic5,link5,hotel,food1,food2,food3,gift1,gift2,gift3,point,user_id,create_date) VALUES (:title,:departure,:arrival,:time,:cost,:main_pic,:comment1,:pic1,:link1,:comment2,:pic2,:link2,:comment3,:pic3,:link3,:comment4,:pic4,:link4,:comment5,:pic5,:link5,:hotel,:food1,:food2,:food3,:gift1,:gift2,:gift3,:point,:u_id,:date)';
                $data = array(':title' => $title, ':departure' => $departure, ':arrival' => $arrival, ':time' => $time, ':cost' => $cost, ':main_pic' => $main_pic, ':comment1' => $comment1, ':pic1' => $pic1, ':link1' => $link1, ':comment2' => $comment2, ':pic2' => $pic2, ':link2' => $link2, ':comment3' => $comment3, ':pic3' => $pic3, ':link3' => $link3, ':comment4' => $comment4, ':pic4' => $pic4, ':link4' => $link4, ':comment5' => $comment5, ':pic5' => $pic5, ':link5' => $link5, ':hotel' => $hotel, ':food1' => $food1, ':food2' => $food2, ':food3' => $food3, ':gift1' => $gift1, ':gift2' => $gift2, ':gift3' => $gift3, ':point' => $point, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
            }
            debug('SQL:'.$sql);
            debug('流し込みデータ：'.print_r($data,true));
            //クエリ実行
            $stmt = queryPost($dbh,$sql,$data);
            
            //クエリ成功の場合
            if($stmt){
                $_SESSION['msg_success'] = SUC04;
                debug('マイページへ遷移します。');
                header("Location:mypage.php");
            }
            
        }catch(Exception $e){
            error_log('エラー発生：'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
?>


<?php
$siteTitle = (!$edit_flg) ? 'しおり登録' : 'しおり編集';
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
           <h1 class="page-title"><?php  echo (!$edit_flg) ? 'しおりを作成する' : 'しおりを編集する'; ?></h1>
            <div class="form-container">
                <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%; box-sizing:border-box;">
                    
                    
                    <label class="<?php if(!empty($err_msg['title'])) echo 'err'; ?>">
                        タイトル<span class="label-require">必須</span>
                        <input type="text" name="title" value="<?php echo getFormData('title'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['common'])) echo $err_msg['common'];
                        ?>
                    </div>
                    
                    <div class="category">
                    <label class="<?php if(!empty($err_msg['departure'])) echo 'err'; ?>">
                        出発地<span class="label-require">必須</span>
                        <select name="departure" style="font-size:16px;">
                        <option value="" <?php if(getFormData('departure') == 0){echo 'selected';} ?>>選択してください</option>
                        <?php
                            foreach($dbDepartureData as $key => $val){
                        ?>
                        <option value="<?php echo $val['name'] ?>" <?php if(getFormData('departure') == $val['name']){echo 'selected'; } ?>>
                        <?php echo $val['name']; ?>
                        </option>
                        <?php
                            }
                        ?>
                        </select>
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['departure'])) echo $err_msg['departure'];
                        ?>
                    </div>
                    </div>
                    
                    <div class="category">
                    <label class="<?php if(!empty($err_msg['arrival'])) echo 'err'; ?>">
                        目的地<span class="label-require">必須</span>
                        <select name="arrival" style="font-size:16px;">
                        <option value="" <?php if(getFormData('arrival') == 0){echo 'selected';} ?>>選択してください</option>
                        <?php
                            foreach($dbArrivalData as $key => $val){
                        ?>
                        <option value="<?php echo $val['name'] ?>" <?php if(getFormData('arrival') == $val['name']){echo 'selected'; } ?>>
                        <?php echo $val['name']; ?>
                        </option>
                        <?php
                            }
                        ?>
                        </select>
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['arrival'])) echo $err_msg['arrival'];
                        ?>
                    </div>
                    </div>
                    
                    <div class="category">
                    <label class="<?php if(!empty($err_msg['time'])) echo 'err'; ?>">
                        所要時間<span class="label-require">必須</span>
                        <select name="time" style="font-size:16px;">
                        <option value="" <?php if(getFormData('time') == 0){echo 'selected';} ?>>選択してください</option>
                        <?php
                            foreach($dbTimeData as $key => $val){
                        ?>
                        <option value="<?php echo $val['name'] ?>" <?php if(getFormData('time') == $val['name']){echo 'selected'; } ?>>
                        <?php echo $val['name']; ?>
                        </option>
                        <?php
                            }
                        ?>
                        </select>
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['time'])) echo $err_msg['time'];
                        ?>
                    </div>
                    </div>
                    
                    <div class="category" style="margin-right:0; width:150px;">
                    <label class="<?php if(!empty($err_msg['cost'])) echo 'err'; ?>">
                        予算<span class="label-require">必須</span>
                        <div class="form-group">
                            <input type="text" name="cost" style="font-size:16px; width:120px;" value="<?php echo (!empty(getFormData('cost'))) ? getFormData('cost') : 0; ?>"><span class="option">円</span>
                        </div>
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['cost'])) echo $err_msg['cost'];
                        ?>
                    </div>
                    </div>
                    
                    <div class="imgDrop-container">
                        メイン画像
                        <label class="area-drop" <?php if(!empty($err_msg['main_pic'])) echo 'err'; ?>>
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                        <input type="file" name="main_pic" class="input-file">
                        <img src="<?php echo getFormData('main_pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('main_pic'))) echo 'display:none;' ?>">
                        ドラッグ＆ドロップ
                        </label>
                        <div class="area-msg">
                            <?php
                            if(!empty($err_msg['main_pic'])) echo $err_msg['main_pic'];
                            ?>
                        </div>
                    </div>
                    
                    <div style="clear:left;">
                    詳細スケジュール<span class="label-require">必須</span>
                        <div class="schedule">
                            <div class="scheduleDetail">
                                <div class="schedule-left">
                                    <div class="comment">
                                        <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
                                        <textarea name="comment1"><?php echo getFormData('comment1'); ?></textarea>
                                        </label>
                                    </div>
                                </div>
                                <div class="schedule-right">
                                    <div class="schedule-img">
                                        <label class="area-drop" <?php if(!empty($err_msg['pic'])) echo 'err'; ?>>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                        <input type="file" name="pic1" class="input-file">
                                        <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic1'))) echo 'display:none;' ?>">
                                        画像
                                        </label>
                                    </div>
                                    <div class="schedule-link">
                                        <label for="">
                                        <input class="link" type="text"name="link1" value="<?php echo getFormData('link1'); ?>" placeholder="リンク">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="scheduleDetail">
                                <div class="schedule-left">
                                    <div class="comment">
                                        <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
                                        <textarea name="comment2"><?php echo getFormData('comment2'); ?></textarea>
                                        </label>
                                    </div>
                                </div>
                                <div class="schedule-right">
                                    <div class="schedule-img">
                                        <label class="area-drop" <?php if(!empty($err_msg['pic'])) echo 'err'; ?>>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                        <input type="file" name="pic2" class="input-file">
                                        <img src="<?php echo getFormData('pic2'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic2'))) echo 'display:none;' ?>">
                                        画像
                                        </label>
                                    </div>
                                    <div class="schedule-link">
                                        <label for="">
                                        <input class="link" type="text"name="link2" value="<?php echo getFormData('link2'); ?>" placeholder="リンク">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="close-schedule">
                            <div class="scheduleDetail">
                                <div class="schedule-left">
                                    <div class="comment">
                                        <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
                                        <textarea name="comment3"><?php echo getFormData('comment3'); ?></textarea>
                                        </label>
                                    </div>
                                </div>
                                <div class="schedule-right">
                                    <div class="schedule-img">
                                        <label class="area-drop" <?php if(!empty($err_msg['pic'])) echo 'err'; ?>>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                        <input type="file" name="pic3" class="input-file">
                                        <img src="<?php echo getFormData('pic3'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic3'))) echo 'display:none;' ?>">
                                        画像
                                        </label>
                                    </div>
                                    <div class="schedule-link">
                                        <label for="">
                                        <input class="link" type="text"name="link3" value="<?php echo getFormData('link3'); ?>" placeholder="リンク">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="scheduleDetail">
                                <div class="schedule-left">
                                    <div class="comment">
                                        <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
                                        <textarea name="comment4"><?php echo getFormData('comment4'); ?></textarea>
                                        </label>
                                    </div>
                                </div>
                                <div class="schedule-right">
                                    <div class="schedule-img">
                                        <label class="area-drop" <?php if(!empty($err_msg['pic'])) echo 'err'; ?>>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                        <input type="file" name="pic4" class="input-file">
                                        <img src="<?php echo getFormData('pic4'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic4'))) echo 'display:none;' ?>">
                                        画像
                                        </label>
                                    </div>
                                    <div class="schedule-link">
                                        <label for="">
                                        <input class="link" type="text"name="link4" value="<?php echo getFormData('link4'); ?>" placeholder="リンク">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="scheduleDetail">
                                <div class="schedule-left">
                                    <div class="comment">
                                        <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
                                        <textarea name="comment5"><?php echo getFormData('comment5'); ?></textarea>
                                        </label>
                                    </div>
                                </div>
                                <div class="schedule-right">
                                    <div class="schedule-img">
                                        <label class="area-drop" <?php if(!empty($err_msg['pic'])) echo 'err'; ?>>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                        <input type="file" name="pic5" class="input-file">
                                        <img src="<?php echo getFormData('pic5'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic5'))) echo 'display:none;' ?>">
                                        画像
                                        </label>
                                    </div>
                                    <div class="schedule-link">
                                        <label for="">
                                        <input class="link" type="text"name="link5" value="<?php echo getFormData('link5'); ?>" placeholder="リンク">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            </div>
                            
                            
                            
                            
                            
                        </div>
                    </div>
                    
                    
                    
                    
                    
                    
                    <label class="<?php if(!empty($err_msg['hotel'])) echo 'err'; ?>">
                        本日のお宿
                        <input type="text" name="hotel" value="<?php echo getFormData('hotel'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['hotel'])) echo $err_msg['hotel'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['food1'])) echo 'err'; ?>">
                        食べたいものリスト
                        <input type="text" name="food1" value="<?php echo getFormData('food1'); ?>" placeholder="1.">
                    </label>
                    
                    <label class="<?php if(!empty($err_msg['food2'])) echo 'err'; ?>">
                        <input type="text" name="food2" value="<?php echo getFormData('food2'); ?>" placeholder="2.">
                    </label>
                    
                    <label class="<?php if(!empty($err_msg['food3'])) echo 'err'; ?>">
                        <input type="text" name="food3" value="<?php echo getFormData('food3'); ?>" placeholder="3.">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['food1'])) echo $err_msg['food1'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['gift1'])) echo 'err'; ?>">
                        おみやげリスト
                        <input type="text" name="gift1" value="<?php echo getFormData('gift1'); ?>" placeholder="1.">
                    </label>
                    
                    <label class="<?php if(!empty($err_msg['gift2'])) echo 'err'; ?>">
                        <input type="text" name="gift2" value="<?php echo getFormData('gift2'); ?>" placeholder="2.">
                    </label>
                    
                    <label class="<?php if(!empty($err_msg['gift3'])) echo 'err'; ?>">
                        <input type="text" name="gift3" value="<?php echo getFormData('gift3'); ?>" placeholder="3.">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['gift1'])) echo $err_msg['gift1'];
                        ?>
                    </div>
                    
                    <label class="<?php if(!empty($err_msg['point'])) echo 'err'; ?>">
                        特徴！
                        <input type="text" name="point" value="<?php echo getFormData('point'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if(!empty($err_msg['point'])) echo $err_msg['point'];
                        ?>
                    </div>
                    
                    <div class="btn-container short-container">
                        <div class="delete-block">
                        <?php if($edit_flg): ?>
                        <button type="button" id="js-delete-btn"><i class="far fa-trash-alt fa-3x"></i></button>
                        <?php endif; ?>
                        </div>
                        
                        <input type="submit" class="btn btn-mid  short-btn" value="<?php echo (!$edit_flg) ? '作成する' : '編集する'; ?>">
                        
                    </div>
                    
                    
                    
                </form>
                <?php
               //削除ポップアップ
                require('delete.php');
                ?>
                
            </div>
        </section>
        
        <!-- サイドバー -->
      <?php
      require('sidebar_mypage.php');
      ?>
      
    </div>
    
    <!-- footer -->
    <?php
    require('footer.php');
    ?>