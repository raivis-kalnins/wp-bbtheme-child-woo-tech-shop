/* Tech Shop 3.8.11.50 - targeted homepage section repair. */
(function(W,D){
  'use strict';
  var CFG=W.wpbbSuiteV150||{};
  /* v150 owns homepage gallery media; stop v149's delayed three-image cycler. */
  if(W.wpbbSuiteV149)W.wpbbSuiteV149.galleryBase='';
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}

  function repairTrust(){
    qa('#wp-theme-main .tech-spec-strip').forEach(function(row){
      row.classList.add('wpbb-v150-trust-grid');
      var shell=row.parentElement;
      if(shell)shell.classList.add('wpbb-v150-trust-shell');
      qa(':scope > *',row).forEach(function(cell){
        cell.style.removeProperty('width');
        cell.style.removeProperty('max-width');
        cell.style.removeProperty('margin-left');
        cell.style.removeProperty('margin-right');
      });
    });
  }

  function repairGallery(){
    qa('#wp-theme-main .wp-theme-gallery-section').forEach(function(section){
      var root=q('.wpbb-swiper--gallery',section)||q('.swiper',section);
      if(!root)return;
      var swiper=q('.swiper',root)||root;
      var wrapper=q('.swiper-wrapper',swiper);
      if(!wrapper)return;
      var slides=qa(':scope > .swiper-slide',wrapper).filter(function(slide){return !slide.classList.contains('swiper-slide-duplicate');});
      if(slides.length<2)return;
      root.classList.add('wpbb-v150-gallery-static');
      swiper.classList.add('wpbb-v150-gallery-static');
      wrapper.classList.add('wpbb-v150-gallery-grid');
      wrapper.style.removeProperty('transform');
      wrapper.style.removeProperty('transition-duration');
      wrapper.style.removeProperty('height');
      slides.forEach(function(slide,i){
        slide.style.removeProperty('width');
        slide.style.removeProperty('margin-right');
        slide.style.removeProperty('transform');
        slide.style.removeProperty('height');
        var img=q('.wpbb-swiper-slide__media img,img',slide);
        if(img&&Array.isArray(CFG.gallery)&&CFG.gallery[i]){
          img.setAttribute('src',CFG.gallery[i]);
          img.removeAttribute('srcset');
          img.removeAttribute('sizes');
          img.setAttribute('loading','lazy');
          img.setAttribute('decoding','async');
        }
      });
      qa('.swiper-slide-duplicate',wrapper).forEach(function(slide){slide.style.display='none';});
      try{
        if(swiper.swiper&&swiper.swiper.autoplay&&typeof swiper.swiper.autoplay.stop==='function')swiper.swiper.autoplay.stop();
      }catch(e){}
    });
  }

  function repairProcess(){
    qa('#wp-theme-main .wp-theme-sector-process-grid .wp-theme-process-badge').forEach(function(badge){
      badge.style.removeProperty('width');
      badge.style.removeProperty('height');
      badge.style.removeProperty('max-width');
      badge.style.removeProperty('max-height');
    });
  }

  function run(){
    if(D.body)D.body.classList.add('wpbb-v150');
    repairTrust();
    repairGallery();
    repairProcess();
  }
  var timer=0;
  function schedule(ms){clearTimeout(timer);timer=W.setTimeout(run,ms||30);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',function(){schedule(30);},{once:true});else schedule(30);
  W.addEventListener('load',function(){run();W.setTimeout(run,250);W.setTimeout(run,900);});
  W.addEventListener('resize',function(){schedule(90);},{passive:true});
  if(W.MutationObserver){
    var queued=false,obs=new MutationObserver(function(ms){
      if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;
      if(queued)return;queued=true;
      W.setTimeout(function(){queued=false;run();},60);
    });
    obs.observe(D.documentElement,{childList:true,subtree:true});
    W.setTimeout(function(){obs.disconnect();run();},5000);
  }
})(window,document);
