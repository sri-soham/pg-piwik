/*
 * Piwik - Web Analytics
 *
 * JavaScript tracking client
 *
 * @link http://piwik.org
 * @source http://dev.piwik.org/trac/browser/trunk/js/piwik.js
 * @license http://www.opensource.org/licenses/bsd-license.php Simplified BSD
 */
if(!this.JSON){this.JSON={}}(function(){function d(f){return f<10?"0"+f:f}if(typeof Date.prototype.toJSON!=="function"){Date.prototype.toJSON=function(f){return isFinite(this.valueOf())?this.getUTCFullYear()+"-"+d(this.getUTCMonth()+1)+"-"+d(this.getUTCDate())+"T"+d(this.getUTCHours())+":"+d(this.getUTCMinutes())+":"+d(this.getUTCSeconds())+"Z":null};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(f){return this.valueOf()}}var c=new RegExp("[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]","g"),g=new RegExp('[\\\\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]',"g"),h,b,j={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},i;
function a(f){g.lastIndex=0;return g.test(f)?'"'+f.replace(g,function(k){var l=j[k];return typeof l==="string"?l:"\\u"+("0000"+k.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+f+'"'}function e(r,o){var m,l,s,f,p=h,n,q=o[r];if(q&&typeof q==="object"&&typeof q.toJSON==="function"){q=q.toJSON(r)}if(typeof i==="function"){q=i.call(o,r,q)}switch(typeof q){case"string":return a(q);case"number":return isFinite(q)?String(q):"null";case"boolean":case"null":return String(q);case"object":if(!q){return"null"}h+=b;n=[];if(Object.prototype.toString.apply(q)==="[object Array]"){f=q.length;for(m=0;m<f;m+=1){n[m]=e(m,q)||"null"}s=n.length===0?"[]":h?"[\n"+h+n.join(",\n"+h)+"\n"+p+"]":"["+n.join(",")+"]";h=p;return s}if(i&&typeof i==="object"){f=i.length;for(m=0;m<f;m+=1){l=i[m];if(typeof l==="string"){s=e(l,q);if(s){n.push(a(l)+(h?": ":":")+s)}}}}else{for(l in q){if(Object.hasOwnProperty.call(q,l)){s=e(l,q);if(s){n.push(a(l)+(h?": ":":")+s)}}}}s=n.length===0?"{}":h?"{\n"+h+n.join(",\n"+h)+"\n"+p+"}":"{"+n.join(",")+"}";
h=p;return s}}if(typeof JSON.stringify!=="function"){JSON.stringify=function(m,k,l){var f;h="";b="";if(typeof l==="number"){for(f=0;f<l;f+=1){b+=" "}}else{if(typeof l==="string"){b=l}}i=k;if(k&&typeof k!=="function"&&(typeof k!=="object"||typeof k.length!=="number")){throw new Error("JSON.stringify")}return e("",{"":m})}}if(typeof JSON.parse!=="function"){JSON.parse=function(m,f){var l;function k(q,p){var o,n,r=q[p];if(r&&typeof r==="object"){for(o in r){if(Object.hasOwnProperty.call(r,o)){n=k(r,o);if(n!==undefined){r[o]=n}else{delete r[o]}}}}return f.call(q,p,r)}m=String(m);c.lastIndex=0;if(c.test(m)){m=m.replace(c,function(n){return"\\u"+("0000"+n.charCodeAt(0).toString(16)).slice(-4)})}if((new RegExp("^[\\],:{}\\s]*$")).test(m.replace(new RegExp('\\\\(?:["\\\\/bfnrt]|u[0-9a-fA-F]{4})',"g"),"@").replace(new RegExp('"[^"\\\\n\\r]*"|true|false|null|-?\\d+(?:\\.\\d*)?(?:[eE][+\\-]?\\d+)?',"g"),"]").replace(new RegExp("(?:^|:|,)(?:\\s*\\[)+","g"),""))){l=eval("("+m+")");return typeof f==="function"?k({"":l},""):l
}throw new SyntaxError("JSON.parse")}}}());var _paq=_paq||[],Piwik=Piwik||(function(){var m,w={},d=document,j=navigator,v=screen,G=window,h=false,B=[],e=G.encodeURIComponent,H=G.decodeURIComponent,F,C;function b(i){return typeof i!=="undefined"}function a(i){return typeof i==="function"}function n(i){return typeof i==="object"}function q(i){return typeof i==="string"||i instanceof String}function z(I){var i=I.shift();if(q(i)){F[i].apply(F,I)}else{i.apply(F,I)}}function t(K,J,I,i){if(K.addEventListener){K.addEventListener(J,I,i);return true}if(K.attachEvent){return K.attachEvent("on"+J,I)}K["on"+J]=I}function g(J,M){var I="",L,K;for(L in w){K=w[L][J];if(a(K)){I+=K(M)}}return I}function A(){g("unload");if(m){var i;do{i=new Date()}while(i.getTime()<m)}}function k(){var I;if(!h){h=true;g("load");for(I=0;I<B.length;I++){B[I]()}}return true}function x(){if(d.addEventListener){t(d,"DOMContentLoaded",function i(){d.removeEventListener("DOMContentLoaded",i,false);k()})}else{if(d.attachEvent){d.attachEvent("onreadystatechange",function i(){if(d.readyState==="complete"){d.detachEvent("onreadystatechange",i);
k()}});if(d.documentElement.doScroll&&G===G.top){(function i(){if(!h){try{d.documentElement.doScroll("left")}catch(I){setTimeout(i,0);return}k()}}())}}}t(G,"load",k,false)}function f(){var i="";try{i=G.top.document.referrer}catch(J){if(G.parent){try{i=G.parent.document.referrer}catch(I){i=""}}}if(i===""){i=d.referrer}return i}function y(i){var J=new RegExp("^(?:(?:https?|ftp):)/*(?:[^@]+@)?([^:/#]+)"),I=J.exec(i);return I?I[1]:i}function p(I,M){var L=new RegExp("^(?:https?|ftp)(?::/*(?:[^?]+)[?])([^#]+)"),K=L.exec(I),J=new RegExp("(?:^|&)"+M+"=([^&]*)"),i=K?J.exec(K[1]):0;return i?H(i[1]):""}function s(N,K,J,M,I,L){var i;if(J){i=new Date();i.setTime(i.getTime()+J)}d.cookie=N+"="+e(K)+(J?";expires="+i.toGMTString():"")+";path="+(M?M:"/")+(I?";domain="+I:"")+(L?";secure":"")}function E(J){var i=new RegExp("(^|;)[ ]*"+J+"=([^;]*)"),I=i.exec(d.cookie);return I?H(I[2]):0}function r(i){return unescape(e(i))}function u(Y){var K=function(W,i){return(W<<i)|(W>>>(32-i))},Z=function(af){var ae="",ad,W;
for(ad=7;ad>=0;ad--){W=(af>>>(ad*4))&15;ae+=W.toString(16)}return ae},N,ab,aa,J=[],R=1732584193,P=4023233417,O=2562383102,M=271733878,L=3285377520,X,V,U,T,S,ac,I,Q=[];Y=r(Y);I=Y.length;for(ab=0;ab<I-3;ab+=4){aa=Y.charCodeAt(ab)<<24|Y.charCodeAt(ab+1)<<16|Y.charCodeAt(ab+2)<<8|Y.charCodeAt(ab+3);Q.push(aa)}switch(I&3){case 0:ab=2147483648;break;case 1:ab=Y.charCodeAt(I-1)<<24|8388608;break;case 2:ab=Y.charCodeAt(I-2)<<24|Y.charCodeAt(I-1)<<16|32768;break;case 3:ab=Y.charCodeAt(I-3)<<24|Y.charCodeAt(I-2)<<16|Y.charCodeAt(I-1)<<8|128;break}Q.push(ab);while((Q.length&15)!==14){Q.push(0)}Q.push(I>>>29);Q.push((I<<3)&4294967295);for(N=0;N<Q.length;N+=16){for(ab=0;ab<16;ab++){J[ab]=Q[N+ab]}for(ab=16;ab<=79;ab++){J[ab]=K(J[ab-3]^J[ab-8]^J[ab-14]^J[ab-16],1)}X=R;V=P;U=O;T=M;S=L;for(ab=0;ab<=19;ab++){ac=(K(X,5)+((V&U)|(~V&T))+S+J[ab]+1518500249)&4294967295;S=T;T=U;U=K(V,30);V=X;X=ac}for(ab=20;ab<=39;ab++){ac=(K(X,5)+(V^U^T)+S+J[ab]+1859775393)&4294967295;S=T;T=U;U=K(V,30);V=X;X=ac}for(ab=40;ab<=59;
ab++){ac=(K(X,5)+((V&U)|(V&T)|(U&T))+S+J[ab]+2400959708)&4294967295;S=T;T=U;U=K(V,30);V=X;X=ac}for(ab=60;ab<=79;ab++){ac=(K(X,5)+(V^U^T)+S+J[ab]+3395469782)&4294967295;S=T;T=U;U=K(V,30);V=X;X=ac}R=(R+X)&4294967295;P=(P+V)&4294967295;O=(O+U)&4294967295;M=(M+T)&4294967295;L=(L+S)&4294967295}ac=Z(R)+Z(P)+Z(O)+Z(M)+Z(L);return ac.toLowerCase()}function o(J,i,I){if(J==="webcache.googleusercontent.com"||J==="cc.bingj.com"||J.slice(0,5)==="74.6."){i=d.links[0].href;J=y(i)}else{if(J==="translate.googleusercontent.com"){if(I===""){I=i}i=p(i,"u");J=y(i)}}return[J,i,I]}function l(I){var i=I.length;return(I.charAt(--i)===".")?I.slice(0,i):I}function D(ay,aw){var al=o(d.domain,G.location.href,f()),Z=l(al[0]),V=al[1],aA=al[2],K="GET",ad=ay||"",aQ=aw||"",aJ,aP=d.title,ah="7z|aac|ar[cj]|as[fx]|avi|bin|csv|deb|dmg|doc|exe|flv|gif|gz|gzip|hqx|jar|jpe?g|js|mp(2|3|4|e?g)|mov(ie)?|ms[ip]|od[bfgpst]|og[gv]|pdf|phps|png|ppt|qtm?|ra[mr]?|rpm|sea|sit|tar|t?bz2?|tgz|torrent|txt|wav|wm[av]|wpd||xls|xml|z|zip",aB=[Z],M=[],aC=[],aF=[],ac=500,J,aj,ak,aD="_pk_",P,ax,L,ar,aR=63072000000,af=1800000,ab=15768000000,aG=false,T=100,Q="0",W={pdf:["pdf","application/pdf","0"],quicktime:["qt","video/quicktime","0"],realplayer:["realp","audio/x-pn-realaudio-plugin","0"],wma:["wma","application/x-mplayer2","0"],director:["dir","application/x-director","0"],flash:["fla","application/x-shockwave-flash","0"],java:["java","application/x-java-vm","0"],gears:["gears","application/x-googlegears","0"],silverlight:["ag","application/x-silverlight","0"]},aq=false,S=false,aa,aN,ao,az,aI=u,av;
function aK(aT){var aS;if(ak){aS=new RegExp("#.*");return aT.replace(aS,"")}return aT}function ap(aV){var aT,aS,aU;for(aT=0;aT<aB.length;aT++){aS=aB[aT].toLowerCase();if(aV===aS){return true}if(aS.slice(0,2)==="*."){if(aV===aS.slice(2)){return true}aU=aV.length-aS.length+1;if((aU>0)&&(aV.slice(aU)===aS.slice(1))){return true}}}return false}function i(aS){var aT=new Image(1,1);aT.onLoad=function(){};aT.src=ad+"?"+aS}function Y(aS){try{var aU=G.XMLHttpRequest?new G.XMLHttpRequest():G.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):null;aU.open("POST",ad,true);aU.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");aU.setRequestHeader("Content-Length",aS.length);aU.setRequestHeader("Connection","close");aU.send(aS)}catch(aT){i(aS)}}function aM(aU,aT){var aS=new Date();if(!L){if(K==="POST"){Y(aU)}else{i(aU)}m=aS.getTime()+aT}}function R(){var aS,aT;if(typeof navigator.javaEnabled!=="unknown"&&b(j.javaEnabled)&&j.javaEnabled()){W.java[2]="1"}if(a(G.GearsFactory)){W.gears[2]="1"
}if(j.mimeTypes&&j.mimeTypes.length){for(aS in W){aT=j.mimeTypes[W[aS][1]];if(aT&&aT.enabledPlugin){W[aS][2]="1"}}}}function N(aS){return aD+aS+"."+av}function au(){var aS=N("testcookie");if(!b(j.cookieEnabled)){s(aS,"1");return E(aS)==="1"?"1":"0"}return j.cookieEnabled?"1":"0"}function ai(){av=aI((P||Z)+(ax||"/")).slice(0,8)}function X(){var aT=N("cvar"),aS=E(aT);if(aS.length){aS=JSON.parse(aS);if(n(aS)){return aS}}return{}}function aE(){if(aG===false){aG=X()}}function O(aS){var aT=new Date();aa=aT.getTime()}function am(bh){var be,aS=new Date(),a2=Math.round(aS.getTime()/1000),aW=1024,aV,bg,bi,bf,aX,a9,bc,a1,a0,bd,bj,a5,aZ,a7=N("id"),a3=N("ses"),a4=N("ref"),bk=N("cvar"),ba=E(a7),a6=E(a3),aY=E(a4),a8=d.location.protocol==="https",aU="&res="+v.width+"x"+v.height+"&cookie="+Q;if(L){s(a7,"",-1,ax,P);s(a3,"",-1,ax,P);s(bk,"",-1,ax,P);s(a4,"",-1,ax,P);return""}for(be in W){aU+="&"+W[be][0]+"="+W[be][2]}if(ba){bi="0";aV=ba.split(".");bf=aV[0];a9=aV[1];aX=aV[2];bc=aV[3];a1=aV[4]}else{bi="1";a9=a2;
bc=a2;a1="";bf=aI((j.userAgent||"")+(j.platform||"")+aU+Math.round(aS.getTime/1000)).slice(0,16);aX=0}az=bf;if(aY){bg=aY.indexOf(".");a0=aY.slice(0,bg);bd=aY.slice(bg+1)}else{a0=0;bd=""}if(!a6){aX++;a1=bc;bj=y(aA);a5=aY?y(aY):"";if(bj.length&&!ap(bj)&&(!ar||!a5.length||ap(a5))){a0=a2;bd=aA;s(a4,a0+"."+bd.substr(0,aW),ab,ax,P,a8)}}aZ=JSON.stringify(aG);aU="idsite="+aQ+"&rec=1&rand="+Math.random()+"&h="+aS.getHours()+"&m="+aS.getMinutes()+"&s="+aS.getSeconds()+"&url="+e(aK(aJ||V))+"&urlref="+e(aK(aA))+"&_id="+bf+"&_idts="+a9+"&_idvc="+aX+"&_idn="+bi+"&_ref="+e(aK(bd.substr(0,aW)))+"&_refts="+a0+"&_viewts="+a1+aU;if(aZ.length>10){aU+="&_cvar="+aZ}var aT,bb=aG;for(aT in bb){if(aG[aT][0]===""||aG[aT][1]===""){delete aG[aT]}}aZ=JSON.stringify(aG);s(bk,aZ,af,ax,P,a8);bc=a2;s(a7,bf+"."+a9+"."+aX+"."+bc+"."+a1,aR,ax,P,a8);s(a3,"*",af,ax,P,a8);aU+=g(bh);return aU}function I(aV){var aS=new Date(),aU=am("log")+"&action_name="+e(aV||aP);aM(aU,ac);if(J&&aj&&!S){S=true;t(d,"click",O);t(d,"mouseup",O);
t(d,"mousedown",O);t(d,"mousemove",O);t(d,"mousewheel",O);t(G,"DOMMouseScroll",O);t(G,"scroll",O);t(d,"keypress",O);t(d,"keydown",O);t(d,"keyup",O);t(G,"resize",O);t(G,"focus",O);t(G,"blur",O);aa=aS.getTime();setTimeout(function aT(){var aW=new Date(),aX;if((aa+aj)>aW.getTime()){if(J<aW.getTime()){aX=am("ping")+"&ping=1";aM(aX,ac)}setTimeout(aT,aj)}},aj)}}function aL(aS,aU){var aT=am("goal")+"&idgoal="+aS;if(aU){aT+="&revenue="+aU}aM(aT,ac)}function ag(aT,aS){var aU=am("click")+"&"+aS+"="+e(aK(aT));aM(aU,ac)}function at(aU,aT){var aV,aS="(^| )(piwik[_-]"+aT;if(aU){for(aV=0;aV<aU.length;aV++){aS+="|"+aU[aV]}}aS+=")( |$)";return new RegExp(aS)}function aO(aV,aS,aW){if(!aW){return"link"}var aU=at(aC,"download"),aT=at(aF,"link"),aX=new RegExp("\\.("+ah+")([?&#]|$)","i");return aT.test(aV)?"link":(aU.test(aV)||aX.test(aS)?"download":0)}function U(aX){var aV,aT,aS;while(!!(aV=aX.parentNode)&&((aT=aX.tagName)!=="A"&&aT!=="AREA")){aX=aV}if(b(aX.href)){var aY=aX.hostname||y(aX.href),aZ=aY.toLowerCase(),aU=aX.href.replace(aY,aZ),aW=new RegExp("^(javascript|vbscript|jscript|mocha|livescript|ecmascript): *","i");
if(!aW.test(aU)){aS=aO(aX.className,aU,ap(aZ));if(aS){ag(aU,aS)}}}}function ae(aS){var aT,aU;aS=aS||G.event;aT=aS.which||aS.button;aU=aS.target||aS.srcElement;if(aS.type==="click"){if(aU){U(aU)}}else{if(aS.type==="mousedown"){if((aT===1||aT===2)&&aU){aN=aT;ao=aU}else{aN=ao=null}}else{if(aS.type==="mouseup"){if(aT===aN&&aU===ao){U(aU)}aN=ao=null}}}}function aH(aT,aS){if(aS){t(aT,"mouseup",ae,false);t(aT,"mousedown",ae,false)}else{t(aT,"click",ae,false)}}function an(aT){if(!aq){aq=true;var aU,aS=at(M,"ignore"),aV=d.links;if(aV){for(aU=0;aU<aV.length;aU++){if(!aS.test(aV[aU].className)){aH(aV[aU],aT)}}}}}Q=au();R();ai();return{setTrackerUrl:function(aS){ad=aS},setSiteId:function(aS){aQ=aS},setCustomVariable:function(aT,aS,aU){aE();if(aT>0&&aT<=5){aG[aT]=[aS.slice(0,T),aU.slice(0,T)]}},getCustomVariable:function(aS){aE();return aG[aS]},deleteCustomVariable:function(aS){var aT=this.getCustomVariable(aS);if(b(aT)){this.setCustomVariable(aS,"","")}},setLinkTrackingTimer:function(aS){ac=aS},setDownloadExtensions:function(aS){ah=aS
},addDownloadExtensions:function(aS){ah+="|"+aS},setDomains:function(aS){aB=q(aS)?[aS]:aS;aB.push(Z)},setIgnoreClasses:function(aS){M=q(aS)?[aS]:aS},setRequestMethod:function(aS){K=aS||"GET"},setReferrerUrl:function(aS){aA=aS},setCustomUrl:function(aS){aJ=aS},setDocumentTitle:function(aS){aP=aS},setDownloadClasses:function(aS){aC=q(aS)?[aS]:aS},setLinkClasses:function(aS){aF=q(aS)?[aS]:aS},discardHashTag:function(aS){ak=aS},setCookieNamePrefix:function(aS){aD=aS;aG=X()},setCookieDomain:function(aS){P=l(aS);ai()},setCookiePath:function(aS){ax=aS;ai()},setVisitorCookieTimeout:function(aS){aR=aS*1000},setSessionCookieTimeout:function(aS){af=aS*1000},setReferralCookieTimeout:function(aS){ab=aS*1000},setConversionAttributionFirstReferrer:function(aS){ar=aS},setDoNotTrack:function(aS){L=aS&&j.doNotTrack},addListener:function(aT,aS){aH(aT,aS)},enableLinkTracking:function(aS){if(h){an(aS)}else{B[B.length]=function(){an(aS)}}},setHeartBeatTimer:function(aU,aT){var aS=new Date();J=aS.getTime()+aU*1000;
aj=aT*1000},killFrame:function(){if(G!==G.top){G.top.location=G.location}},redirectFile:function(aS){if(G.location.protocol==="file:"){G.location=aS}},trackGoal:function(aS,aT){aL(aS,aT)},trackLink:function(aT,aS){ag(aT,aS)},trackPageView:function(aS){I(aS)}}}function c(){return{push:z}}t(G,"beforeunload",A,false);x();F=new D();for(C=0;C<_paq.length;C++){z(_paq[C])}_paq=new c();return{addPlugin:function(i,I){w[i]=I},getTracker:function(i,I){return new D(i,I)},getAsyncTracker:function(){return F}}}()),piwik_track,piwik_log=function(b,f,d,g){function a(h){try{return eval("piwik_"+h)}catch(i){}return}var c,e=Piwik.getTracker(d,f);e.setDocumentTitle(b);if(!!(c=a("tracker_pause"))){e.setLinkTrackingTimer(c)}if(!!(c=a("download_extensions"))){e.setDownloadExtensions(c)}if(!!(c=a("hosts_alias"))){e.setDomains(c)}if(!!(c=a("ignore_classes"))){e.setIgnoreClasses(c)}e.trackPageView();if((a("install_tracker"))){piwik_track=function(i,k,j,h){e.setSiteId(k);e.setTrackerUrl(j);e.trackLink(i,h)
};e.enableLinkTracking()}};