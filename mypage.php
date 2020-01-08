<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
//================================
// 画面処理
//================================
//ログイン認証
require('auth.php');

// 画面表示用データ取得
//================================
$u_id = $_SESSION['user_id'];
//DBからしおりデータを取得
$shioriData = getMyShiori($u_id);
//DBからお気に入りデータを取得
$favoriteData = getMyFavorite($u_id);

debug('取得したしおりデータ：'.print_r($shioriData,true));
debug('取得したお気に入りデータ：'.print_r($favoriteData,true));
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'マイページ';
require('head.php'); 
?>


<body class="page-mypage page-2colum page-logined">
    
    <?php
    require('header.php');
    ?>
    
    <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?>
    </p>
    
    <!--メインコンテンツ-->
    <div id="contents" class="site-width">
        
        <!--Main-->
        <section id="main">
           <h1 class="page-title">MYPAGE</h1>
            <section class="list panel-list">
                <h2 class="mypage-title">
                    <i class="far fa-edit" style="margin-right:3px;"></i>しおり編集／削除
                </h2>
                <?php
                    if(!empty($shioriData)):
                    foreach($shioriData as $key => $val):
                ?>
                <a href="registPlan.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&s_id='.$val['id'] : '?s_id='.$val['id']; ?>" class="panel">
                    <div class="panel-head">
                       <p class="panel-title"><?php echo sanitize($val['title']); ?></p>
                        <img src="<?php echo showImg(sanitize($val['main_pic'])); ?>" alt="<?php echo sanitize($val['title']); ?>">
                    </div>
                    <div class="panel-body">
                        <div class="panel-top">
                           <p class="departure">出発地：<?php echo sanitize($val['departure']); ?></p>
                           <p class="arrival">目的地：<?php echo sanitize($val['arrival']); ?></p>
                        </div>
                        <div class="panel-bottom">
                           <p class="time">日数：<?php echo sanitize($val['time']); ?></p>
                           <p class="cost">予算：<?php echo sanitize($val['cost']); ?>円</p>
                        </div>
                    </div>
                </a>
                <?php
                    endforeach;
                    endif;
                ?>
            </section>
            
            <style>
                .list{
                    margin-bottom: 30px;
                }
            
            </style>
            
            <section class="list panel-list">
                <h2 class="mypage-title">
                    <i class="far fa-heart" style="margin-right:3px;"></i>お気に入り
                </h2>
                <?php
                    if(!empty($favoriteData)):
                    foreach($favoriteData as $key => $val):
                ?>
                <a href="planDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&s_id='.$val['id'] : '?s_id='.$val['id']; ?>" class="panel">
                    <div class="panel-head">
                       <p class="panel-title"><?php echo sanitize($val['title']); ?></p>
                        <img src="<?php echo showImg(sanitize($val['main_pic'])); ?>" alt="<?php echo sanitize($val['title']); ?>">
                    </div>
                    <div class="panel-body">
                        <div class="panel-top">
                           <p class="departure">出発地：<?php echo sanitize($val['departure']); ?></p>
                           <p class="arrival">目的地：<?php echo sanitize($val['arrival']); ?></p>
                        </div>
                        <div class="panel-bottom">
                           <p class="time">日数：<?php echo sanitize($val['time']); ?></p>
                           <p class="cost">予算：<?php echo sanitize($val['cost']); ?>円</p>
                        </div>
                    </div>
                </a>
                <?php
                    endforeach;
                    endif;
                ?>
            </section>
            
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
    
   
  
 
















