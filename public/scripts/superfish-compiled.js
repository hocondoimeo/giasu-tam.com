(function(a){a.fn.superfish=function(d){var c=a.fn.superfish,b=c.c,l=a(['<span class="',b.arrowClass,'"></span>'].join("")),g=function(){var c=a(this),b=h(c);clearTimeout(b.sfTimer);c.showSuperfishUl().siblings().hideSuperfishUl()},k=function(){var b=a(this),d=h(b),e=c.op;clearTimeout(d.sfTimer);d.sfTimer=setTimeout(function(){e.retainPath=-1<a.inArray(b[0],e.$path);b.hideSuperfishUl();e.$path.length&&1>b.parents(["li.",e.hoverClass].join("")).length&&g.call(e.$path)},e.delay)},h=function(a){a=a.parents(["ul.",
b.menuClass,":first"].join(""))[0];c.op=c.o[a.serial];return a};return this.each(function(){var m=this.serial=c.o.length,f=a.extend({},c.defaults,d);f.$path=a("li."+f.pathClass,this).slice(0,f.pathLevels).each(function(){a(this).addClass([f.hoverClass,b.bcClass].join(" ")).filter("li:has(ul)").removeClass(f.pathClass)});c.o[m]=c.op=f;a("li:has(ul)",this)[a.fn.hoverIntent&&!f.disableHI?"hoverIntent":"hover"](g,k).each(function(){f.autoArrows&&a(">a:first-child",this).addClass(b.anchorClass).append(l.clone())}).not("."+
b.bcClass).hideSuperfishUl();var e=a("a",this);e.each(function(a){var b=e.eq(a).parents("li");e.eq(a).focus(function(){g.call(b)}).blur(function(){k.call(b)})});f.onInit.call(this)}).each(function(){var d=[b.menuClass];!c.op.dropShadows||a.browser.msie&&7>a.browser.version||d.push(b.shadowClass);a(this).addClass(d.join(" "))})};var b=a.fn.superfish;b.o=[];b.op={};b.IE7fix=function(){var d=b.op;a.browser.msie&&6<a.browser.version&&d.dropShadows&&void 0!=d.animation.opacity&&this.toggleClass(b.c.shadowClass+
"-off")};b.c={bcClass:"sf-breadcrumb",menuClass:"sf-js-enabled",anchorClass:"sf-with-ul",arrowClass:"sf-sub-indicator",shadowClass:"sf-shadow"};b.defaults={hoverClass:"sfHover",pathClass:"overideThisToUse",pathLevels:1,delay:800,animation:{opacity:"show"},speed:"normal",autoArrows:!0,dropShadows:!0,disableHI:!1,onInit:function(){},onBeforeShow:function(){},onShow:function(){},onHide:function(){}};a.fn.extend({hideSuperfishUl:function(){var d=b.op,c=!0===d.retainPath?d.$path:"";d.retainPath=!1;c=a(["li.",
d.hoverClass].join(""),this).add(this).not(c).removeClass(d.hoverClass).find(">ul").hide().css("visibility","hidden");d.onHide.call(c);return this},showSuperfishUl:function(){var a=b.op,c=this.addClass(a.hoverClass).find(">ul:hidden").css("visibility","visible");b.IE7fix.call(c);a.onBeforeShow.call(c);c.animate(a.animation,a.speed,function(){b.IE7fix.call(c);a.onShow.call(c)});return this}})})(jQuery);