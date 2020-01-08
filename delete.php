
               
               <!-- 削除ポップアップ -->
                <div class="popup" id="js-popup">
                      <div class="popup-inner">
                        <div class="close-btn" id="js-close-btn"><i class="fas fa-times"></i></div>
                        <form action="" method="post">
                            削除しますか？
                            <input type="submit" class="ok-btn" name="action" value="はい" action="mypage.php">
                            <?php
                            if(isset($_POST['action'])){
                                deleteShiori($s_id);
                            }
                            ?>
                        </form>
                        </div>
                        <div class="black-background" id="js-black-bg"></div>
                </div>