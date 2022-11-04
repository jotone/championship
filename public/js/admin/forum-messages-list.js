(()=>{"use strict";var e,t={1648:()=>{function e(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function t(t){for(var a=1;a<arguments.length;a++){var o=null!=arguments[a]?arguments[a]:{};a%2?e(Object(o),!0).forEach((function(e){n(t,e,o[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(o)):e(Object(o)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(o,e))}))}return t}function n(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function a(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function o(e,t){for(var n=0;n<t.length;n++){var a=t[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}var r=function(){function e(n){var o=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;a(this,e),this.text=n||"Підтвердьте свою дію.",this.options={ok:"OK",cancel:"Скасувати"},o&&(this.options=t(t({},this.options),o)),this.overlay=$(".overlay")}var n,r,i;return n=e,(r=[{key:"close",value:function(){this.overlay.find(".confirmation-popup").fadeOut(250,(function(){$(this).closest(".overlay").hide(),$(this).closest(".confirmation-popup").remove()}))}},{key:"open",value:function(){var e=this;return new Promise((function(t){e.overlay.find(".popup-wrap, .preload").hide(),e.overlay.find(".confirmation-popup").remove(),e.overlay.append(e.template()),e.overlay.css({display:"flex"}),e.overlay.find(".confirmation-popup").on("click",'button[name="accept"]',(function(){e.close(),t(!0)})).on("click",'button[name="cancel"], .close',(function(){e.close(),t(!1)}))}))}},{key:"template",value:function(){return'\n    <div class="confirmation-popup">\n      <div class="close">\n        <i class="fas fa-times-circle"></i>\n      </div>\n      <div class="question-text">'.concat(this.text,'</div>\n      <div class="buttons-wrap">\n        <button name="accept" type="button" class="btn success">').concat(this.options.ok,'</button>\n        <button name="cancel" type="button" class="btn cancel">').concat(this.options.cancel,"</button>\n      </div>\n    </div>")}}])&&o(n.prototype,r),i&&o(n,i),Object.defineProperty(n,"prototype",{writable:!1}),e}();function i(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==n)return;var a,o,r=[],i=!0,s=!1;try{for(n=n.call(e);!(i=(a=n.next()).done)&&(r.push(a.value),!t||r.length!==t);i=!0);}catch(e){s=!0,o=e}finally{try{i||null==n.return||n.return()}finally{if(s)throw o}}return r}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return s(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return s(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function s(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}var c={arrayDiff:function(e,t){return e.filter((function(e){return!t.includes(e)}))},buildUrl:function(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:1,a=e.split("/");return a[a.length-n]=t,a=a.join("/")},goto:function(e){return $("html").animate({scrollTop:e.offset().top-65},500)},parseUrl:function(){var e,t,n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,a=null===n?window.location.href:n,o=a.split("?"),r=i(o,2),s=r[0],c=r[1];if(void 0!==c){var l=c.split("#"),u=i(l,2);e=u[0],t=u[1],e=e.split("&").reduce((function(e,t){var n=t.split("=");return void 0!==n[1]&&(e[decodeURIComponent(n[0])]=decodeURIComponent(n[1])),e}),{})}return{hash:t||null,path:s,search:e||{}}},setRequestOrderParams:function(e,t){var n=c.parseUrl(e);return n.search["order[by]"]=t.by,n.search["order[dir]"]=t.dir,$('select[name="perPage"]').length&&(n.search.take=$('select[name="perPage"]').val()),"".concat(n.path,"?").concat(new URLSearchParams(n.search).toString())},toAscii:function(e){var t={Á:"A",á:"a",Ä:"A",ä:"a",À:"A",à:"a",Â:"A",â:"a",æ:"ae",ǽ:"ae",Ã:"A",Å:"A",Ǻ:"A",Ă:"A",Ǎ:"A",Æ:"AE",Ǽ:"AE",ã:"a",å:"a",ǻ:"a",ă:"a",ǎ:"a",Ά:"A",ª:"a",α:"a",ά:"a",ἀ:"a",ἁ:"a",ἂ:"a",ἃ:"a",ἄ:"a",ἅ:"a",ἆ:"a",ἇ:"a",Ἀ:"A",Ἁ:"A",Ἂ:"A",Ἃ:"A",Ἄ:"A",Ἅ:"A",Ἆ:"A",Ἇ:"A",ᾰ:"a",ᾱ:"a",ᾲ:"a",ᾳ:"a",ᾴ:"a",ᾶ:"a",ᾷ:"a",Ᾰ:"A",Ᾱ:"A",Ὰ:"A",Ά:"A",ᾼ:"A",Ⱥ:"A",ⱥ:"a",Ȧ:"A",ȧ:"a",Ә:"A",ә:"a",Ĉ:"C",Ċ:"C",Ç:"C",ç:"c",ĉ:"c",ċ:"c",Ȼ:"C",ȼ:"c",č:"c",Č:"C",ć:"c",Ć:"C",Ð:"D",Đ:"D",ð:"d",đ:"d",Δ:"D",δ:"d",É:"E",é:"e",Ë:"E",ë:"e",È:"E",è:"e",Ê:"E",ê:"e",Ĕ:"E",Ė:"E",ĕ:"e",ė:"e",Έ:"E",ε:"e",έ:"e",Ȩ:"E",ȩ:"e",Ɇ:"E",ɇ:"e",ƒ:"f",φ:"f",Ѳ:"F",ѳ:"f",Ғ:"G",ғ:"g",Ĝ:"G",Ġ:"G",ĝ:"g",ġ:"g",γ:"gh",Ĥ:"H",Ħ:"H",ĥ:"h",ħ:"h",Ή:"I",Í:"I",í:"i",Ï:"I",ï:"i",Ì:"I",ì:"i",Î:"I",î:"i",Ĩ:"I",Ĭ:"I",Ǐ:"I",Ί:"I",Į:"I",Ĳ:"Ij",ĩ:"i",ĭ:"i",ǐ:"i",į:"i",ĳ:"ij",Ĵ:"J",ĵ:"j",η:"i",ή:"i",ι:"i",ί:"i",ϊ:"i",ΐ:"i",Ꙗ:"Ja",ꙗ:"ja",Ɨ:"I",ɨ:"i",i:"i",Ɉ:"J",ɉ:"j",ĸ:"k",Қ:"Q",қ:"q",Ĺ:"L",Ľ:"L",Ŀ:"L",ĺ:"l",ľ:"l",ŀ:"l",λ:"l",Ƚ:"L",ƚ:"l",Ḷ:"L",ḷ:"l",μ:"m",Ñ:"N",ñ:"n",ŉ:"n",Ŋ:"N",ŋ:"n",Ǹ:"N",ǹ:"n",Ꞥ:"N",ꞥ:"n",Ṅ:"N",ṅ:"n",Ṇ:"N",ṇ:"n",Ң:"N",ң:"n",Ó:"O",ó:"o",Ö:"O",ö:"o",Ò:"O",ò:"o",Ô:"O",ô:"o",Õ:"O",Ō:"O",Ŏ:"O",Ǒ:"O",Ő:"O",Ơ:"O",Ø:"O",Ǿ:"O",Œ:"OE",õ:"o",ō:"o",ŏ:"o",ǒ:"o",ő:"o",ơ:"o",ø:"o",ǿ:"o",º:"o",œ:"oe",Ό:"O",Ω:"O",ό:"o",ω:"o",Ǫ:"O",ǫ:"o",Ɵ:"O",ɵ:"o",Ȯ:"O",ȯ:"o",Ψ:"Ps",ψ:"ps",π:"p",Ѱ:"Ps",ѱ:"ps",Ŕ:"R",Ŗ:"R",ŕ:"r",ŗ:"r",ρ:"r",Ŝ:"S",Ș:"S",ŝ:"s",ș:"s",ſ:"s",Σ:"S",σ:"s",ς:"s",Ŝ̀:"S",Ꞩ:"S",ꞩ:"s",Ṡ:"S",ṡ:"s",Ṣ:"S",ṣ:"s",š:"s",Š:"S",Ţ:"T",Ț:"T",Ŧ:"T",Þ:"Th",ţ:"t",ț:"t",ŧ:"t",þ:"th",Θ:"Th",τ:"t",ϑ:"th",θ:"th",Ⱦ:"T",ⱦ:"t",Ṫ:"T",ṫ:"t",Ṭ:"T",ṭ:"t",Ú:"U",ú:"u",Ü:"U",ü:"u",Ù:"U",ù:"u",Û:"U",û:"u",Ũ:"U",Ŭ:"U",Ű:"U",Ų:"U",Ư:"U",Ǔ:"U",Ǖ:"U",Ǘ:"U",Ǚ:"U",Ǜ:"U",ũ:"u",ŭ:"u",ű:"u",ų:"u",ư:"u",ǔ:"u",ǖ:"u",ǘ:"u",ǚ:"u",ǜ:"u",Ʉ:"U",ʉ:"u",Ʊ:"U",ʊ:"u",Ө:"O",ө:"o",β:"v",ϐ:"v",Ŵ:"W",ŵ:"w",Ώ:"W",ώ:"w",Ẁ:"W",ẁ:"w",Ẃ:"W",ẃ:"w",Ẅ:"W",ẅ:"w",Ξ:"Ks",ξ:"ks",Ѯ:"Ks",ѯ:"ks",Ý:"Y",ý:"y",Ÿ:"Y",Ŷ:"Y",ÿ:"y",ŷ:"y",ϒ:"Y",Ύ:"Y",Ϋ:"Y",ύ:"y",ΰ:"y",ϋ:"y",Ɏ:"Y",ɏ:"y",Ẏ:"Y",ẏ:"y",Ȳ:"Y",ȳ:"y",Ұ:"U",Ү:"U",ұ:"u",ү:"u",ζ:"z",Ẑ:"Z",ẑ:"z",Ƶ:"Z",ƶ:"z",Ẓ:"Z",ẓ:"z",А:"A",Б:"B",В:"V",Г:"G",Д:"D",Ѓ:"Gj",Е:"E",Ж:"Z",З:"Z",Ѕ:"Dz",И:"I",Ј:"j",К:"K",Л:"L",Љ:"Lj",М:"M",Н:"N",Њ:"Nj",О:"O",П:"P",Р:"R",С:"S",Т:"T",Ќ:"Kj",У:"U",Ф:"F",Х:"X",Ц:"C",Ч:"C",Џ:"Dz",Ш:"S",а:"a",б:"b",в:"v",г:"g",д:"d",ѓ:"gj",е:"e",ж:"z",з:"z",ѕ:"dz",и:"i",ј:"j",к:"k",л:"l",љ:"lj",м:"m",н:"n",њ:"nj",о:"o",п:"p",р:"r",с:"s",т:"t",ќ:"kj",у:"u",ф:"f",х:"x",ц:"c",ч:"c",џ:"dz",ш:"s",Й:"i",Щ:"Shh",Ъ:"",Ь:"",Ю:"Iu",Я:"Ia",й:"i",щ:"shh",ъ:"",ь:"",ю:"iu",я:"ia",Ё:"E",ё:"e",Ы:"Y",ы:"y",Э:"E",э:"e",І:"I",і:"i",Ѣ:"E",ѣ:"e",Є:"Je",є:"je",Ґ:"G",ґ:"g",Ї:"Yi",ї:"yi",अ:"a",आ:"aa",ए:"e",ई:"ii",ऍ:"ei",ऎ:"ae",ऐ:"ai",इ:"i",ओ:"o",ऑ:"oi",ऒ:"oii",ऊ:"uu",औ:"ou",उ:"u",ब:"B",भ:"Bha",च:"Ca",छ:"Chha",ड:"Da",ढ:"Dha",फ:"Fa",फ़:"Fi",ग:"Ga",घ:"Gha",ग़:"Ghi",ह:"Ha",ज:"Ja",झ:"Jha",क:"Ka",ख:"Kha",ख़:"Khi",ल:"L",ळ:"Li",ऌ:"Li",ऴ:"Lii",ॡ:"Lii",म:"Ma",न:"Na",ङ:"Na",ञ:"Nia",ण:"Nae",ऩ:"Ni",ॐ:"oms",प:"Pa",क़:"Qi",र:"Ra",ऋ:"Ri",ॠ:"Ri",ऱ:"Ri",स:"Sa",श:"Sha",ष:"Shha",ट:"Ta",त:"Ta",ठ:"Tha",द:"Tha",थ:"Tha",ध:"Thha",ड़:"ugDha",ढ़:"ugDhha",व:"Va",य:"Ya",य़:"Yi",ज़:"Za",Ա:"A",Բ:"B",Գ:"G",Դ:"D",Ե:"E",Զ:"Z",Է:"E",Ը:"Y",Թ:"Th",Ժ:"Zh",Ի:"I",Խ:"Kh",Ծ:"Ts",Կ:"K",Հ:"H",Ձ:"Dz",Ղ:"Gh",Ճ:"Tch",Մ:"M",Յ:"Y",Ն:"N",Շ:"Sh",Ո:"Vo",Չ:"Ch",Պ:"P",Ջ:"J",Ռ:"R",Ս:"S",Վ:"V",Տ:"T",Ր:"R",Ց:"C",Ւ:"u",Փ:"Ph",Ք:"Q",և:"ev",Օ:"O",Ֆ:"F",ա:"a",բ:"b",գ:"g",դ:"d",ե:"e",զ:"z",է:"e",ը:"y",թ:"th",ժ:"zh",ի:"i",լ:"l",խ:"kh",ծ:"ts",կ:"k",հ:"h",ձ:"dz",ղ:"gh",ճ:"tch",մ:"m",յ:"y",ն:"n",շ:"sh",ո:"vo",չ:"ch",պ:"p",ջ:"j",ռ:"r",ս:"s",վ:"v",տ:"t",ր:"r",ց:"c",ւ:"u",փ:"ph",ք:"q",օ:"o",ֆ:"f",Ž:"Z",Ň:"N",Ş:"S",ž:"z",ň:"n",ş:"s",ı:"i",İ:"I",ğ:"g",Ğ:"G",Ē:"E",ē:"e",Ǳ:"DZ",ǲ:"Dz",ǳ:"dz",Ǉ:"LJ",ǈ:"Lj",ǉ:"lj",Ǌ:"NJ",ǋ:"Nj",ǌ:"nj",ა:"a",ბ:"b",გ:"g",დ:"d",ე:"e",ვ:"v",ზ:"z",თ:"t",ი:"i",კ:"k",ლ:"l",მ:"m",ნ:"n",ო:"o",პ:"p",ჟ:"zh",რ:"r",ს:"s",ტ:"t",უ:"u",ფ:"f",ქ:"q",ღ:"gh",ყ:"y",შ:"sh",ჩ:"ch",ც:"ts",ძ:"dz",წ:"ts",ჭ:"ch",ხ:"kh",ჯ:"j",ჰ:"h",Ѵ:"I",ѵ:"i",Ѥ:"Je",ѥ:"je",Ꙋ:"U",ꙋ:"u",Ѡ:"O",ѡ:"o",Ѿ:"Ot",ѿ:"ot",Ѫ:"U",ѫ:"u",Ѧ:"Ja",ѧ:"ja",Ѭ:"Ju",ѭ:"ju",Ѩ:"Ja",ѩ:"Ja"};e=e.trim().split("");var n="";for(var a in e)n+=void 0!==t[e[a]]?t[e[a]]:e[a];return n.toLowerCase().replace(/[^-a-z0-9_\/\.\#]/g,"-")}};function l(e,t){for(var n=0;n<t.length;n++){var a=t[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}var u=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e)}var t,n,a;return t=e,a=[{key:"push",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"success",n=Object.keys(localStorage).indexOf("notificationMessages")>=0?JSON.parse(localStorage.getItem("notificationMessages")):{};return n.hasOwnProperty(t)||(n[t]=[]),n[t].push(e),localStorage.setItem("notificationMessages",JSON.stringify(n)),this}},{key:"show",value:function(){var e=Object.keys(localStorage).indexOf("notificationMessages")>=0?JSON.parse(localStorage.getItem("notificationMessages")):{};if(!$.isEmptyObject(e)){for(var t in e)for(var n=0,a=e[t].length;n<a;n++)$(".notifications-wrap").append(this.template(e[t][n],t));$(".notifications-wrap li").each((function(){$(this).show(500).delay(2500).fadeOut(500,(function(){$(this).off("click").remove()}))})),localStorage.removeItem("notificationMessages")}return this}},{key:"template",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"success";return'\n    <li class="'.concat(t,'">\n      <div class="text-wrap">').concat(e,'</div>\n      <div class="close"><i class="fas fa-times-circle"></i></div>\n    </li>')}}],(n=null)&&l(t.prototype,n),a&&l(t,a),Object.defineProperty(t,"prototype",{writable:!1}),e}();$(document).ready((function(){$(".comment-list-wrap").on("keyup",'textarea[name="message"]',(function(){$(this).attr("data-changed",1)})).on("click","a.update",(function(e){e.preventDefault();var t=$(this).closest("form");if(void 0!==t.find('textarea[name="message"]').attr("data-changed")){var n=new FormData;n.append("_method","patch"),n.append("message",t.find('textarea[name="message"]').val().trim()),$.axios.post($(this).attr("href"),n).then((function(e){return 200===e.status&&u.push("Коментар успішно змінено.").show()}))}})).on("click","a.restore",(function(e){e.preventDefault();var t=new FormData;t.append("_method","patch"),$.axios.post($(this).attr("href"),t).then((function(e){return 200===e.status&&window.location.reload()}))})).on("click","a.pin",(function(e){e.preventDefault();var t=$(this).find("i"),n=new FormData;n.append("_method","patch"),n.append("pinned",t.hasClass("far")),$.axios.post($(this).attr("href"),n).then((function(e){return 200===e.status&&t.removeClass("fas").removeClass("far").addClass(e.data.pinned?"fas":"far")}))})).on("click","a.remove",(function(e){var t=this;e.preventDefault(),new r("Ви дійсно хочете видалити цей коментар?").open().then((function(e){return e&&$.axios.delete($(t).attr("href")).then((function(e){return 204===e.status&&window.location.reload()}))}))})),window.location.hash&&c.goto($("li"+window.location.hash))}))},9680:()=>{},3473:()=>{},2209:()=>{},6745:()=>{},9729:()=>{},9742:()=>{},6754:()=>{},9880:()=>{},2253:()=>{},3586:()=>{}},n={};function a(e){var o=n[e];if(void 0!==o)return o.exports;var r=n[e]={exports:{}};return t[e](r,r.exports,a),r.exports}a.m=t,e=[],a.O=(t,n,o,r)=>{if(!n){var i=1/0;for(u=0;u<e.length;u++){for(var[n,o,r]=e[u],s=!0,c=0;c<n.length;c++)(!1&r||i>=r)&&Object.keys(a.O).every((e=>a.O[e](n[c])))?n.splice(c--,1):(s=!1,r<i&&(i=r));if(s){e.splice(u--,1);var l=o();void 0!==l&&(t=l)}}return t}r=r||0;for(var u=e.length;u>0&&e[u-1][2]>r;u--)e[u]=e[u-1];e[u]=[n,o,r]},a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={695:0,719:0,761:0,999:0,723:0,141:0,170:0,701:0,660:0,421:0,317:0};a.O.j=t=>0===e[t];var t=(t,n)=>{var o,r,[i,s,c]=n,l=0;if(i.some((t=>0!==e[t]))){for(o in s)a.o(s,o)&&(a.m[o]=s[o]);if(c)var u=c(a)}for(t&&t(n);l<i.length;l++)r=i[l],a.o(e,r)&&e[r]&&e[r][0](),e[r]=0;return a.O(u)},n=self.webpackChunk=self.webpackChunk||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})(),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(1648))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(6754))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(9880))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(2253))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(3586))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(9680))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(3473))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(2209))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(6745))),a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(9729)));var o=a.O(void 0,[719,761,999,723,141,170,701,660,421,317],(()=>a(9742)));o=a.O(o)})();