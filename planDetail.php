<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　詳細ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// 商品IDのGETパラメータを取得
$s_id = (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
//DBから商品データを取得
$viewData = getShioriOne($s_id);
//パラメータに不正な値が入っているかチェック
debug('$viewDataの中身：'.print_r($viewData,true));
if(empty($viewData)){
    error_log('エラー発生：指定ページに不正な値が入りました');
    header("Location:index.php");
    exit;
}
//現在のページのURLを取得
$getUrl = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
debug('URL'.$getUrl);

debug('取得したDBデータ：'.print_r($viewData,true));



debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
    $siteTitle = 'プラン詳細';
    require('head.php');
?>

    <body class="page-planDetail page-1colum">
       
       <!--ヘッダー-->
         <?php
            require('header.php'); 
         ?>
        
        <!--メイン-->
        <div id="contents" class="site-width">
            
            <!--Main-->
            <section id="main">
            <div class="main-plan">
            <div class="main-left">
               <div class="plan-title">
                   <?php echo sanitize($viewData['title']); ?>
               </div>
               
               <div class="subplan">
                   <div class="sub">
                        <p class="subtitle">出発地</p><span class="badge"><?php echo sanitize($viewData['departure']); ?></span>
                    </div>
                    <div class="sub">
                        <p class="subtitle">目的地</p><span class="badge"><?php echo sanitize($viewData['arrival']); ?></span>
                    </div>
                    <div class="sub">
                        <p class="subtitle">日数</p><span class="badge"><?php echo sanitize($viewData['time']); ?></span>
                    </div>
                    <div class="sub">
                        <p class="subtitle">予算</p><span class="badge"><?php echo sanitize($viewData['cost']); ?>円</span>
                    </div>
               </div>
               <div class="icn-box">
                   <i class="fas fa-heart fa-lg icn-favorite js-click-favorite <?php if(isFavorite($_SESSION['user_id'], $viewData['id'])){ echo 'active'; } ?>" aria-hidden="true" data-shioriid="<?php  echo sanitize($viewData['id']); ?>"></i>
                   <a href="https://twitter.com/intent/tweet?url=<?php echo $getUrl ?>&text=<?php echo $viewData['title'] ?>&hashtags=みんなのしおり" target="blank_"><i class="fab fa-twitter-square fa-2x icn-twitter"></i></a>
                   <a href="http://www.facebook.com/share.php?u=<?php $getUrl ?>" rel="nofollow" target="_blank"><i class="fab fa-facebook-square fa-2x icn-facebook"></i></a>
               </div>
            </div>
            <div class="main-right">
                <div class="main-pic">
                    <img src="<?php echo sanitize($viewData['main_pic']); ?>" alt="">
                 </div>
            </div>
            </div>
              
               <div class="planDetail">
               <div class="plan-left">
                <div class="plan-block">
                   <p class="block-title"><?php echo sanitize($viewData['comment1']); ?></p>
                   <img src="<?php echo sanitize($viewData['pic1']); ?>" alt="">
                   <?php if(!empty($viewData['link1'])) : ?>
                    <a href="<?php echo sanitize($viewData['link1']); ?>">参考サイト</a>
                    <?php endif; ?>
                </div>
                
                <div class="plan-block">
                   <p class="block-title"><?php echo sanitize($viewData['comment2']); ?></p>
                   <img src="<?php echo sanitize($viewData['pic2']); ?>" alt="">
                   <?php if(!empty($viewData['link2'])) : ?>
                    <a href="<?php echo sanitize($viewData['link2']); ?>">参考サイト</a>
                    <?php endif; ?>
                </div>
                
                <div class="plan-block">
                   <p class="block-title"><?php echo sanitize($viewData['comment3']); ?></p>
                   <img src="<?php echo sanitize($viewData['pic3']); ?>" alt="">
                   <?php if(!empty($viewData['link3'])) : ?>
                    <a href="<?php echo sanitize($viewData['link3']); ?>">参考サイト</a>
                    <?php endif; ?>
                </div>
                <div class="plan-block">
                   <p class="block-title"><?php echo sanitize($viewData['comment4']); ?></p>
                   <img src="<?php echo sanitize($viewData['pic4']); ?>" alt="">
                   <?php if(!empty($viewData['link4'])) : ?>
                    <a href="<?php echo sanitize($viewData['link4']); ?>">参考サイト</a>
                    <?php endif; ?>
                </div>
                <div class="plan-block">
                   <p class="block-title"><?php echo sanitize($viewData['comment5']); ?></p>
                   <img src="<?php echo sanitize($viewData['pic5']); ?>" alt="">
                   <?php if(!empty($viewData['link5'])) : ?>
                    <a href="<?php echo sanitize($viewData['link5']); ?>">参考サイト</a>
                    <?php endif; ?>
                </div>
               </div>
                
                
                <div class="plan-right">
                
                 <div class="side-plan hotel">
                    <p class="title">本日のお宿</p>
                    <p><?php echo sanitize($viewData['hotel']); ?></p>
                 </div>
                
                 <div class="side-plan food">
                    <p class="title">食べたいものリスト</p>
                    <p>１位 <?php echo sanitize($viewData['food1']); ?></p>
                    <p>２位 <?php echo sanitize($viewData['food2']); ?></p>
                    <p>３位 <?php echo sanitize($viewData['food3']); ?></p>
                 </div>
                
                 <div class="side-plan gift">
                    <p class="title">おみやげリスト</p>
                    <p>１位 <?php echo sanitize($viewData['gift1']); ?></p>
                    <p>２位 <?php echo sanitize($viewData['gift2']); ?></p>
                    <p>３位 <?php echo sanitize($viewData['gift3']); ?></p>
                 </div>
                </div>
                
                <div class="plan-point">
                    <p class="title">特徴！</p>
                    <?php echo sanitize($viewData['point']); ?>
                </div>
                </div>
                
                <div class="item-left">
                    <a href="index.php<?php echo appendGetParam(array('s_id')); ?>">&lt; しおり一覧に戻る</a>
                </div>
                
            </section>
            
        </div>
        
        <!-- footer -->
        <?php
        require('footer.php'); 
        ?>
        
        
    
    
    



