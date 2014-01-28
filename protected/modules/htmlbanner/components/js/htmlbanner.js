function htmlBanner(){
	
	this.currentIndex=0,
	this.slides=null,
	this.container=null,
	this.stop=false;
	
	this.init=function(){
		this.container=$("#htmlbanner");
		this.slides=this.container.children(".banner_slide");
		this.container.find(".slide_buttons a").bind(
				'click',
				{'obj':this},
				this.clickEvent
		);
	},
	
	this.switchSlide=function(index) {
		if(index==this.currentIndex)
			return;
		if(index+1>this.slides.size())
			index=0;
		currSlide=this.slides.eq(this.currentIndex);
		nextSlide=this.slides.eq(index);
		fake=currSlide.clone();
		fake.css({
			'position':'absolute',
			'top':0,
			'left':0,
			'width':'100%'
		}).appendTo(this.container);
		
		currSlide.hide();
		nextSlide.show();
		this.currentIndex=index;
		fake.fadeOut(2000,function() {$(this).remove();});
	},
	
	this.clickEvent=function(event) {
		event.preventDefault();
		btn=$(this);
		btnIndex=btn.prevAll().size();
		event.data.obj.stop=true;
		event.data.obj.switchSlide(btnIndex);
	},
	
	this.run=function(){
		obj=this;
		setTimeout(function(){obj.bannerLoop(obj);}, 10000);
	},
	
	this.bannerLoop=function(obj) {
		if(obj.stop) {
			obj.stop=false;
		} else
			obj.switchSlide(obj.currentIndex+1);
		setTimeout(function(){obj.bannerLoop(obj);}, 10000);
	};
}

$(document).ready(function() {
	var banner=new htmlBanner();
	banner.init();
	banner.run();
});