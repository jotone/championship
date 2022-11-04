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
.content-table tbody tr { background-color: {{ $data['table_td_bg_color'] }} }
.content-table tbody tr td,
.content-table tbody tr td a,
.content-table tbody tr td span { background-color: {{ $data['table_td_font_color'] }} }