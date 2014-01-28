(function($){
	
	effect='sliding',
	speed='slow',
	
	init=function(menu){
		subs=menu.find(".menu_level .menu_level");
		subs
			.css({
				'position':'absolute',
				'width':'auto'
			})
			.hide();
		items=menu.find(".menu_item");
		items.hover(mouseIn,mouseOut);
	},
	
	mouseIn=function(e){
		var sub=$(this).children(".menu_level");
		if(sub){
			showSub(sub);}
	},
	
	mouseOut=function(e){
		var sub=$(this).children(".menu_level");
		if(sub)
			hideSub(sub);
	},
	
	showSub=function(item){
		if(item.data("processShow"))
			return;
		item.data("processShow",true);
		
		switch (effect) {
		case 'fading':
			item.fadeIn(speed,function(){$(this).data("processShow",false);});
			break;
		case 'sliding':
			item.slideDown(speed,function(){$(this).data("processShow",false);});
			break;
		default:
			item.show(speed,function(){$(this).data("processShow",false);});
		};
	},
	
	hideSub=function(item){
		if(item.data("processHide"))
			return;
		item.data("processHide",true);
			
		switch (effect) {
		case 'fading':
			item.fadeOut(speed,function(){$(this).data("processHide",false);});
			break;
		case 'sliding':
			item.slideUp(speed,function(){$(this).data("processHide",false);});
			break;
		default:
			item.hide(speed,function(){$(this).data("processHide",false);});
		};
	},
	
	$.fn.yDropdown=function(opts){
		if(opts['effect'])
			effect=opts['effect'];
		if(opts['speed'])
			speed=opts['speed'];
		init($(this));
	}
	
})(jQuery);