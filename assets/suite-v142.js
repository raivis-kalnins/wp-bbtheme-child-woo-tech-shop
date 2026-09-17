/* Tech Shop 3.8.11.42 - fresh-path Automotive-style grid marking.
 * Adds classes only. It never moves, clones, deletes or re-parents content.
 */
(function(W,D){
  'use strict';
  /* v148 owns the homepage deterministically from stored semantic row classes. */
  if (D.body && D.body.classList.contains('wpbb-v148')) return;
  var ROOT=D.documentElement;
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function txt(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function unique(arr){return arr.filter(function(x,i,a){return x&&a.indexOf(x)===i;});}
  function topLevel(arr){return unique(arr).filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}

  function measureGrid(){
    var candidates=[
      q('.wp-theme-header-main > .container'),
      q('.wp-theme-header-main .container'),
      q('.wp-theme-site-header .container'),
      q('.wp-theme-site-footer .container')
    ].filter(Boolean);
    var best=null;
    candidates.some(function(el){
      var r=el.getBoundingClientRect();
      if(r.width>320&&r.width<=W.innerWidth+2){best=r;return true;}
      return false;
    });
    if(!best)return;
    ROOT.style.setProperty('--wpbb-v137-left',Math.max(16,Math.round(best.left))+'px');
    ROOT.style.setProperty('--wpbb-v137-right',Math.max(16,Math.round(W.innerWidth-best.right))+'px');
  }

  function bestHost(scope,cards){
    cards=topLevel(cards||[]);
    if(!scope||cards.length<2)return null;
    var candidates=unique(cards.reduce(function(out,card){
      var node=card;
      for(var depth=0;node&&node!==scope&&depth<7;depth++,node=node.parentElement){
        if(node.matches&&node.matches('.row,.wpbb-row,.wpbb-v62-card-grid,.wpbb-sector-grid,.products,.wp-block-post-template,.tech-spec-strip,.tech-category-grid'))out.push(node);
      }
      return out;
    },[]));
    var best=null;
    candidates.forEach(function(host){
      var direct=kids(host).filter(function(child){return cards.some(function(card){return child===card||child.contains(card);});});
      if(direct.length<2)return;
      if(!best||direct.length>best.items.length)best={host:host,items:direct};
    });
    if(best)return best;
    var parent=cards[0]&&cards[0].parentElement;
    if(parent&&cards.every(function(card){return card.parentElement===parent;}))return{host:parent,items:cards.slice()};
    return null;
  }

  function markGrid(host,items,cols,extra){
    if(!host||!items||items.length<2)return;
    cols=Math.max(2,Math.min(4,cols||items.length));
    host.classList.add('wpbb-v137-grid','wpbb-v137-cols-'+cols);
    if(extra)host.classList.add(extra);
    items.forEach(function(item){item.classList.add('wpbb-v137-grid-cell');});
  }

  function markKnownRows(){
    var groups=[
      ['.wp-theme-services-section,.wp-theme-sector-services-section','.wp-theme-sector-card,.wpbb-icon-card',3],
      ['.wp-theme-industries-section,.wp-theme-sector-industries-section','.wp-theme-sector-card,.wpbb-icon-card',4],
      ['.wp-theme-case-studies-section','.wp-theme-case-card,.wpbb-icon-card,.wp-theme-sector-card',3],
      ['.wp-theme-process-section','.wp-theme-process-card,.wpbb-icon-card,.wp-theme-sector-card',3]
    ];
    groups.forEach(function(group){
      qa('#wp-theme-main '+group[0]).forEach(function(section){
        var cards=topLevel(qa(group[1],section));
        var pick=bestHost(section,cards);
        if(pick)markGrid(pick.host,pick.items,group[2]);
      });
    });

    qa('#wp-theme-main .wp-theme-sector-services,#wp-theme-main .wp-theme-sector-industries,#wp-theme-main .wp-theme-sector-process-grid,#wp-theme-main .wp-theme-case-grid,#wp-theme-main .wp-theme-case-studies-grid').forEach(function(host){
      if(host.closest('.swiper,.wpbb-swiper--hero'))return;
      var items=kids(host).filter(function(item){return txt(item)||q('article,.card,.wpbb-icon-card,.wp-theme-sector-card,img',item);});
      if(items.length>=2&&items.length<=8)markGrid(host,items,items.length===3?3:4);
    });
  }

  function markTechRows(){
    qa('#wp-theme-main .tech-spec-strip,#wp-theme-main .tech-category-grid').forEach(function(host){
      var items=kids(host).filter(function(item){return txt(item)||q('.tech-trust-item,.tech-category-card',item);});
      if(items.length>=2)markGrid(host,items,4,'wpbb-v142-tech-grid');
    });
  }

  function markStats(){
    qa('#wp-theme-main .wp-theme-sector-proof,#wp-theme-main .wp-theme-home-stats').forEach(function(section){
      var cards=topLevel(qa('.wp-theme-sector-proof__item,.wpbb-fun-fact',section));
      var pick=bestHost(section,cards);
      if(pick)markGrid(pick.host,pick.items,4,'wpbb-v137-stats-grid');
    });
  }

  function markProof(){
    qa('#wp-theme-main .wpbb-sector-proof-band').forEach(function(section){
      var cards=topLevel(qa('.wpbb-sector-proof-card,.wpbb-v137-sector-proof-card,.wpbb-icon-card,.wp-theme-sector-card',section));
      var pick=bestHost(section,cards);
      if(pick)markGrid(pick.host,pick.items,3,'wpbb-v137-proof-grid');
    });
  }

  function productCards(section){
    return topLevel(qa('.wpbb-catalogue-card,.product.type-product,.type-product.product,.product-card,.iws-product-card',section));
  }
  function markProducts(){
    qa('#wp-theme-main .wp-theme-home-product-catalogue').forEach(function(section){
      var cards=productCards(section);
      var pick=bestHost(section,cards);
      if(pick){
        markGrid(pick.host,pick.items,4,'wpbb-v137-product-grid');
        cards.forEach(function(card){card.classList.add('wpbb-v137-product-card');});
      }
      qa('h2,h3,h4',section).forEach(function(h){
        if(txt(h))return;
        var box=h.parentElement;
        if(box&&!q('article,.card,.wpbb-catalogue-card,img,form,button,input,select',box))box.classList.add('wpbb-v137-hidden');
        else h.classList.add('wpbb-v137-hidden');
      });
    });
  }

  function markEditorial(){
    qa('#wp-theme-main .wp-theme-insights-section .wp-block-post-template,#wp-theme-main .wp-theme-blog-preview-section .wp-block-post-template,#wp-theme-main .wp-theme-insights-grid,#wp-theme-main .wp-theme-blog-grid').forEach(function(host){
      var items=kids(host).filter(function(item){return txt(item)||q('article,img,a',item);});
      if(items.length>=2)markGrid(host,items,3,'wpbb-v137-blog-grid');
    });
    /* Match Automotive: only mark gallery cards when the section is not a swiper. */
    qa('#wp-theme-main .wp-theme-gallery-section').forEach(function(section){
      if(q('.swiper,.wpbb-swiper--gallery',section))return;
      var cards=topLevel(qa('.wp-theme-gallery-card',section));
      var pick=bestHost(section,cards);
      if(pick)markGrid(pick.host,pick.items,4,'wpbb-v137-gallery-grid');
    });
  }

  function run(){
    measureGrid();
    markKnownRows();
    markTechRows();
    markStats();
    markProof();
    markProducts();
    markEditorial();
  }

  var timer=0;
  function schedule(){clearTimeout(timer);timer=W.setTimeout(run,40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,300);W.setTimeout(run,1100);});
  W.addEventListener('resize',function(){clearTimeout(timer);timer=W.setTimeout(run,100);},{passive:true});
  var main=q('#wp-theme-main');
  if(main&&W.MutationObserver){
    var stopTimer;
    var observer=new MutationObserver(function(ms){
      if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;
      schedule();clearTimeout(stopTimer);stopTimer=W.setTimeout(function(){observer.disconnect();},4200);
    });
    observer.observe(main,{childList:true,subtree:true});
    stopTimer=W.setTimeout(function(){observer.disconnect();},4800);
  }
})(window,document);
