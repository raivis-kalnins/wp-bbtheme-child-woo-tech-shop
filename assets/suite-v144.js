/* Tech Shop 3.8.11.44 — DB/Events-informed class-only layout recovery. */
(function(W,D){
  'use strict';
  var ROOT=D.documentElement;
  function q(s,r){try{return(r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function unique(arr){return arr.filter(function(x,i,a){return x&&a.indexOf(x)===i;});}

  function measure(){
    var candidates=[q('.wp-theme-header-main > .container'),q('.wp-theme-header-main .container'),q('.wp-theme-site-header .container'),q('.wp-theme-site-footer .container')].filter(Boolean),best=null;
    candidates.some(function(el){var r=el.getBoundingClientRect();if(r.width>320&&r.width<=W.innerWidth+2){best=r;return true;}return false;});
    if(!best)return;
    ROOT.style.setProperty('--wpbb-v137-left',Math.max(16,Math.round(best.left))+'px');
    ROOT.style.setProperty('--wpbb-v137-right',Math.max(16,Math.round(W.innerWidth-best.right))+'px');
  }

  /* Events v139/v140 exact-row catalogue ownership recovery. */
  function isCell(n){return !!(n&&n.matches&&n.matches('.wpbb-column,.wp-block-wpbb-column,[class^="col-"],[class*=" col-"]'));}
  function closestCell(card,section){var n=card;while(n&&n!==section){if(isCell(n))return n;n=n.parentElement;}return card&&card.parentElement;}
  function closestRow(cell,section){var n=cell&&cell.parentElement;while(n&&n!==section){if(n.matches&&n.matches('.row,.wpbb-row'))return n;n=n.parentElement;}return n===section&&section.matches&&section.matches('.row,.wpbb-row')?section:null;}
  function clearOldGridClasses(node){
    if(!node||!node.classList)return;
    ['wpbb-v124-grid','wpbb-v125-grid','wpbb-v136-grid','wpbb-v136-cols-2','wpbb-v136-cols-3','wpbb-v136-cols-4','wpbb-v136-grid-cell','wpbb-v137-grid','wpbb-v137-cols-2','wpbb-v137-cols-3','wpbb-v137-cols-4','wpbb-v137-grid-cell','wpbb-v137-product-grid','wpbb-v137-product-card'].forEach(function(c){node.classList.remove(c);});
  }
  function clearOwnedAncestors(scope,host){var n=host&&host.parentElement;while(n&&n!==scope){clearOldGridClasses(n);n=n.parentElement;}}
  function productCards(section){return unique(qa('.wpbb-catalogue-card,.product.type-product,.type-product.product,.product-card,.iws-product-card',section));}
  function repairCatalogue(section){
    if(!section||section.matches('[data-wpbb-v144-catalogue]'))return;
    section.classList.add('wpbb-v144-catalogue');
    clearOldGridClasses(section);
    qa('.wpbb-v144-catalogue-grid',section).forEach(function(n){n.classList.remove('wpbb-v144-catalogue-grid');});
    qa('.wpbb-v144-catalogue-cell',section).forEach(function(n){n.classList.remove('wpbb-v144-catalogue-cell');});
    qa('.wpbb-v144-empty-catalogue-heading',section).forEach(function(n){n.classList.remove('wpbb-v144-empty-catalogue-heading');});

    var cards=productCards(section);if(!cards.length)return;
    var groups=[];
    cards.forEach(function(card){
      var cell=closestCell(card,section),row=closestRow(cell,section);if(!cell||!row||!section.contains(row))return;
      var group=groups.find(function(g){return g.row===row;});if(!group){group={row:row,cells:[]};groups.push(group);}
      if(group.cells.indexOf(cell)<0)group.cells.push(cell);
    });
    groups.forEach(function(group){
      if(group.cells.length<1)return;
      clearOwnedAncestors(section,group.row);clearOldGridClasses(group.row);group.row.classList.add('wpbb-v144-catalogue-grid');
      group.cells.forEach(function(cell){clearOldGridClasses(cell);cell.classList.add('wpbb-v144-catalogue-cell');});
    });
    cards.forEach(function(card){clearOldGridClasses(card);card.classList.add('wpbb-v144-catalogue-card');});
    qa('h1,h2,h3,h4,h5,h6',section).forEach(function(h){if(String(h.textContent||'').trim())return;var cell=closestCell(h,section);if(cell&&!q('article,.card,.wpbb-catalogue-card,img,form,button,input,select',cell))cell.classList.add('wpbb-v144-empty-catalogue-heading');});
  }
  function repairCatalogues(){qa('#wp-theme-main .wp-theme-home-product-catalogue').forEach(repairCatalogue);}

  function repairAccountLinks(){
    var main=q('#wp-theme-main.wp-theme-woo-legacy--account');if(!main)return;
    var endpoints=['orders','downloads','edit-address','edit-account','payment-methods','lost-password','view-order','add-payment-method','delete-payment-method','set-default-payment-method','customer-logout'];
    qa('a[href]',main).forEach(function(link){try{var url=new URL(link.href,W.location.href);if(url.origin!==W.location.origin)return;var parts=url.pathname.replace(/^\/+|\/+$/g,'').split('/').filter(Boolean);if(parts.length!==1||endpoints.indexOf(parts[0])===-1)return;url.pathname='/my-account/'+parts[0]+'/';link.href=url.toString();}catch(e){}});
  }

  function run(){measure();repairCatalogues();repairAccountLinks();}
  var timer=0;function schedule(delay){clearTimeout(timer);timer=W.setTimeout(run,typeof delay==='number'?delay:40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',function(){schedule(45);},{once:true});else schedule(45);
  W.addEventListener('load',function(){run();W.setTimeout(run,320);W.setTimeout(run,1250);W.setTimeout(run,1900);});
  W.addEventListener('resize',function(){schedule(100);},{passive:true});
  if(W.MutationObserver){
    var queued=false,observer=new MutationObserver(function(ms){if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;if(queued)return;queued=true;W.setTimeout(function(){queued=false;run();},70);});
    observer.observe(D.documentElement,{childList:true,subtree:true});W.setTimeout(function(){observer.disconnect();run();},6500);
  }
})(window,document);
