/* Tech Shop 3.8.11.43 — small front-end repairs only; no DOM moves/reparenting. */
(function(W,D){
  'use strict';
  function q(s,r){try{return (r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return [];}}

  function repairProductTabs(){
    qa('#wp-theme-main.wp-theme-woo-legacy--product .woocommerce-tabs').forEach(function(tabs){
      var links=qa('ul.tabs a[href^="#tab-"]',tabs);
      var panels=qa('.wc-tab',tabs);
      if(!panels.length)return;
      function activate(hash){
        var target=q(hash,tabs) || panels[0];
        panels.forEach(function(panel){panel.classList.toggle('wpbb-v143-active',panel===target);});
        links.forEach(function(link){
          var li=link.closest('li');
          if(li)li.classList.toggle('active',link.getAttribute('href')==='#'+target.id);
          link.setAttribute('aria-selected',link.getAttribute('href')==='#'+target.id?'true':'false');
        });
      }
      var active=q('ul.tabs li.active a[href^="#tab-"]',tabs);
      activate(active ? active.getAttribute('href') : '#'+panels[0].id);
      links.forEach(function(link){
        link.addEventListener('click',function(ev){
          ev.preventDefault();
          activate(link.getAttribute('href'));
        });
      });
    });
  }

  function upgradeGallery(){
    var base=(W.wpbbSuiteV143&&W.wpbbSuiteV143.galleryBase)||'';
    if(!base)return;
    var files=['gallery-workspace.jpg','gallery-audio.jpg','gallery-smart-home.jpg'];
    qa('#wp-theme-main .wp-theme-gallery-section .wpbb-swiper-slide__media img, #wp-theme-main .wp-theme-gallery-section .wp-theme-gallery-card img').forEach(function(img,i){
      var src=base+files[i%files.length];
      if(img.getAttribute('src')!==src){
        img.setAttribute('src',src);
        img.removeAttribute('srcset');
        img.removeAttribute('sizes');
        img.setAttribute('loading','lazy');
        img.setAttribute('decoding','async');
      }
    });
  }

  function run(){repairProductTabs();upgradeGallery();}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',run,{once:true}); else run();
  W.addEventListener('load',function(){run();W.setTimeout(run,300);});
})(window,document);
