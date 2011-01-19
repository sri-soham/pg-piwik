/*
 * Piwik - Web Analytics
 *
 * JavaScript tracking client
 *
 * @link http://piwik.org
 * @source http://dev.piwik.org/trac/browser/trunk/js/piwik.js
 * @license http://www.opensource.org/licenses/bsd-license.php Simplified BSD
 */
var _paq=_paq||[],Piwik=Piwik||(function(){var m,w={},d=document,j=navigator,v=screen,H=window,h=false,C=[],e=H.encodeURIComponent,I=H.decodeURIComponent,G,D;function b(i){return typeof i!=="undefined"}function a(i){return typeof i==="function"}function n(i){return typeof i==="object"}function q(i){return typeof i==="string"||i instanceof String}function z(J){var i=J.shift();if(q(i)){G[i].apply(G,J)}else{i.apply(G,J)}}function t(L,K,J,i){if(L.addEventListener){L.addEventListener(K,J,i);return true}if(L.attachEvent){return L.attachEvent("on"+K,J)}L["on"+K]=J}function g(K,N){var J="",M,L;for(M in w){L=w[M][K];if(a(L)){J+=L(N)}}return J}function B(){g("unload");if(m){var i;do{i=new Date()}while(i.getTime()<m)}}function k(){var J;if(!h){h=true;g("load");for(J=0;
J<C.length;J++){C[J]()}}return true}function x(){if(d.addEventListener){t(d,"DOMContentLoaded",function i(){d.removeEventListener("DOMContentLoaded",i,false);k()})}else{if(d.attachEvent){d.attachEvent("onreadystatechange",function i(){if(d.readyState==="complete"){d.detachEvent("onreadystatechange",i);k()}});if(d.documentElement.doScroll&&H===top){(function i(){if(!h){try{d.documentElement.doScroll("left")}catch(J){setTimeout(i,0);return}k()}}())}}}t(H,"load",k,false)}function f(){var i="";try{i=top.document.referrer}catch(K){if(parent){try{i=parent.document.referrer}catch(J){i=""}}}if(i===""){i=d.referrer}return i}function y(i){var K=new RegExp("^(?:(?:https?|ftp):)/*(?:[^@]+@)?([^:/#]+)"),J=K.exec(i);return J?J[1]:i}function p(J,N){var M=new RegExp("^(?:https?|ftp)(?::/*(?:[^?]+)[?])([^#]+)"),L=M.exec(J),K=new RegExp("(?:^|&)"+N+"=([^&]*)"),i=L?K.exec(L[1]):0;return i?I(i[1]):""}function s(O,L,K,N,J,M){var i;if(K){i=new Date();i.setTime(i.getTime()+K)}d.cookie=O+"="+e(L)+(K?";expires="+i.toGMTString():"")+";path="+(N?N:"/")+(J?";domain="+J:"")+(M?";secure":"")
}function F(K){var i=new RegExp("(^|;)[ ]*"+K+"=([^;]*)"),J=i.exec(d.cookie);return J?I(J[2]):0}function r(i){return unescape(e(i))}function u(Z){var L=function(W,i){return(W<<i)|(W>>>(32-i))},aa=function(ag){var af="",ae,W;for(ae=7;ae>=0;ae--){W=(ag>>>(ae*4))&15;af+=W.toString(16)}return af},O,ac,ab,K=[],S=1732584193,Q=4023233417,P=2562383102,N=271733878,M=3285377520,Y,X,V,U,T,ad,J,R=[];Z=r(Z);J=Z.length;for(ac=0;ac<J-3;ac+=4){ab=Z.charCodeAt(ac)<<24|Z.charCodeAt(ac+1)<<16|Z.charCodeAt(ac+2)<<8|Z.charCodeAt(ac+3);R.push(ab)}switch(J&3){case 0:ac=2147483648;break;case 1:ac=Z.charCodeAt(J-1)<<24|8388608;break;case 2:ac=Z.charCodeAt(J-2)<<24|Z.charCodeAt(J-1)<<16|32768;break;case 3:ac=Z.charCodeAt(J-3)<<24|Z.charCodeAt(J-2)<<16|Z.charCodeAt(J-1)<<8|128;break}R.push(ac);while((R.length&15)!==14){R.push(0)}R.push(J>>>29);R.push((J<<3)&4294967295);for(O=0;O<R.length;O+=16){for(ac=0;ac<16;ac++){K[ac]=R[O+ac]}for(ac=16;ac<=79;ac++){K[ac]=L(K[ac-3]^K[ac-8]^K[ac-14]^K[ac-16],1)}Y=S;X=Q;V=P;U=N;T=M;
for(ac=0;ac<=19;ac++){ad=(L(Y,5)+((X&V)|(~X&U))+T+K[ac]+1518500249)&4294967295;T=U;U=V;V=L(X,30);X=Y;Y=ad}for(ac=20;ac<=39;ac++){ad=(L(Y,5)+(X^V^U)+T+K[ac]+1859775393)&4294967295;T=U;U=V;V=L(X,30);X=Y;Y=ad}for(ac=40;ac<=59;ac++){ad=(L(Y,5)+((X&V)|(X&U)|(V&U))+T+K[ac]+2400959708)&4294967295;T=U;U=V;V=L(X,30);X=Y;Y=ad}for(ac=60;ac<=79;ac++){ad=(L(Y,5)+(X^V^U)+T+K[ac]+3395469782)&4294967295;T=U;U=V;V=L(X,30);X=Y;Y=ad}S=(S+Y)&4294967295;Q=(Q+X)&4294967295;P=(P+V)&4294967295;N=(N+U)&4294967295;M=(M+T)&4294967295}ad=aa(S)+aa(Q)+aa(P)+aa(N)+aa(M);return ad.toLowerCase()}function A(K){var N=new RegExp('[\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]',"g"),L={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"};function i(O){N.lastIndex=0;return N.test(O)?'"'+O.replace(N,function(P){var Q=L[P];return q(Q)?Q:"\\u"+("0000"+P.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+O+'"'}function J(O){return O<10?"0"+O:O
}function M(T,S){var R,Q,P,O,U=S[T];if(U===null){return"null"}if(U&&n(U)&&a(U.toJSON)){U=U.toJSON(T)}switch(typeof U){case"string":return i(U);case"number":return isFinite(U)?String(U):"null";case"boolean":case"null":return String(U);case"object":O=[];if(U instanceof Array){for(R=0;R<U.length;R++){O[R]=M(R,U)||"null"}P=O.length===0?"[]":"["+O.join(",")+"]";return P}if(U instanceof Date){return i(U.getUTCFullYear()+"-"+J(U.getUTCMonth()+1)+"-"+J(U.getUTCDate())+"T"+J(U.getUTCHours())+":"+J(U.getUTCMinutes())+":"+J(U.getUTCSeconds())+"Z")}for(Q in U){P=M(Q,U);if(P){O.push(i(Q)+":"+P)}}P=O.length===0?"{}":"{"+O.join(",")+"}";return P}}return M("",{"":K})}function o(J,i,K){if(J==="webcache.googleusercontent.com"||J==="cc.bingj.com"||J.substring(0,5)==="74.6."){i=d.links[0].href;J=y(i)}else{if(J==="translate.googleusercontent.com"){if(K===""){K=i}i=p(i,"u");J=y(i)}}return[J,i,K]}function l(J){var i=J.length;return(J.charAt(--i)===".")?J.substring(0,i):J}function E(at,aq){var ag=o(d.domain,H.location.href,f()),U=l(ag[0]),R=ag[1],au=ag[2],K="GET",Y=at||"",aK=aq||"",aD,aJ=d.title,ac="7z|aac|ar[cj]|as[fx]|avi|bin|csv|deb|dmg|doc|exe|flv|gif|gz|gzip|hqx|jar|jpe?g|js|mp(2|3|4|e?g)|mov(ie)?|ms[ip]|od[bfgpst]|og[gv]|pdf|phps|png|ppt|qtm?|ra[mr]?|rpm|sea|sit|tar|t?bz2?|tgz|torrent|txt|wav|wm[av]|wpd||xls|xml|z|zip",aw=[U],L=[],ax=[],aA=[],X=500,ae=30000,af,ao,V,az="_pk_",N,ar,ay,aL=63072000000,aa=1800000,W=15768000000,O="0",S={pdf:["pdf","application/pdf","0"],quicktime:["qt","video/quicktime","0"],realplayer:["realp","audio/x-pn-realaudio-plugin","0"],wma:["wma","application/x-mplayer2","0"],director:["dir","application/x-director","0"],flash:["fla","application/x-shockwave-flash","0"],java:["java","application/x-java-vm","0"],gears:["gears","application/x-googlegears","0"],silverlight:["ag","application/x-silverlight","0"]},al=false,aC=u,aH,aj,av,ap;
function aE(aN){var aM;if(af){aM=new RegExp("#.*");return aN.replace(aM,"")}return aN}function ak(aP){var aN,aM,aO;for(aN=0;aN<aw.length;aN++){aM=aw[aN].toLowerCase();if(aP===aM){return true}if(aM.substring(0,2)==="*."){if(aP===aM.substring(2)){return true}aO=aP.length-aM.length+1;if((aO>0)&&(aP.substring(aO)===aM.substring(1))){return true}}}return false}function i(aM){var aN=new Image(1,1);aN.onLoad=function(){};aN.src=Y+"?"+aM}function T(aM){try{var aO=H.XMLHttpRequest?new H.XMLHttpRequest():H.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):null;aO.open("POST",Y,true);aO.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");aO.setRequestHeader("Content-Length",aM.length);aO.setRequestHeader("Connection","close");aO.send(aM)}catch(aN){i(aM)}}function aG(aO,aN){var aM=new Date();if(K==="POST"){T(aO)}else{i(aO)}m=aM.getTime()+aN}function P(){var aM,aN;if(typeof navigator.javaEnabled!=="unknown"&&b(j.javaEnabled)&&j.javaEnabled()){S.java[2]="1"}if(a(H.GearsFactory)){S.gears[2]="1"
}if(j.mimeTypes&&j.mimeTypes.length){for(aM in S){aN=j.mimeTypes[S[aM][1]];if(aN&&aN.enabledPlugin){S[aM][2]="1"}}}}function an(){var aM=az+"testcookie";if(!b(j.cookieEnabled)){s(aM,"1");return F(aM)==="1"?"1":"0"}return j.cookieEnabled?"1":"0"}function M(aM){return az+aM+"."+ap}function ad(){ap=aC((N||U)+(ar||"/")).substring(0,8)}function ah(a6,a7){var a3,aN=new Date(),aU=Math.round(aN.getTime()/1000),aP,a8,a5,aQ,aZ,a1,aT,aS,a2,aM,a4,aY=M("id"),aV=M("ses"),aW=M("ref"),a0=F(aY),aX=F(aV),aR=F(aW),aO="&res="+v.width+"x"+v.height+"&cookie="+O;for(a3 in S){aO+="&"+S[a3][0]+"="+S[a3][2]}if(a0){a8="0";aP=a0.split(".");a5=aP[0];aZ=aP[1];aQ=aP[2];a1=aP[3];aT=aP[4]}else{a8="1";aZ=aU;aT="";a5=aC((b(j.userAgent)?j.userAgent:"")+(b(j.platform)?j.platform:"")+aO+Math.round(aN.getTime/1000)).substring(0,16);aQ=0}if(aR){aP=aR.split(" ");aS=aP[0];a2=aP[1]}if(!aX){aQ++;aT=a1;aM=y(au);a4=aR?y(aR):"";if(aM.length&&!ak(aM)&&(!ay||!a4.length||ak(a4))){aS=aU;a2=au;s(aW,aS+" "+a2,W,ar,N)}}a1=aU;s(aY,a5+"."+aZ+"."+aQ+"."+a1+"."+aT,aL,ar,N);
s(aV,"*",aa,ar,N);aO="idsite="+aK+"&rec=1&rand="+Math.random()+"&h="+aN.getHours()+"&m="+aN.getMinutes()+"&s="+aN.getSeconds()+"&url="+e(aE(aD||R))+"&urlref="+e(aE(au))+"&_id="+a5+"&_idts="+aZ+"&_idvc="+aQ+"&_idn="+a8+"&_ref="+e(aE(a2))+"&_refts="+aS+"&_viewts="+aT+"&_ses="+(V?0:1)+aO;if(a6){aO+="&data="+e(A(a6))}else{if(ao){aO+="&data="+e(A(ao))}}aO+=g(a7);return aO}function J(aN,aO){var aM=ah(aO,"log")+"&action_name="+e(aN||aJ);aG(aM,X)}function aF(aM,aP,aO){var aN=ah(aO,"goal")+"&idgoal="+aM;if(aP){aN+="&revenue="+aP}aG(aN,X)}function ab(aN,aM,aP){var aO=ah(aP,"click")+"&"+aM+"="+e(aE(aN))+"&redirect=0";aG(aO,X)}function am(aO,aN){var aP,aM="(^| )(piwik[_-]"+aN;if(aO){for(aP=0;aP<aO.length;aP++){aM+="|"+aO[aP]}}aM+=")( |$)";return new RegExp(aM)}function aI(aP,aM,aQ){if(!aQ){return"link"}var aO=am(ax,"download"),aN=am(aA,"link"),aR=new RegExp("\\.("+ac+")([?&#]|$)","i");return aN.test(aP)?"link":(aO.test(aP)||aR.test(aM)?"download":0)}function Q(aR){var aP,aN,aM;while((aP=aR.parentNode)&&((aN=aR.tagName)!=="A"&&aN!=="AREA")){aR=aP
}if(b(aR.href)){var aS=aR.hostname,aT=aS.toLowerCase(),aO=aR.href.replace(aS,aT),aQ=new RegExp("^(javascript|vbscript|jscript|mocha|livescript|ecmascript): *","i");if(!aQ.test(aO)){aM=aI(aR.className,aO,ak(aT));if(aM){ab(aO,aM)}}}}function Z(aM){var aN,aO;aM=aM||H.event;aN=aM.which||aM.button;aO=aM.target||aM.srcElement;if(aM.type==="click"){if(aO){Q(aO)}}else{if(aM.type==="mousedown"){if((aN===1||aN===2)&&aO){aH=aN;aj=aO}else{aH=aj=null}}else{if(aM.type==="mouseup"){if(aN===aH&&aO===aj){Q(aO)}aH=aj=null}}}}function aB(aN,aM){if(aM){t(aN,"click",Z,false);t(aN,"click",Z,false)}else{t(aN,"click",Z,false)}}function ai(aN){if(!al){al=true;var aO,aM=am(L,"ignore"),aP=d.links;if(aP){for(aO=0;aO<aP.length;aO++){if(!aM.test(aP[aO].className)){aB(aP[aO],aN)}}}}}O=an();P();ad();return{getVisitorId:function(){return av},setTrackerUrl:function(aM){Y=aM},setSiteId:function(aM){aK=aM},setCustomData:function(aM,aN){if(n(aM)){ao=aM}else{if(!ao){ao=[]}ao[aM]=aN}},getCustomData:function(){return ao},setCustomVar:function(aN,aO,aP,aM){},getCustomVar:function(aM){},deleteCustomVar:function(aM){},setLinkTrackingTimer:function(aM){X=aM
},setDownloadExtensions:function(aM){ac=aM},addDownloadExtensions:function(aM){ac+="|"+aM},setDomains:function(aM){aw=q(aM)?[aM]:aM;aw.push(U)},setIgnoreClasses:function(aM){L=q(aM)?[aM]:aM},setRequestMethod:function(aM){K=aM||"GET"},setReferrerUrl:function(aM){au=aM},setCustomUrl:function(aM){aD=aM},setDocumentTitle:function(aM){aJ=aM},setDownloadClasses:function(aM){ax=q(aM)?[aM]:aM},setLinkClasses:function(aM){aA=q(aM)?[aM]:aM},discardHashTag:function(aM){af=aM},setCookieNamePrefix:function(aM){az=aM},setCookieDomain:function(aM){N=l(aM);ad()},setCookiePath:function(aM){ar=aM;ad()},setVisitorCookieTimeout:function(aM){aL=aM},setSessionCookieTimeout:function(aM){aa=aM},setReferralCookieTimeout:function(aM){W=aM},setConversionAttributionFirstReferer:function(aM){ay=aM},addListener:function(aN,aM){aB(aN,aM)},enableLinkTracking:function(aM){if(h){ai(aM)}else{C[C.length]=function(){ai(aM)}}},enableServerCookies:function(aM){V=aM},setHeartBeatTimer:function(aM){ae=aM},killFrame:function(){if(H!==top){top.location=H.location
}},redirectFile:function(aM){if(H.location.protocol==="file:"){H.location=aM}},trackGoal:function(aM,aO,aN){aF(aM,aO,aN)},trackLink:function(aN,aM,aO){ab(aN,aM,aO)},trackPageView:function(aM,aN){J(aM,aN);if(ae){setTimeout(function(){J(aM,aN)},ae)}}}}function c(){return{push:z}}t(H,"beforeunload",B,false);x();G=new E();for(D=0;D<_paq.length;D++){z(_paq[D])}_paq=new c();return{addPlugin:function(i,J){w[i]=J},getTracker:function(i,J){return new E(i,J)}}}());