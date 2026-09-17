/* Tech Shop 3.8.11.40 - deterministic homepage and WooCommerce repair. */
(function(W,D){
  'use strict';
  var cfg=W.wpbbSuiteV132||{};
  if(String(cfg.themeSlug||'').toLowerCase().indexOf('tech')===-1)return;
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function txt(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function norm(v){return String(v||'').toLowerCase().replace(/[^a-z0-9]+/g,' ').replace(/\s+/g,' ').trim();}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function unique(arr){return arr.filter(function(x,i,a){return x&&a.indexOf(x)===i;});}
  function topLevel(arr){return unique(arr).filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}
  function markTheme(){if(D.body)D.body.classList.add('wpbb-v132-theme-tech');}

  function commonHost(cells){
    if(cells.length<2)return null;
    var node=cells[0].parentElement,depth=0;
    while(node&&node!==D.body&&depth<6){
      var direct=kids(node).filter(function(k){return cells.some(function(c){return k===c||k.contains(c);});});
      if(direct.length>=Math.min(3,cells.length))return node;
      node=node.parentElement;depth++;
    }
    return null;
  }

  function repairProcess(){
    qa('#wp-theme-main .wp-theme-process-section,#wp-theme-main .wp-theme-sector-process-grid').forEach(function(section){
      var badges=qa('.wp-theme-process-badge,.wp-block-wpbb-badge.wp-theme-process-badge',section).slice(0,6);
      var cells=[];
      badges.forEach(function(b,i){
        b.classList.add('wpbb-v132-process-badge');
        if(!txt(b)||txt(b).length>4)b.textContent=String(i+1).padStart(2,'0');
        var cell=b.closest('.wpbb-column,.wp-block-wpbb-column,[class*="col-"]')||b.parentElement;
        if(cell&&cells.indexOf(cell)===-1)cells.push(cell);
        var card=cell&&(q('.wp-theme-process-card,.wp-theme-sector-card,.wpbb-icon-card,.wpbb-card,.card',cell)||cell.firstElementChild);
        if(card){
          card.classList.add('wpbb-v132-process-card');
          if(!card.contains(b))card.insertBefore(b,card.firstChild||null);
        }
      });
      var host=commonHost(cells)||q('.row,.wpbb-row',section);
      if(host&&cells.length>=2)host.classList.add('wpbb-v132-process-grid');
    });
  }

  function productCards(section){
    return topLevel(qa('.wpbb-catalogue-card,.product.type-product,.type-product.product,.product-card,.iws-product-card',section));
  }
  function productHost(section,cards){
    var candidates=[];
    qa('.row,.wpbb-row,ul.products,.products',section).forEach(function(host){
      var direct=kids(host).filter(function(k){return cards.some(function(c){return k===c||k.contains(c);});});
      if(direct.length>=2)candidates.push({host:host,count:direct.length});
    });
    if(candidates.length){candidates.sort(function(a,b){return b.count-a.count;});return candidates[0].host;}
    var p=cards[0]&&cards[0].parentElement,depth=0;
    while(p&&p!==section.parentElement&&depth<6){
      if(kids(p).filter(function(k){return cards.some(function(c){return k===c||k.contains(c);});}).length>=2)return p;
      p=p.parentElement;depth++;
    }
    return null;
  }
  function repairCatalogue(){
    qa('#wp-theme-main .wp-theme-home-product-catalogue').forEach(function(section){
      var cards=productCards(section);if(cards.length<2)return;
      var host=productHost(section,cards);if(host)host.classList.add('wpbb-v132-product-grid');
      cards.forEach(function(c){c.classList.add('wpbb-v132-product-card');});
    });
  }

  function repairShop(){
    qa('#wp-theme-main.wp-theme-woo-legacy--catalog ul.products,#wp-theme-main.wp-theme-woo-legacy--catalog .products').forEach(function(g){
      if(qa(':scope > li.product',g).length>1)g.classList.add('wpbb-v132-shop-grid');
    });
    qa('#wp-theme-main .iws-compare-icon').forEach(function(icon){icon.setAttribute('aria-hidden','true');});
  }

  function repairCart(){
    var main=q('#wp-theme-main.wp-theme-woo-legacy--cart');if(!main)return;
    var form=q('.woocommerce-cart-form',main),tot=q('.cart-collaterals',main);
    if(form)form.classList.add('wpbb-v132-cart-form');
    if(tot)tot.classList.add('wpbb-v132-cart-totals');
  }

  function safeCheckout(){
    var main=q('#wp-theme-main.wp-theme-woo-legacy--checkout');if(!main)return;
    var form=q('form.checkout.woocommerce-checkout',main);if(!form)return;
    qa('.wpbb-v128-newsletter-consent,.wp-newslatter-campaigns-consent,.wp-newsletter-campaigns-consent,[class*="newsletter"][class*="consent"],[class*="newslatter"][class*="consent"]',form).forEach(function(n){
      var box=n.closest('.form-row,.woocommerce-form-row,label,p')||n;
      if(box&&!q('#payment,#order_review',box))box.remove();
    });
    qa('label,p',form).forEach(function(n){
      var v=norm(txt(n));
      if(v.indexOf('i agree to receive occasional email updates')===-1&&v.indexOf('i agree to receive email updates and can unsubscribe')===-1&&v.indexOf('send me wordpress newsletter updates')===-1&&v.indexOf('newsletter updates by email')===-1)return;
      var box=n.closest('.form-row,.woocommerce-form-row,label,p')||n;
      if(box&&!q('#payment,#order_review',box))box.remove();
    });
  }

  function run(){markTheme();repairProcess();repairCatalogue();repairShop();repairCart();safeCheckout();}
  var queued=false;
  function schedule(){if(queued)return;queued=true;W.setTimeout(function(){queued=false;run();},24);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,250);W.setTimeout(run,900);});
  if(W.MutationObserver){
    var observer=new MutationObserver(function(ms){if(ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))schedule();});
    observer.observe(D.documentElement,{childList:true,subtree:true});
    W.setTimeout(function(){observer.disconnect();},5000);
  }
})(window,document);
