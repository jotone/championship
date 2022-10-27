(()=>{"use strict";var e,t={3195:()=>{function e(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function t(t){for(var o=1;o<arguments.length;o++){var a=null!=arguments[o]?arguments[o]:{};o%2?e(Object(a),!0).forEach((function(e){n(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):e(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function n(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function o(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function a(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}var r=function(){function e(n){var a=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;o(this,e),this.text=n||"Підтвердьте свою дію.",this.options={ok:"OK",cancel:"Скасувати"},a&&(this.options=t(t({},this.options),a)),this.overlay=$(".overlay")}var n,r,i;return n=e,(r=[{key:"close",value:function(){this.overlay.find(".confirmation-popup").fadeOut(250,(function(){$(this).closest(".overlay").hide(),$(this).closest(".confirmation-popup").remove()}))}},{key:"open",value:function(){var e=this;return new Promise((function(t){e.overlay.find(".popup-wrap, .preload").hide(),e.overlay.find(".confirmation-popup").remove(),e.overlay.append(e.template()),e.overlay.css({display:"flex"}),e.overlay.find(".confirmation-popup").on("click",'button[name="accept"]',(function(){e.close(),t(!0)})).on("click",'button[name="cancel"], .close',(function(){e.close(),t(!1)}))}))}},{key:"template",value:function(){return'\n    <div class="confirmation-popup">\n      <div class="close">\n        <i class="fas fa-times-circle"></i>\n      </div>\n      <div class="question-text">'.concat(this.text,'</div>\n      <div class="buttons-wrap">\n        <button name="accept" type="button" class="btn success">').concat(this.options.ok,'</button>\n        <button name="cancel" type="button" class="btn cancel">').concat(this.options.cancel,"</button>\n      </div>\n    </div>")}}])&&a(n.prototype,r),i&&a(n,i),Object.defineProperty(n,"prototype",{writable:!1}),e}();function i(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}var s=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e)}var t,n,o;return t=e,o=[{key:"push",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"success",n=Object.keys(localStorage).indexOf("notificationMessages")>=0?JSON.parse(localStorage.getItem("notificationMessages")):{};return n.hasOwnProperty(t)||(n[t]=[]),n[t].push(e),localStorage.setItem("notificationMessages",JSON.stringify(n)),this}},{key:"show",value:function(){var e=Object.keys(localStorage).indexOf("notificationMessages")>=0?JSON.parse(localStorage.getItem("notificationMessages")):{};if(!$.isEmptyObject(e)){for(var t in e)for(var n=0,o=e[t].length;n<o;n++)$(".notifications-wrap").append(this.template(e[t][n],t));$(".notifications-wrap li").each((function(){$(this).show(500).delay(2500).fadeOut(500,(function(){$(this).off("click").remove()}))})),localStorage.removeItem("notificationMessages")}return this}},{key:"template",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"success";return'\n    <li class="'.concat(t,'">\n      <div class="text-wrap">').concat(e,'</div>\n      <div class="close"><i class="fas fa-times-circle"></i></div>\n    </li>')}}],(n=null)&&i(t.prototype,n),o&&i(t,o),Object.defineProperty(t,"prototype",{writable:!1}),e}();$(document).ready((function(){$(".comment-list-wrap").on("keyup",'textarea[name="message"]',(function(){$(this).attr("data-changed",1)})).on("click","a.update",(function(e){e.preventDefault();var t=$(this).closest("form");if(void 0!==t.find('textarea[name="message"]').attr("data-changed")){var n=new FormData;n.append("_method","patch"),n.append("message",t.find('textarea[name="message"]').val().trim()),$.axios.post($(this).attr("href"),n).then((function(e){return 200===e.status&&s.push("Коментар успішно змінено.").show()}))}})).on("click","a.restore",(function(e){e.preventDefault();var t=new FormData;t.append("_method","patch"),$.axios.post($(this).attr("href"),t).then((function(e){return 200===e.status&&window.location.reload()}))})).on("click","a.pin",(function(e){e.preventDefault();var t=$(this).find("i"),n=new FormData;n.append("_method","patch"),n.append("pinned",t.hasClass("far")),$.axios.post($(this).attr("href"),n).then((function(e){return 200===e.status&&t.removeClass("fas").removeClass("far").addClass(e.data.pinned?"fas":"far")}))})).on("click","a.remove",(function(e){var t=this;e.preventDefault(),new r("Ви дійсно хочете видалити цей коментар?").open().then((function(e){return e&&$.axios.delete($(t).attr("href")).then((function(e){return 204===e.status&&window.location.reload()}))}))}))}))},9680:()=>{},3473:()=>{},8486:()=>{},6754:()=>{},9880:()=>{},2253:()=>{},3586:()=>{}},n={};function o(e){var a=n[e];if(void 0!==a)return a.exports;var r=n[e]={exports:{}};return t[e](r,r.exports,o),r.exports}o.m=t,e=[],o.O=(t,n,a,r)=>{if(!n){var i=1/0;for(f=0;f<e.length;f++){for(var[n,a,r]=e[f],s=!0,c=0;c<n.length;c++)(!1&r||i>=r)&&Object.keys(o.O).every((e=>o.O[e](n[c])))?n.splice(c--,1):(s=!1,r<i&&(i=r));if(s){e.splice(f--,1);var l=a();void 0!==l&&(t=l)}}return t}r=r||0;for(var f=e.length;f>0&&e[f-1][2]>r;f--)e[f]=e[f-1];e[f]=[n,a,r]},o.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={695:0,719:0,761:0,999:0,723:0,170:0,421:0,317:0};o.O.j=t=>0===e[t];var t=(t,n)=>{var a,r,[i,s,c]=n,l=0;if(i.some((t=>0!==e[t]))){for(a in s)o.o(s,a)&&(o.m[a]=s[a]);if(c)var f=c(o)}for(t&&t(n);l<i.length;l++)r=i[l],o.o(e,r)&&e[r]&&e[r][0](),e[r]=0;return o.O(f)},n=self.webpackChunk=self.webpackChunk||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})(),o.O(void 0,[719,761,999,723,170,421,317],(()=>o(3195))),o.O(void 0,[719,761,999,723,170,421,317],(()=>o(6754))),o.O(void 0,[719,761,999,723,170,421,317],(()=>o(9880))),o.O(void 0,[719,761,999,723,170,421,317],(()=>o(2253))),o.O(void 0,[719,761,999,723,170,421,317],(()=>o(3586))),o.O(void 0,[719,761,999,723,170,421,317],(()=>o(9680))),o.O(void 0,[719,761,999,723,170,421,317],(()=>o(3473)));var a=o.O(void 0,[719,761,999,723,170,421,317],(()=>o(8486)));a=o.O(a)})();