(()=>{var t={296:t=>{function e(t,e,n){var a,r,o,i,l;function s(){var c=Date.now()-i;c<e&&c>=0?a=setTimeout(s,e-c):(a=null,n||(l=t.apply(o,r),o=r=null))}null==e&&(e=100);var c=function(){o=this,r=arguments,i=Date.now();var c=n&&!a;return a||(a=setTimeout(s,e)),c&&(l=t.apply(o,r),o=r=null),l};return c.clear=function(){a&&(clearTimeout(a),a=null)},c.flush=function(){a&&(l=t.apply(o,r),o=r=null,clearTimeout(a),a=null)},c}e.debounce=e,t.exports=e}},e={};function n(a){var r=e[a];if(void 0!==r)return r.exports;var o=e[a]={exports:{}};return t[a](o,o.exports,n),o.exports}(()=>{"use strict";function t(t,e){for(var n=0;n<e.length;n++){var a=e[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}var e=function(){function e(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,e)}var n,a,r;return n=e,r=[{key:"push",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"success",n=Object.keys(localStorage).indexOf("notificationMessages")>=0?JSON.parse(localStorage.getItem("notificationMessages")):{};return n.hasOwnProperty(e)||(n[e]=[]),n[e].push(t),localStorage.setItem("notificationMessages",JSON.stringify(n)),this}},{key:"show",value:function(){var t=Object.keys(localStorage).indexOf("notificationMessages")>=0?JSON.parse(localStorage.getItem("notificationMessages")):{};if(!$.isEmptyObject(t)){for(var e in t)for(var n=0,a=t[e].length;n<a;n++)$(".notifications-wrap").append(this.template(t[e][n],e));$(".notifications-wrap li").each((function(){$(this).show(500).delay(2500).fadeOut(500,(function(){$(this).off("click").remove()}))})),localStorage.removeItem("notificationMessages")}return this}},{key:"template",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"success";return'\n    <li class="'.concat(e,'">\n      <div class="text-wrap">').concat(t,'</div>\n      <div class="close"><i class="fas fa-times-circle"></i></div>\n    </li>')}}],(a=null)&&t(n.prototype,a),r&&t(n,r),Object.defineProperty(n,"prototype",{writable:!1}),e}(),a=n(296);function r(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null==n)return;var a,r,o=[],i=!0,l=!1;try{for(n=n.call(t);!(i=(a=n.next()).done)&&(o.push(a.value),!e||o.length!==e);i=!0);}catch(t){l=!0,r=t}finally{try{i||null==n.return||n.return()}finally{if(l)throw r}}return o}(t,e)||o(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function o(t,e){if(t){if("string"==typeof t)return i(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?i(t,e):void 0}}function i(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,a=new Array(e);n<e;n++)a[n]=t[n];return a}$(document).ready((function(){e.show(),$(".side-menu").on("click","li",(function(){$(this).children("ul").length&&$(this).toggleClass("active")}));var t=window.location.href,n=t.split("/");"create"===n[n.length-1]?t=n.slice(0,-1).join("/"):"edit"===n[n.length-1]&&(t=n.slice(0,-2).join("/")),$('.side-menu a[href="'.concat(t,'"]')).parents("li").addClass("active"),$("[data-slug]").each((function(){var t=r($(this).attr("data-slug").split("."),2),e=t[0],n=t[1],o=$("".concat(e,'[name="').concat(n,'"]'));$(this).on("keyup",(0,a.debounce)((function(){void 0===o.attr("data-changed")&&o.val(window.Helpers.toAscii($(this).val()).replace(/-+/g,"-"))}),250)),o.on("keyup",(function(){$(this).attr("data-changed","1")}))})),$(".image-upload-wrap").on("click",'button[name="upload"]',(function(t){t.preventDefault(),$(this).closest(".buttons-wrap").find('input[type="file"]').trigger("click")})).on("click",'button[name="clear"]',(function(t){t.preventDefault(),$(this).closest(".buttons-wrap").find('input[type="file"]').val(""),$(this).closest(".image-upload-wrap").find(".image-upload-preview").empty()})).on("change",'input[type="file"]',(function(){var t=$(this).prop("files")[0],e=$(this).closest(".image-upload-wrap").find(".image-upload-preview"),n=new FileReader;n.onload=function(t){!e.find("img").length&&e.append('<img src="" alt="No image&hellip;">'),e.find("img").attr("src",t.target.result)},n.readAsDataURL(t)})),$("form[data-xhr]").on("submit",(function(t){var n=this,a=void 0!==$(this).attr("data-redirect");t.preventDefault();var i,l=new FormData(this),s=new FormData,c=function(t,e){var n="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!n){if(Array.isArray(t)||(n=o(t))||e&&t&&"number"==typeof t.length){n&&(t=n);var a=0,r=function(){};return{s:r,n:function(){return a>=t.length?{done:!0}:{done:!1,value:t[a++]}},e:function(t){throw t},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,l=!0,s=!1;return{s:function(){n=n.call(t)},n:function(){var t=n.next();return l=t.done,t},e:function(t){s=!0,i=t},f:function(){try{l||null==n.return||n.return()}finally{if(s)throw i}}}}(l);try{for(c.s();!(i=c.n()).done;){var u=i.value;"undefined"!=typeof CKEDITOR&&u[0]in CKEDITOR.instances?s.append(u[0],CKEDITOR.instances[u[0]].getData()):s.append(u[0],u[1])}}catch(t){c.e(t)}finally{c.f()}var f=void 0!==$(this).attr("method")?$(this).attr("method"):"get";$.axios[f.toLowerCase()]($(this).attr("action"),s).then((function(t){if(void 0!==$(n).attr("data-msg")){var o=r($(n).attr("data-msg").split("."),2),i=o[0],l=o[1],s="".concat(i,' "').concat(t.data[l],'" was successfully ').concat(201===t.status?"created":"modified",".");e.push(s),a?window.location=window.Helpers.buildUrl($(n).attr("data-redirect"),t.data.id,2):201===t.status?window.location.reload():e.show()}})).catch((function(t){var n=t.response;if(400<=n.status&&500>=n.status&&(n.data.hasOwnProperty("message")&&e.push(n.data.message,"error"),n.data.hasOwnProperty("errors"))){for(var a in n.data.errors)for(var r=n.data.errors[a],o=0,i=r.length;o<i;o++)e.push(r[o],"error");e.show()}})).finally((function(){return $(".overlay, .overlay .preload").hide()}))}))}))})()})();