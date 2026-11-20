/* Lentera-theme + CMS backend wiring (semua halaman publik) */
(function(){
"use strict";
function $(s,c){return (c||document).querySelector(s);}
function $$(s,c){return Array.prototype.slice.call((c||document).querySelectorAll(s));}

/* tahun berjalan */
var yr=$('#year'); if(yr) yr.textContent=new Date().getFullYear();

/* bayangan masthead */
var mast=$('#masthead');
function sh(){ if(mast) mast.classList.toggle('scrolled',window.scrollY>8); }
window.addEventListener('scroll',sh,{passive:true}); sh();

/* overlay indeks menu */
var ov=$('#menuOverlay'),
    openers=[$('#menuBtn'),$('#burger')].filter(Boolean),
    closer=$('#menuClose');
function setOv(open){
  if(!ov) return;
  ov.classList.toggle('open',open);
  ov.setAttribute('aria-hidden',String(!open));
  document.body.style.overflow=open?'hidden':'';
  if(open&&closer) closer.focus();
}
openers.forEach(function(b){ b.addEventListener('click',function(){setOv(true);}); });
if(closer) closer.addEventListener('click',function(){setOv(false);});
if(ov) ov.addEventListener('click',function(e){
  if(e.target===ov||e.target.closest('a')) setOv(false);
});
document.addEventListener('keydown',function(e){
  if(e.key==='Escape'){ setOv(false); closeLb(); }
});

/* tandai halaman aktif (berbasis body[data-page]) */
var page=document.body.getAttribute('data-page');
if(page){
  $$('.nav a').forEach(function(a){ a.classList.toggle('active',a.getAttribute('data-p')===page); });
  $$('.ov-grid a').forEach(function(a){ a.classList.toggle('on',a.getAttribute('data-p')===page); });
}
/* umpan balik instan saat link nav diklik */
$$('.nav a').forEach(function(a){
  a.addEventListener('click',function(){
    $$('.nav a').forEach(function(x){ x.classList.remove('active'); });
    a.classList.add('active');
  });
});

/* ticker : gandakan isi agar loop mulus */
var track=$('#tickerTrack'); if(track) track.innerHTML+=track.innerHTML;

/* tab saring generik (client-side) : hanya tab ber-data-f (agenda, prestasi, dsb.) */
$$('.tab[data-f]').forEach(function(t){
  t.addEventListener('click',function(){
    var sec=t.closest('section'); if(!sec) return;
    $$('.tab',sec).forEach(function(x){ x.classList.remove('on'); x.removeAttribute('aria-selected'); });
    t.classList.add('on');
    t.setAttribute('aria-selected','true');
    var f=t.getAttribute('data-f');
    $$('[data-cat]',sec).forEach(function(r){
      r.classList.toggle('hide',f!=='semua'&&r.getAttribute('data-cat')!==f);
    });
  });
});

/* tab navigasi server-side (filter kategori berita) : aktif instan + kunci klik ganda */
$$('a.tab[href]').forEach(function(t){
  if(t.hasAttribute('data-f')) return;
  t.addEventListener('click',function(e){
    if(e.button!==0||e.metaKey||e.ctrlKey||e.shiftKey||e.altKey) return;
    var wrap=t.closest('.tabs');
    if(wrap&&wrap.classList.contains('is-loading')){ e.preventDefault(); return; }
    var sec=t.closest('section');
    if(sec) $$('.tab',sec).forEach(function(x){ x.classList.remove('on'); x.removeAttribute('aria-current'); });
    t.classList.add('on');
    t.setAttribute('aria-current','page');
    if(wrap){ wrap.classList.add('is-loading'); wrap.setAttribute('aria-busy','true'); }
  });
});

/* reveal saat scroll */
var rvs=$$('.rv');
if('IntersectionObserver' in window&&rvs.length){
  var io=new IntersectionObserver(function(es){
    es.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); } });
  },{threshold:.12});
  rvs.forEach(function(el){ io.observe(el); });
}else{
  rvs.forEach(function(el){ el.classList.add('in'); });
}

/* toast */
var toast=$('#toast'),tmsg=$('#toastMsg'),tt;
function showToast(m){
  if(!toast) return;
  tmsg.textContent=m;
  toast.classList.add('show');
  clearTimeout(tt); tt=setTimeout(function(){ toast.classList.remove('show'); },3600);
}

/* samakan token CSRF form dengan cookie (token dirotasi server tiap POST,
   termasuk saat validasi gagal — tanpa ini kirim ulang = 403) */
function syncCsrfToken(){
  var fields=$$('[name="csrf_test_name"]');
  if(!fields.length) return;
  var match=document.cookie.match(/(?:^|;\s*)csrf_cookie_name=([^;]*)/);
  if(!match) return;
  var token;
  try{ token=decodeURIComponent(match[1]); }catch(e){ return; }
  fields.forEach(function(f){ f.value=token; });
}
syncCsrfToken();
window.addEventListener('pageshow',syncCsrfToken);

/* formulir kontak -> POST /contact/send (AJAX, dukung dua varian field) */
$$('#contactForm').forEach(function(cf){
  cf.addEventListener('submit',function(e){
    e.preventDefault();
    syncCsrfToken();
    var fd=new FormData(cf);
    // Petakan varian theme (nama/hp/keperluan/pesan) ke backend bila backend names kosong
    function val(){ for(var i=0;i<arguments.length;i++){ var v=fd.get(arguments[i]); if(v&&String(v).trim()!=='') return v; } return ''; }
    var name=val('name','nama','fNama'), email=val('email','mail','cMail'),
        subject=val('subject','keperluan','fPerlu'), message=val('message','pesan','fPesan','cIsi');
    if(!String(name).trim()||!String(message).trim()){
      showToast('Mohon lengkapi nama dan pesan.'); return;
    }
    if(!String(subject).trim()){ fd.set('subject','Pesan dari website'); }
    var btn=cf.querySelector('button[type="submit"]');
    var original=btn?btn.innerHTML:'';
    if(btn){ btn.disabled=true; btn.innerHTML='Mengirim…'; }
    fetch(cf.action,{
      method:'POST',
      body:fd,
      headers:{'X-Requested-With':'XMLHttpRequest'}
    }).then(function(r){ return r.json(); }).then(function(res){
      syncCsrfToken();
      if(res&&res.success){
        showToast(res.message||('Terima kasih, '+String(name).trim().split(' ')[0]+'. Pesan Anda sudah kami terima.'));
        cf.reset();
      }else{
        var errText=res&&res.errors?Object.values(res.errors).join(', '):'Gagal mengirim. Coba lagi.';
        showToast(errText);
      }
      if(btn){ btn.disabled=false; btn.innerHTML=original; }
    }).catch(function(){
      syncCsrfToken();
      showToast('Gagal mengirim. Periksa koneksi lalu coba lagi.');
      if(btn){ btn.disabled=false; btn.innerHTML=original; }
    });
  });
});

/* akordeon FAQ */
$$('.faq-q').forEach(function(q){
  q.addEventListener('click',function(){
    var item=q.parentElement, list=q.closest('.faq')||q.closest('section'),
        ans=$('.faq-a',item), was=item.classList.contains('open');
    $$('.faq-item.open',list).forEach(function(o){
      o.classList.remove('open');
      $('.faq-q',o).setAttribute('aria-expanded','false');
      $('.faq-a',o).style.maxHeight=null;
    });
    if(!was){
      item.classList.add('open');
      q.setAttribute('aria-expanded','true');
      ans.style.maxHeight=ans.scrollHeight+'px';
    }
  });
});
// Buka item pertama bila tidak ada yang open (khusus halaman FAQ CMS)
(function(){
  var faq=$('.faq');
  if(faq&&!$('.faq-item.open',faq)){
    var first=$('.faq-item',faq);
    if(first){
      first.classList.add('open');
      var q=$('.faq-q',first),a=$('.faq-a',first);
      if(q) q.setAttribute('aria-expanded','true');
      if(a) a.style.maxHeight=a.scrollHeight+'px';
    }
  }
})();

/* cari berkas unduhan */
var ds=$('#dlSearch');
if(ds) ds.addEventListener('input',function(){
  var k=ds.value.trim().toLowerCase(), n=0;
  $$('.dl-row').forEach(function(r){
    var hit=r.textContent.toLowerCase().indexOf(k)>-1;
    r.style.display=hit?'':'none';
    if(hit) n++;
  });
  var c=$('#dlCount'); if(c) c.textContent=n+' berkas ditemukan';
});
$$('.dl-btn').forEach(function(b){
  b.addEventListener('click',function(e){
    var href=b.getAttribute('href')||'';
    // Hanya cegah bila href dummy; link asli CMS dibiarkan mengunduh
    if(href===''||href==='#'){ e.preventDefault(); showToast('Berkas demo — dokumen resmi tersedia di TU sekolah.'); }
  });
});

/* lightbox galeri (dukung SVG ilustrasi maupun <img> foto CMS) */
var lb=$('#lightbox'),lbFig=$('#lbFig'),lbCap=$('#lbCap');
function closeLb(){
  if(!lb) return;
  lb.classList.remove('open');
  lb.setAttribute('aria-hidden','true');
  document.body.style.overflow='';
}
$$('.g-item').forEach(function(g){
  g.addEventListener('click',function(){
    if(!lb) return;
    var art=$('.g-art',g), cap=$('.g-cap span',g);
    lbFig.innerHTML='';
    if(art) lbFig.appendChild(art.cloneNode(true));
    lbCap.textContent=cap?cap.textContent:'';
    lb.classList.add('open');
    lb.setAttribute('aria-hidden','false');
    document.body.style.overflow='hidden';
  });
});
if(lb) lb.addEventListener('click',function(e){
  if(e.target===lb||e.target.closest('[data-lb-close]')) closeLb();
});

/* ke atas */
var top=$('#totop');
if(top) top.addEventListener('click',function(){ window.scrollTo({top:0,behavior:'smooth'}); });

/* reply komentar (halaman detail berita CMS) */
$$('.c-reply[data-to]').forEach(function(btn){
  btn.addEventListener('click',function(){
    var to=btn.getAttribute('data-to');
    var isi=$('#cIsi'); if(!isi) return;
    isi.value='@'+to+' ';
    isi.focus();
    var form=$('#cForm');
    if(form) form.scrollIntoView({behavior:'smooth',block:'center'});
  });
});
var cIsi=$('#cIsi'),cLeft=$('#cLeft');
if(cIsi&&cLeft){
  var upd=function(){ cLeft.textContent=cIsi.value.length+'/500'; };
  cIsi.addEventListener('input',upd); upd();
}
})();
