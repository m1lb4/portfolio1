<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ホーム　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// GETパラメータを取得
//----------------------------------
// カレントページ
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
//出発地カテゴリー
$departure = (!empty($_GET['d_id'])) ? $_GET['d_id'] : '';
//目的地カテゴリー
$arrival = (!empty($_GET['a_id'])) ? $_GET['a_id'] : '';
//日数カテゴリー
$time = (!empty($_GET['t_id'])) ? $_GET['t_id'] : '';
//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
//パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
    error_log('エラー発生：指定ページに不正な値が入りました');
    header("Location:index.php");
    exit;
}
//表示件数
$listSpan = 20;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan);
//DBから商品データを取得
$dbShioriData = getShioriList($currentMinNum,$departure,$arrival,$time,$sort);
//DBから出発地カテゴリデータを取得
$dbDeparturePrefectureData = getDeparturePrefecture();
//DBから目的地カテゴリデータを取得
$dbArrivalPrefectureData = getArrivalPrefecture();
//DBから日数カテゴリデータを取得
$dbTimeData = getTime();




debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>



<?php
    $siteTitle = 'HOME';
    require('head.php');
?>

    <body class="page-home page-2colum">
       
       <!--ヘッダー-->
         <?php
            require('header.php'); 
         ?>
        
        <!--メイン画像-->
           <div class="main-img">
             <p>Let's share<br>the travel plan</p>
           </div>
           
        <!--メイン-->
        <div id="contents" class="site-width">
           
           <!--sidebar-->
           <section id="sidebar">
               <form>
                   <div class="sidebar-box" style="margin-bottom:10px;"><div class="box"></div><h1 class="search-title">出発地</h1></div>
                   <div class="selectbox">
                       <span class="icn_select"></span>
                       <select name="d_id">
                           <option value="0"<?php if(getFormData('d_id',true) == 0){echo 'selected';} ?>>選択してください</option>
                           <?php
                            foreach($dbDeparturePrefectureData as $key => $val){
                           ?>
                           <option value="<?php echo $val['name'] ?>" <?php if(getFormData('d_id',true) == $val['name']){echo 'selected'; } ?> >
                               <?php echo $val['name']; ?>
                           </option>
                           <?php
                            }
                           ?>
                       </select>
                   </div>
                   <div class="sidebar-box" style="margin-bottom:10px;"><div class="box"></div><h1 class="search-title">目的地</h1></div>
                   <div class="selectbox">
                       <span class="icn_select"></span>
                       <select name="a_id">
                           <option value="0"<?php if(getFormData('a_id',true) == 0){echo 'selected';} ?>>選択してください</option>
                           <?php
                            foreach($dbArrivalPrefectureData as $key => $val){
                           ?>
                           <option value="<?php echo $val['name'] ?>" <?php if(getFormData('a_id',true) == $val['name']){echo 'selected'; } ?> >
                               <?php echo $val['name']; ?>
                           </option>
                           <?php
                            }
                           ?>
                       </select>
                   </div>
                   <div class="sidebar-box" style="margin-bottom:10px;"><div class="box"></div><h1 class="search-title">日数</h1></div>
                   <div class="selectbox">
                       <span class="icn_select"></span>
                       <select name="t_id">
                           <option value="0"<?php if(getFormData('t_id',true) == 0){echo 'selected';} ?>>選択してください</option>
                           <?php
                            foreach($dbTimeData as $key => $val){
                           ?>
                           <option value="<?php echo $val['name'] ?>" <?php if(getFormData('t_id',true) == $val['name']){echo 'selected'; } ?> >
                               <?php echo $val['name']; ?>
                           </option>
                           <?php
                            }
                           ?>
                       </select>
                   </div>
                   <div class="sidebar-box" style="margin-bottom:10px;"><div class="box"></div><h1 class="search-title">予算</h1></div>
                   <div class="selectbox">
                       <span class="icn_select"></span>
                       <select name="sort">
                           <option value="0" <?php if(getFormData('sort',true) == 0){echo 'slected';} ?>>選択してください</option>
                           <option value="1" <?php if(getFormData('sort',true) == 1){echo 'slected';} ?>>安い順</option>
                           <option value="2" <?php if(getFormData('sort',true) == 2){echo 'slected';} ?>>高い順</option>
                               
                       </select>
                   </div>
                   <input type="submit" value="検索">
               </form>
           </section>
            
            <!--Main-->
            <section id="main">
               <div class="search">
                   <div class="search-left">
                       <span class="total-num"><?php echo sanitize($dbShioriData['total']); ?></span>件のプランが見つかりました
                   </div>
                   <div class="search-right">
                       <span class="num"><?php echo(!empty($dbShioriData['data'])) ? $currentMinNum+1 : 0; ?></span> - <span class="num"><?php  if(!empty($dbShioriData['data'])) echo $currentMinNum+count($dbShioriData['data']); ?></span>件 / <span class="num"><?php echo sanitize($dbShioriData['total']); ?></span>件中
                   </div>
               </div>
               
               <div class="panel-list">
                 <?php
                   foreach((array)$dbShioriData['data'] as $key => $val):
                 ?>
                  <div class="panel">
                   <a href="planDetail.php<?php echo(!empty(appendGetParam())) ? appendGetParam().'&s_id='.$val['id'] : '?s_id='.$val['id']; ?>">
                       <div class="panel-head">
                          <p class="panel-title"><?php echo sanitize($val['title']); ?></p>
                           <img src="<?php echo showImg(sanitize($val['main_pic'])); ?>" alt="<?php sanitize($val['title']); ?>">
                       </div>
                       </a>
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
                   </div>
                <?php
                    endforeach;
                ?>
               </div>
               
               <?php pagination($currentPageNum,$dbShioriData['total_page']); ?>
                
                
            </section>
            
        </div>
        
        <!-- footer -->
        <?php
        require('footer.php'); 
        ?>
        
        
    
    
    



