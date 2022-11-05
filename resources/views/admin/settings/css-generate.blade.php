header { background-color: {{ $data['top_menu_bg_color'] }} }

header .login-form-menu ul li a, header .login-form-menu ul li span { color: {{ $data['top_menu_font_color'] }} }

.login-form { background-color: {{ $data['login_form_bg_color'] }} }

.login-form .form-buttons a { color: {{ $data['login_form_font_color'] }} }

.page-title, .page-heading { background-color: {{ $data['title_bg_color'] }}; color: {{ $data['title_font_color'] }} }

.form-fieldset legend,
.form-row .caption,
.subject-text-wrap > *,
.forum-list-wrap .forum-list-body .forum-list-title,
.forum-list-wrap .forum-list-body .forum-list-description,
.user-list thead th.rotate span,
.games-list-wrap ul.participant-list-wrap li,
.comment-list-wrap .comment-text-wrap .comment-text > * { color: {{ $data['text_main_color'] }} }

.forum-list-wrap .forum-list-info .forum-list-text,
.forum-list-wrap .forum-list-info .forum-list-pin,
.forum-list-wrap .forum-list-info .forum-list-edit,
.forum-list-wrap .forum-list-info .forum-list-move,
.subject-text-wrap .subject-controls-wrap .subject-etc,
.comment-list-wrap .comment-etc-wrap .comment-misc,
.comment-list-wrap .comment-etc-wrap .answer-action,
.comment-list-wrap .comment-etc-wrap .comment-link,
.comment-list-wrap .comment-etc-wrap .remove-comment-link { color: {{ $data['text_secondary_color'] }} }

.content-table thead tr { background-color: {{ $data['table_th_bg_color'] }} }
.content-table thead tr th { color: {{ $data['table_th_font_color'] }} }
.content-table tbody tr,
.user-list tbody tr,
.games-list-wrap ul.game-list li.group-games-list { background-color: {{ $data['table_td_bg_color'] }} }
.content-table tbody tr td,
.content-table tbody tr td a,
.content-table tbody tr td span,
.user-list tbody .user-list-value .user-list-score,
.user-list tbody .user-list-value .user-list-team { color: {{ $data['table_td_font_color'] }} }

.user-list tbody tr.user-list-heading,
.games-list-wrap .group-name { background-color: {{ $data['table_th_bg_color'] }}; color: {{ $data['table_th_font_color'] }} }

.btn.regular {
  background-color: {{ $data['secondary_btn']['normal']['background-color'] }};
  border: 1px solid {{ $data['secondary_btn']['normal']['border-color'] }};
  color: {{ $data['secondary_btn']['normal']['color'] }};
}
.btn.regular:hover {
  background-color: {{ $data['secondary_btn']['hover']['background-color'] }};
  border: 1px solid {{ $data['secondary_btn']['hover']['border-color'] }};
  color: {{ $data['secondary_btn']['hover']['color'] }};
}

.btn.success {
  background-color: {{ $data['primary_btn']['normal']['background-color'] }};
  border: 1px solid {{ $data['primary_btn']['normal']['border-color'] }};
  color: {{ $data['primary_btn']['normal']['color'] }};
}
.btn.success:hover {
  background-color: {{ $data['primary_btn']['hover']['background-color'] }};
  border: 1px solid {{ $data['primary_btn']['hover']['border-color'] }};
  color: {{ $data['primary_btn']['hover']['color'] }};
}