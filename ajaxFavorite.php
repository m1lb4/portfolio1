<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// Ajax処理
//================================

//POSTがあり、ユーザーIDがあり、ログインしている場合
if(isset($_POST['shioriId']) && isset($_SESSION['user_id']) && isLogin()){
    debug('POST送信があります。');
    $s_id = $_POST['shioriId'];
    debug('しおりID：.$s_id');
    //例外処理
    try{
        //DBへ接続
        $dbh = dbConnect();
        //レコードがあるか検索
        $sql = 'SELECT * FROM favorite WHERE shiori_id = :s_id AND user_id = :u_id';
        $data = array(':s_id' => $s_id, ':u_id' => $_SESSION['user_id']);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $resultCount = $stmt->rowCount();
        debug($resultCount);
        //レコードが１件でもある場合
        if(!empty($resultCount)){
            //レコードを削除する
            $sql = 'DELETE FROM favorite WHERE shiori_id = :s_id AND user_id = :u_id';
            $data = array(':s_id' => $s_id, ':u_id' => $_SESSION['user_id']);
            //クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
        }else{
            //レコードを挿入する
            $sql = 'INSERT INTO favorite (shiori_id, user_id, create_date) VALUES(:s_id, :u_id, :date)';
            $data = array(':s_id' => $s_id, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
            //クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
        }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}
debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>