/* Tech Shop 3.8.11.49 - Events-parity measured rail and exact-row stability runtime. */
(function(W,D){
  'use strict';
  var ROOT=D.documentElement;
  function q(s,r){try{return(r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return[];}}
  function unique(a){return a.filter(function(x,i,z){return x&&z.indexOf(x)===i;});}
  function measureGrid(){
    var c=[q('.wp-theme-header-main > .container'),q('.wp-theme-header-main .container'),q('.wp-theme-site-header .container'),q('.wp-theme-site-footer .container')].filter(Boolean),best=null;
    c.some(function(el){var r=el.getBoundingClientRect();if(r.width>320&&r.width<=W.innerWidth+2){best=r;return true;}return false;});
    if(!best)return;
    ROOT.style.setProperty('--wpbb-v149-left',Math.max(16,Math.round(best.left))+'px');
    ROOT.style.setProperty('--wpbb-v149-right',Math.max(16,Math.round(W.innerWidth-best.right))+'px');
  }
  function isCell(n){return !!(n&&n.matches&&n.matches('.wpbb-column,.wp-block-wpbb-column,[class^="col-"],[class*=" col-"]'));}
  function closestCell(card,section){var n=card;while(n&&n!==section){if(isCell(n))return n;n=n.parentElement;}return card&&card.parentElement;}
  function closestRow(cell,section){var n=cell&&cell.parentElement;while(n&&n!==section){if(n.matches&&n.matches('.row,.wpbb-row'))return n;n=n.parentElement;}return n===section&&section.matches&&section.matches('.row,.wpbb-row')?section:null;}
  function clearOld(node){if(!node||!node.classList)return;['wpbb-v124-grid','wpbb-v125-grid','wpbb-v136-grid','wpbb-v136-cols-2','wpbb-v136-cols-3','wpbb-v136-cols-4','wpbb-v136-grid-cell','wpbb-v137-grid','wpbb-v137-cols-2','wpbb-v137-cols-3','wpbb-v137-cols-4','wpbb-v137-grid-cell','wpbb-v137-product-grid','wpbb-v137-product-card','wpbb-v140-grid','wpbb-v140-grid-cell','wpbb-v142-grid'].forEach(function(c){node.classList.remove(c);});}
  function clearAncestors(scope,host){var n=host&&host.parentElement;while(n&&n!==scope){clearOld(n);n=n.parentElement;}}
  function productCards(section){return unique(qa('.wpbb-catalogue-card,.product.type-product,.type-product.product,.product-card,.iws-product-card',section));}
  function repairCatalogue(section){
    if(!section||section.matches('[data-wpbb-v144-catalogue]'))return;
    clearOld(section);
    var cards=productCards(section);if(!cards.length)return;
    var groups=[];
    cards.forEach(function(card){var cell=closestCell(card,section),row=closestRow(cell,section);if(!cell||!row||!section.contains(row))return;var g=groups.find(function(x){return x.row===row;});if(!g){g={row:row,cells:[]};groups.push(g);}if(g.cells.indexOf(cell)<0)g.cells.push(cell);});
    groups.forEach(function(g){if(!g.cells.length)return;clearAncestors(section,g.row);clearOld(g.row);g.row.classList.add('wpbb-v149-grid','wpbb-v149-cols-4','wpbb-v149-product-grid');g.cells.forEach(function(cell){clearOld(cell);cell.classList.add('wpbb-v149-grid-cell');});});
    cards.forEach(function(card){clearOld(card);card.classList.add('wpbb-v149-product-card');});
  }
  function repairCatalogues(){qa('#wp-theme-main .wp-theme-home-product-catalogue').forEach(repairCatalogue);}
  function commonParent(a,b){if(!a||!b)return null;var p=a.parentElement,d=0;while(p&&d<7){if(p.contains(b))return p;p=p.parentElement;d++;}return null;}
  function repairCart(){var main=q('#wp-theme-main.wp-theme-woo-legacy--cart');if(!main)return;var form=q('.woocommerce-cart-form',main),tot=q('.cart-collaterals',main);if(!form||!tot)return;var host=commonParent(form,tot);if(host)host.classList.add('wpbb-v149-cart-grid');}
  var endpoints=['orders','downloads','edit-address','edit-account','payment-methods','lost-password','view-order','add-payment-method','delete-payment-method','set-default-payment-method','customer-logout'];
  function repairAccountLinks(){var main=q('#wp-theme-main.wp-theme-woo-legacy--account');if(!main)return;qa('a[href]',main).forEach(function(link){try{var url=new URL(link.href,W.location.href);if(url.origin!==W.location.origin)return;var parts=url.pathname.replace(/^\/+|\/+$/g,'').split('/').filter(Boolean);if(parts.length!==1||endpoints.indexOf(parts[0])===-1)return;url.pathname='/my-account/'+parts[0]+'/';link.href=url.toString();}catch(e){}});}
  function menuTrigger(menu){var li=menu&&menu.parentElement;if(!li)return null;try{return li.querySelector(':scope > a, :scope > button, :scope > .wp-theme-nav-link')||li;}catch(e){return li.querySelector('a,button')||li;}}
  function desktop(){return W.matchMedia('(min-width: 992px)').matches;}
  function positionMega(menu){if(!menu)return;if(!desktop()){menu.style.removeProperty('top');return;}var t=menuTrigger(menu);if(!t||!t.getBoundingClientRect)return;var r=t.getBoundingClientRect(),li=menu.parentElement,lr=li&&li.getBoundingClientRect?li.getBoundingClientRect():r,b=Math.ceil(Math.max(r.bottom,lr.bottom)-6);if(b>0){menu.style.setProperty('top',b+'px','important');ROOT.style.setProperty('--wpbb-v149-mega-top',b+'px');}}
  function positionMegas(){qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(positionMega);}
  function repairProductTabs(){
    qa('#wp-theme-main.wp-theme-woo-legacy--product .woocommerce-tabs').forEach(function(tabs){
      var links=qa('ul.tabs a[href^="#tab-"]',tabs),panels=qa('.wc-tab',tabs);if(!panels.length)return;
      function activate(hash){var target=q(hash,tabs)||panels[0];panels.forEach(function(panel){panel.classList.toggle('wpbb-v149-active',panel===target);});links.forEach(function(link){var li=link.closest('li'),on=link.getAttribute('href')==='#'+target.id;if(li)li.classList.toggle('active',on);link.setAttribute('aria-selected',on?'true':'false');});}
      var active=q('ul.tabs li.active a[href^="#tab-"]',tabs);activate(active?active.getAttribute('href'):'#'+panels[0].id);
      links.forEach(function(link){if(link.dataset.wpbbV149Tabs)return;link.dataset.wpbbV149Tabs='1';link.addEventListener('click',function(ev){ev.preventDefault();activate(link.getAttribute('href'));});});
    });
  }
  function upgradeGallery(){
    var base=(W.wpbbSuiteV149&&W.wpbbSuiteV149.galleryBase)||'';if(!base)return;
    var files=['gallery-workspace.jpg','gallery-audio.jpg','gallery-smart-home.jpg'];
    qa('#wp-theme-main .wp-theme-gallery-section .wpbb-swiper-slide__media img, #wp-theme-main .wp-theme-gallery-section .wp-theme-gallery-card img').forEach(function(img,i){var src=base+files[i%files.length];if(img.getAttribute('src')!==src){img.setAttribute('src',src);img.removeAttribute('srcset');img.removeAttribute('sizes');img.setAttribute('loading','lazy');img.setAttribute('decoding','async');}});
  }
  function run(){if(D.body)D.body.classList.add('wpbb-v149');measureGrid();repairCatalogues();repairCart();repairAccountLinks();repairProductTabs();upgradeGallery();positionMegas();}
  var timer=0;function schedule(ms){clearTimeout(timer);timer=W.setTimeout(run,ms||45);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',function(){schedule(45);},{once:true});else schedule(45);
  W.addEventListener('load',function(){run();W.setTimeout(run,300);W.setTimeout(run,1200);});
  W.addEventListener('resize',function(){schedule(100);},{passive:true});
  W.addEventListener('scroll',function(){W.requestAnimationFrame(positionMegas);},{passive:true});
  if(W.MutationObserver){var queued=false,obs=new MutationObserver(function(ms){if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;if(queued)return;queued=true;W.setTimeout(function(){queued=false;run();},65);});obs.observe(D.documentElement,{childList:true,subtree:true});W.setTimeout(function(){obs.disconnect();run();},6500);}
})(window,document);
