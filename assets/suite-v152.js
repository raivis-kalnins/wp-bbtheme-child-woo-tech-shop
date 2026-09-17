/* Tech Shop 3.8.11.52 - hero pager only; no layout ownership. */
(function(W,D){
  'use strict';
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function swiperEl(block){return block&&(block.matches&&block.matches('.swiper')?block:q('.swiper',block));}
  function liveSwiper(block,el){return(el&&el.swiper)||(block&&block.swiper)||null;}
  function slides(el){
    if(!el)return[];
    var all=qa('.swiper-wrapper > .swiper-slide',el),seen={},out=[];
    all.forEach(function(slide,index){
      if(slide.classList.contains('swiper-slide-duplicate'))return;
      var raw=slide.getAttribute('data-swiper-slide-index');
      var key=(raw===null||raw==='')?'dom-'+index:String(raw);
      if(seen[key])return;
      seen[key]=1;out.push(slide);
    });
    return out.length?out:all;
  }
  function indexOf(sw,el,count){
    if(!count)return 0;
    var n=sw&&typeof sw.realIndex==='number'?sw.realIndex:(sw&&typeof sw.activeIndex==='number'?sw.activeIndex:null);
    if(n===null){
      var active=q('.swiper-slide-active',el);
      if(active){
        var raw=active.getAttribute('data-swiper-slide-index');
        n=(raw!==null&&raw!=='')?parseInt(raw,10):slides(el).indexOf(active);
      }
    }
    n=parseInt(n,10);if(!isFinite(n)||n<0)n=0;
    return n%count;
  }
  function paint(pager,sw,el,count){
    var active=indexOf(sw,el,count);
    qa('.wpbb-v152-hero-pagination__bullet',pager).forEach(function(button,index){
      var on=index===active;
      button.classList.toggle('is-active',on);
      if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');
    });
  }
  function ensure(block){
    var el=swiperEl(block);if(!el)return;
    var count=slides(el).length;
    if(count<2){block.classList.remove('wpbb-v152-has-pagination');return;}
    block.classList.add('wpbb-v152-has-pagination');
    var pager=q(':scope > .wpbb-v152-hero-pagination',el);
    if(!pager){
      pager=D.createElement('div');
      pager.className='wpbb-v152-hero-pagination';
      pager.setAttribute('role','group');
      pager.setAttribute('aria-label','Hero slides');
      el.appendChild(pager);
    }
    if(pager.children.length!==count){
      pager.innerHTML='';
      for(var i=0;i<count;i++){
        var b=D.createElement('button');
        b.type='button';
        b.className='wpbb-v152-hero-pagination__bullet';
        b.setAttribute('data-wpbb-v152-slide',String(i));
        b.setAttribute('aria-label','Go to hero slide '+(i+1)+' of '+count);
        pager.appendChild(b);
      }
    }
    if(!pager.dataset.wpbbV152Bound){
      pager.dataset.wpbbV152Bound='1';
      pager.addEventListener('click',function(event){
        var button=event.target&&event.target.closest?event.target.closest('[data-wpbb-v152-slide]'):null;
        if(!button)return;
        var target=parseInt(button.getAttribute('data-wpbb-v152-slide'),10)||0;
        var sw=liveSwiper(block,el);
        if(sw){
          try{
            if(typeof sw.slideToLoop==='function')sw.slideToLoop(target);
            else if(typeof sw.slideTo==='function')sw.slideTo(target);
          }catch(e){}
        }
        paint(pager,sw,el,count);
      });
    }
    var sw=liveSwiper(block,el);
    if(sw&&pager._wpbbV152Swiper!==sw){
      pager._wpbbV152Swiper=sw;
      if(typeof sw.on==='function'){
        var update=function(){paint(pager,sw,el,count);};
        try{sw.on('slideChange',update);sw.on('realIndexChange',update);sw.on('transitionEnd',update);}catch(e){}
      }
    }
    paint(pager,sw,el,count);
  }
  function run(){
    if(D.body)D.body.classList.add('wpbb-v152');
    qa('#wp-theme-main .wpbb-swiper--hero').forEach(ensure);
  }
  var timer=0;
  function schedule(ms){clearTimeout(timer);timer=W.setTimeout(run,ms||45);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',function(){schedule(40);},{once:true});else schedule(40);
  W.addEventListener('load',function(){run();W.setTimeout(run,250);W.setTimeout(run,900);});
  W.addEventListener('resize',function(){schedule(90);},{passive:true});
  if(W.MutationObserver){
    var queued=false,obs=new MutationObserver(function(ms){
      if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;
      if(queued)return;queued=true;
      W.setTimeout(function(){queued=false;run();},70);
    });
    obs.observe(D.documentElement,{childList:true,subtree:true});
    W.setTimeout(function(){obs.disconnect();run();},6000);
  }
})(window,document);
