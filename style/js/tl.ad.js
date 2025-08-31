	// 當網頁載入完
	$(window).load(function(){
		var $win = $(window),
			$ad = $('#abgne_float_ad').css('opacity', 0).show(),	// 讓廣告區塊變透明且顯示出來
			_width = $ad.width(),
			_height = $ad.height(),
			_diffY = 320, _diffX = 5,	// 距離左及上方邊距
			_moveSpeed = 800;	// 移動的速度
		
		// 先把 #abgne_float_ad 移動到定點
		$ad.css({
			top: _diffY,	// 往上
			left:  _diffX,	// 往左
			opacity: 1
		});
		
		// 幫網頁加上 scroll 及 resize 事件
		$win.bind('scroll resize', function(){
			var $this = $(this);
			
			// 控制 #abgne_float_ad 的移動
			$ad.stop().animate({
				top: $this.scrollTop() + _diffY,	// 往上
				left: $this.scrollLeft() + _diffX	// 往左
			}, _moveSpeed);
		}).scroll();	// 觸發一次 scroll()
		
		// 關閉廣告
		$('#abgne_float_ad .abgne_close_ad').click(function(){
			$ad.hide();
		});
	});