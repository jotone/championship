(()=>{var a=function(a,t){a.d>0?t.d.text(a.d+" д."):t.d.hide(),t.h.text((a.h+"").padStart(2,"0")+" г."),t.m.text((a.m+"").padStart(2,"0")+" хв.")};$(document).ready((function(){$(".form-wrap .game-timer-wrap").each((function(){if(void 0!==$(this).attr("data-start")){var t=$(this),e=((1e3*parseInt(t.attr("data-start"))-+new Date)/1e3).toFixed(0),r={d:t.find('.count-down-timer-wrap span[data-role="days"]'),h:t.find('.count-down-timer-wrap span[data-role="hours"]'),m:t.find('.count-down-timer-wrap span[data-role="minutes"]'),s:t.find('.count-down-timer-wrap span[data-role="seconds"]')};if(0<=e){var d={d:+Math.floor(e/86400),h:+Math.floor(e/3600)%60,m:+Math.floor(e/60)%60,s:+Math.floor(e%60)};a(d,r),t.show();var o=setInterval((function(){d.s<=0?(d.m<=0&&(d.h--,d.m=59,d.h<=0&&(d.d--,d.h=23)),d.m--,d.s=59):d.s--,a(d,r)(d.h<12&&d.h>6)&&t.addClass("green")(d.h<6&&d.h>1)&&t.removeClass("green").addClass("yellow")(d.h<1)&&t.removeClass("green").removeClass("yellow").addClass("red"),1>d.s&&0===d.h&&0===d.m&&0===d.d&&clearInterval(o)}),1e3)}}}))}))})();