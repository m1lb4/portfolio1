<footer id="footer">
    Copyright minnanoshiori .All Rights Reserved.
</footer>

<script src="js/vendor/jquery-3.4.1.min.js"></script>

<script>
    $(function(){
        //フッターを最下部に固定
        var $ftr = $('#footer');
        if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
            $ftr.attr({'style':'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;' });
        }
        
        //メッセージを表示
        var $jsShowMsg = $('#js-show-msg');
        var msg = $jsShowMsg.text();
        if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
            $jsShowMsg.slideToggle('slow');
            setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 3000);
        }
        
        //画像ライブプレビュー
        var $dropArea = $('.area-drop');
        var $fileInput = $('.input-file');
        $dropArea.on('dragover',function(e){
            e.stopPropagation();
            e.preventDefault();
            $(this).css('border','3px #ccc dashed');
        });
        $dropArea.on('dragleave',function(e){
            e.stopPropagation();
            e.preventDefault();
            $(this).css('border','none');
        });
        $fileInput.on('change',function(e){
            $dropArea.css('border','none');
            var file = this.files[0],
                $img = $(this).siblings('.prev-img'),
                fileReader = new FileReader();
            
            fileReader.onload = function(event){
                $img.attr('src',event.target.result).show();
            };
            
            fileReader.readAsDataURL(file);
        })
        
        //お気に入り登録・削除
        var $favorite,
            favoritePlanId;
        $favorite = $('.js-click-favorite') || null//nullというのはnull値という値で、「変数の中身は空ですよ」と明示するためにつかう値
        favoritePlanId = $favorite.data('shioriid') || null;
        // 数値の0はfalseと判定されてしまう。product_idが0の場合もありえるので、0もtrueとする場合にはundefinedとnullを判定する
        if(favoritePlanId !== undefined && favoritePlanId !== null){
            $favorite.on('click',function(){
                var $this = $(this);
                $.ajax({
                    type: "POST",
                    url: "ajaxFavorite.php",
                    data: {shioriId : favoritePlanId}
                }).done(function(data){
                    console.log('Ajax Success');
                    //クラス属性をtoggleでつけ外しする
                    $this.toggleClass('active');
                }).fail(function(msg){
                    console.log('Ajax Error');
                });
            });
        }
        
        
    });
    
        
</script>
        
        
<script>
function popupImage() {
  var popup = document.getElementById('js-popup');
  if(!popup) return;

  var blackBg = document.getElementById('js-black-bg');

  var blackBg = document.getElementById('js-black-bg');
  var closeBtn = document.getElementById('js-close-btn');
  var showBtn = document.getElementById('js-delete-btn');

  closePopUp(blackBg);
  closePopUp(closeBtn);
  closePopUp(showBtn);
  function closePopUp(elem) {
    if(!elem) return;
    elem.addEventListener('click', function() {
      popup.classList.toggle('is-show');
    });
  }
}
popupImage();

</script>

</body>
</html>

