$(document).ready((function(){$("form").on("submit",(function(s){s.preventDefault();var a=new FormData($(this)[0]);$.axios.post($(this).attr("action"),a).then((function(s){if(200===s.status)for(var a in s.data.messages)for(var e=0,t=s.data.messages[a].length;e<t;e++)$(".messages-wrap ul").append('<li class="'.concat(a,'">\n                  <div class="close"><i class="fas fa-times-circle"></i></div>\n                  <div class="message-text">').concat(s.data.messages[a][e],"</div>\n                </li>"))})).catch((function(s){if(s.response.data.errors)for(var a=0,e=s.response.data.errors.length;a<e;a++)$(".messages-wrap ul").append('<li class="error">\n                  <div class="close"><i class="fas fa-times-circle"></i></div>\n                  <div class="message-text">'.concat(s.response.data.errors[a],"</div>\n                </li>"))}))}))}));