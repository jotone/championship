(()=>{"use strict";var t=function(t,a){var e=$("#comment-form");t.hasClass("active")?(t.removeClass("active"),e.hide(150)):(!a.find("#comment-form").length&&e.appendTo(a),$('.comment-list-wrap button[name="showCommentForm"]').removeClass("active"),$(".comment-list-wrap a.answer-action").removeClass("active"),t.addClass("active"),e.show(150))};$(document).ready((function(){var a;a=$('textarea[name="message"]'),CKEDITOR.replace(a[0],{language:"uk",removePlugins:"sourcearea",toolbarGroups:[{name:"basicstyles",groups:["basicstyles"]},{name:"paragraph",groups:["list","blocks"]},{name:"styles",groups:["styles"]}],removeButtons:"Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord"}),$(".comment-list-wrap").on("click",'button[name="showCommentForm"]',(function(){t($(this),$(this).closest(".add-comment-wrap"))})).on("click","a.answer-action",(function(a){a.preventDefault(),t($(this),$(this).closest(".comment-text-wrap").find(".comment-form-wrap"))})),$("#comment-form").on("submit",(function(t){t.preventDefault();var a=$(this).attr("action");if(void 0!==a){var e=window.location.pathname,s=new FormData($(this)[0]);s.append("message",CKEDITOR.instances.message.getData()),s.append("parent",$(this).closest("li").attr("data-post")||0),s.append("topic",e.substring(e.lastIndexOf("/")+1)),$.axios.post(a,s).then((function(t){if(201===t.status){var a=$('.comment-list-wrap li[data-post="'.concat(t.data.parent,'"]'));console.log(a),(a.length?a.children("ul"):$(".comment-list-wrap > ul")).append((e=t.data,'\n<li data-post="'.concat(e.id,'" class="comment-author">\n  <div class="comment-text-wrap">\n    <div class="comment-text">').concat(e.message,'</div>\n    <div class="comment-etc-wrap">\n      <span class="comment-misc" title="Автор коменатря">Написав: ').concat(e.author,'</span>\n      <span class="comment-misc" title="Дата створення коментаря">').concat(e.created,'</span>        \n      <a class="answer-action" href="#" title="Написати відповідь до цього коментаря">Відповісти</a>\n    </div>\n    <div class="comment-form-wrap"></div>\n  </div>\n  <ul></ul>\n</li>')))}var e;$("#comment-form").hide(150),CKEDITOR.instances.message.setData("")}))}}))}))})();