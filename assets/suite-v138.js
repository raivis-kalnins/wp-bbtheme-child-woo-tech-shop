/* Tech Shop 3.8.11.40 - accessible hero pager + small empty-node cleanup. */
(function(W,D){
  'use strict';
  function q(s,r){return (r||D).querySelector(s);}
  function qa(s,r){return Array.prototype.slice.call((r||D).querySelectorAll(s));}

  function heroBlocks(){return qa('#wp-theme-main .wpbb-swiper--hero');}
  function swiperEl(block){return block&&(block.matches&&block.matches('.swiper')?block:q('.swiper',block));}
  function liveSwiper(block,el){return (el&&el.swiper)||(block&&block.swiper)||null;}
  function uniqueSlides(el){
    if(!el)return [];
    var slides=qa('.swiper-wrapper > .swiper-slide',el);
    if(!slides.length)slides=qa('.swiper-slide',el);
    var seen={},out=[];
    slides.forEach(function(slide,index){
      if(slide.classList.contains('swiper-slide-duplicate'))return;
      var raw=slide.getAttribute('data-swiper-slide-index');
      var key=(raw===null||raw==='')?'dom-'+index:String(raw);
      if(seen[key])return;
      seen[key]=1;out.push(slide);
    });
    return out.length?out:slides;
  }
  function activeIndex(sw,count){
    if(!count)return 0;
    var n=sw&&typeof sw.realIndex==='number'?sw.realIndex:(sw&&typeof sw.activeIndex==='number'?sw.activeIndex:0);
    n=parseInt(n,10);if(!isFinite(n)||n<0)n=0;return n%count;
  }
  function paint(pager,sw,count){
    var active=activeIndex(sw,count);
    qa('.wpbb-v138-hero-pagination__bullet',pager).forEach(function(button,index){
      var on=index===active;
      button.classList.toggle('is-active',on);
      if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');
    });
  }
  function ensurePager(block){
    var el=swiperEl(block);if(!el)return;
    var count=uniqueSlides(el).length;if(count<2)return;
    var pager=q(':scope > .wpbb-v138-hero-pagination',el);
    if(!pager){
      pager=D.createElement('div');
      pager.className='wpbb-v138-hero-pagination';
      pager.setAttribute('role','group');
      pager.setAttribute('aria-label','Hero slides');
      el.appendChild(pager);
    }
    if(pager.children.length!==count){
      pager.innerHTML='';
      for(var i=0;i<count;i++){
        var b=D.createElement('button');
        b.type='button';
        b.className='wpbb-v138-hero-pagination__bullet';
        b.setAttribute('data-wpbb-v138-slide',String(i));
        b.setAttribute('aria-label','Go to hero slide '+(i+1)+' of '+count);
        pager.appendChild(b);
      }
    }
    if(!pager.dataset.wpbbV138Bound){
      pager.dataset.wpbbV138Bound='1';
      pager.addEventListener('click',function(event){
        var button=event.target&&event.target.closest?event.target.closest('[data-wpbb-v138-slide]'):null;
        if(!button)return;
        var index=parseInt(button.getAttribute('data-wpbb-v138-slide'),10)||0;
        var sw=liveSwiper(block,swiperEl(block));
        if(sw){
          try{
            if(typeof sw.slideToLoop==='function')sw.slideToLoop(index);
            else if(typeof sw.slideTo==='function')sw.slideTo(index);
          }catch(e){}
        }
        paint(pager,sw,count);
      });
    }
    var sw=liveSwiper(block,el);
    if(sw&&pager._wpbbV138Swiper!==sw){
      pager._wpbbV138Swiper=sw;
      if(typeof sw.on==='function'){
        var update=function(){paint(pager,sw,count);};
        try{sw.on('slideChange',update);sw.on('realIndexChange',update);sw.on('transitionEnd',update);}catch(e){}
      }
    }
    paint(pager,sw,count);
  }

  function cleanEmptyCatalogueTitles(){
    qa('#wp-theme-main .wp-theme-home-product-catalogue h1,#wp-theme-main .wp-theme-home-product-catalogue h2,#wp-theme-main .wp-theme-home-product-catalogue h3,#wp-theme-main .wp-theme-home-product-catalogue h4').forEach(function(h){
      if(String(h.textContent||'').trim()===''&&!q('img,svg,button,a,input,select',h))h.classList.add('wpbb-v137-hidden');
    });
  }
  function run(){heroBlocks().forEach(ensurePager);cleanEmptyCatalogueTitles();}
  function ready(fn){if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',fn,{once:true});else fn();}
  ready(function(){run();[120,450,1100,2200].forEach(function(ms){W.setTimeout(run,ms);});});
  W.addEventListener('load',run,{once:true});
})(window,document);
