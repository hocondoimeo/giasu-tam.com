window.matchMedia=window.matchMedia||function(d,r){var f,g=d.documentElement,u=g.firstElementChild||g.firstChild,m=d.createElement("body"),h=d.createElement("div");h.id="mq-test-1";h.style.cssText="position:absolute;top:-100em";m.style.background="none";m.appendChild(h);return function(d){h.innerHTML='&shy;<style media="'+d+'"> #mq-test-1 { width: 42px; }</style>';g.insertBefore(m,u);f=42==h.offsetWidth;g.removeChild(m);return{matches:f,media:d}}}(document);
(function(d){function r(){v(!0)}d.respond={};respond.update=function(){};respond.mediaQueriesSupported=d.matchMedia&&d.matchMedia("only all").matches;if(!respond.mediaQueriesSupported){var f=d.document,g=f.documentElement,u=[],m=[],h=[],s={},n=f.getElementsByTagName("head")[0]||g,E=f.getElementsByTagName("base")[0],q=n.getElementsByTagName("link"),w=[],B=function(){for(var b=q.length,c=0,a,k,e,l;c<b;c++)a=q[c],k=a.href,e=a.media,l=a.rel&&"stylesheet"===a.rel.toLowerCase(),k&&l&&!s[k]&&(a.styleSheet&&
a.styleSheet.rawCssText?(z(a.styleSheet.rawCssText,k,e),s[k]=!0):(/^([a-zA-Z:]*\/\/)/.test(k)||E)&&k.replace(RegExp.$1,"").split("/")[0]!==d.location.host||w.push({href:k,media:e}));A()},A=function(){if(w.length){var b=w.shift();F(b.href,function(c){z(c,b.href,b.media);s[b.href]=!0;setTimeout(function(){A()},0)})}},z=function(b,c,a){var k=b.match(/@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi),e=k&&k.length||0;c=c.substring(0,c.lastIndexOf("/"));var d=function(a){return a.replace(/(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,
"$1"+c+"$2$3")},f=!e&&a,h=0,g,p,n,q;c.length&&(c+="/");for(f&&(e=1);h<e;h++)for(g=0,f?(p=a,m.push(d(b))):(p=k[h].match(/@media *([^\{]+)\{([\S\s]+?)$/)&&RegExp.$1,m.push(RegExp.$2&&d(RegExp.$2))),n=p.split(","),q=n.length;g<q;g++)p=n[g],u.push({media:p.split("(")[0].match(/(only\s+)?([a-zA-Z]+)\s?/)&&RegExp.$2||"all",rules:m.length-1,hasquery:-1<p.indexOf("("),minw:p.match(/\(min\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/)&&parseFloat(RegExp.$1)+(RegExp.$2||""),maxw:p.match(/\(max\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/)&&
parseFloat(RegExp.$1)+(RegExp.$2||"")});v()},x,C,D=function(){var b,c=f.createElement("div"),a=f.body,d=!1;c.style.cssText="position:absolute;font-size:1em;width:1em";a||(a=d=f.createElement("body"),a.style.background="none");a.appendChild(c);g.insertBefore(a,g.firstChild);b=c.offsetWidth;d?g.removeChild(a):a.removeChild(c);return b=y=parseFloat(b)},y,v=function(b){var c=g.clientWidth,a="CSS1Compat"===f.compatMode&&c||f.body.clientWidth||c,c={},d=q[q.length-1],e=(new Date).getTime();if(b&&x&&30>e-
x)clearTimeout(C),C=setTimeout(v,30);else{x=e;for(var l in u){b=u[l];var e=b.minw,t=b.maxw,r=null===e,s=null===t;e&&(e=parseFloat(e)*(-1<e.indexOf("em")?y||D():1));t&&(t=parseFloat(t)*(-1<t.indexOf("em")?y||D():1));b.hasquery&&(r&&s||!(r||a>=e)||!(s||a<=t))||(c[b.media]||(c[b.media]=[]),c[b.media].push(m[b.rules]))}for(l in h)h[l]&&h[l].parentNode===n&&n.removeChild(h[l]);for(l in c)a=f.createElement("style"),b=c[l].join("\n"),a.type="text/css",a.media=l,n.insertBefore(a,d.nextSibling),a.styleSheet?
a.styleSheet.cssText=b:a.appendChild(f.createTextNode(b)),h.push(a)}},F=function(b,c){var a=G();a&&(a.open("GET",b,!0),a.onreadystatechange=function(){4!=a.readyState||200!=a.status&&304!=a.status||c(a.responseText)},4!=a.readyState&&a.send(null))},G=function(){var b=!1;try{b=new XMLHttpRequest}catch(c){b=new ActiveXObject("Microsoft.XMLHTTP")}return function(){return b}}();B();respond.update=B;d.addEventListener?d.addEventListener("resize",r,!1):d.attachEvent&&d.attachEvent("onresize",r)}})(this);
