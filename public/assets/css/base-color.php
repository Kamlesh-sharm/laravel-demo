<?php
header("Content-Type:text/css");

/**
 * get the primary and secondary color from query parameter,
 * $color1 = primary color and $color2 = secondary color
 */
$color1 = $_GET['color1'];
$color2 = $_GET['color2'];

// check, whether color has '#' or not, will return 0 or 1
function checkColor($color)
{
  return preg_match('/^#[a-f0-9]{6}/i', $color);
}

// if, color1 value does not contain '#', then add '#' before color1 value
if (isset($color1) && (checkColor($color1) == 0)) {
  $color1 = '#' . $color1;
}

// if, color2 value does not contain '#', then add '#' before color2 value
if (isset($color2) && (checkColor($color2) == 0)) {
  $color2 = '#' . $color2;
}

// then add primary and secondary color to style
?>

.home-two .menu-right-area a {
color: <?php echo htmlspecialchars($color2); ?>;
}
.home-two .menu-right-area .main-menu li.have-submenu::after {
color: <?php echo htmlspecialchars($color2); ?>;
}


.loader>span {
background: <?php echo htmlspecialchars($color1); ?>;
}

.header-top-area .top-contact-info i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.header-top-area a:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

.header-menu-area {
background-color: <?php echo htmlspecialchars($color2); ?>;
}
.home-two .header-top-area {
background-color: <?php echo htmlspecialchars($color2); ?>;
}
.menu-right-area .lang-select .nice-select {
background: <?php echo htmlspecialchars($color2); ?>;
}
.packages-area-v1 .packages-post-item h3.title:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}
.home-two .menu-right-area .lang-select .nice-select {
color: <?php echo htmlspecialchars($color2); ?>;
}
.home-two .menu-right-area .lang-select .nice-select::after {
color: <?php echo htmlspecialchars($color2); ?>;
}

.menu-right-area .lang-select .nice-select .list li:hover {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.menu-right-area .main-menu li.active-page>a, .menu-right-area .main-menu li.active-page.have-submenu::after, .menu-right-area .main-menu li.have-submenu:hover::after {
color: <?php echo htmlspecialchars($color1); ?>;
}

.menu-right-area a:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

.menu-right-area .main-menu li .submenu li a:hover {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.btn {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.btn.filled-btn:hover {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.input-wrap i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.nice-select:after {
color: <?php echo htmlspecialchars($color1); ?>;
}

.title-gallery .title-gallery-content {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.section-title span.title-top.with-border::before {
background: <?php echo htmlspecialchars($color1); ?>;
}

.section-title span.title-top {
color: <?php echo htmlspecialchars($color1); ?>;
}

h1,
h2,
h3,
h4,
h5,
h6 {
color: <?php echo htmlspecialchars($color2); ?>;
}

span.counter-number,
.fact-num {
color: <?php echo htmlspecialchars($color2); ?>;
}

.counter .counter-box i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.single-service-box .service-icon i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.latest-room::after {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.room-arrows span.slick-arrow:hover {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.single-room .room-desc .room-cat, .single-room .room-price {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.single-room .room-desc h4 a {
color: <?php echo htmlspecialchars($color2); ?>;
}

.single-room .room-desc h4 a:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

.single-room .room-desc .room-info i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.single-service-box:hover {
background-color: <?php echo htmlspecialchars($color1); ?>;
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.single-service-box:hover .service-counter {
color: <?php echo htmlspecialchars($color1); ?>;
}



.cta-section::after {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.cta-section .video-icon a {
color: <?php echo htmlspecialchars($color1); ?>;
}

.gallery-box:hover::before {
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.gallery-box::after {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.feature-left .feature-list li .feature-icon {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.feature-img .feature-abs-con {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.feature-img:after {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.feedback-section {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.feedback-section .feadback-slide .single-feedback-box:hover::before {
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.feedback-section .feadback-slide .single-feedback-box .feedback-author::before {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.hero-section ul.slick-dots li.slick-active button,
.feedback-section .feadback-slide ul.slick-dots li.slick-active button {
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.hero-section ul.slick-dots li button::before,
.feedback-section .feadback-slide ul.slick-dots li button::before {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.primary-bg {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.back-top .back-to-top {
background: <?php echo htmlspecialchars($color1); ?>;
}

footer {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

footer .widget.footer-widget ul.nav-widget li a:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

footer .widget.footer-widget ul.nav-widget li a::before {
background: <?php echo htmlspecialchars($color1); ?>;
}

footer .footer-bottom .footer-nav li:hover a {
color: <?php echo htmlspecialchars($color1); ?>;
}

.breadcrumb-area .breadcrumb-content li a {
color: <?php echo htmlspecialchars($color1); ?>;
}

.filter-view ul li.active-f-view a, .filter-view ul li:hover a {
color: <?php echo htmlspecialchars($color1); ?>;
}

.filter-view ul li::after {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.pagination-wrap li a {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.pagination-wrap li a:hover {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.pagination-wrap li.active a {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.sidebar-wrap .widget .widget-title::after,
.sidebar-wrap .widget .widget-title::before {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.sidebar-wrap .widget.fillter-widget .slider-range .ui-widget-header {
background: <?php echo htmlspecialchars($color1); ?>;
}

.sidebar-wrap .widget.fillter-widget .slider-range .ui-slider.ui-slider-horizontal.ui-widget.ui-widget-content.ui-corner-all {
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.sidebar-wrap .widget.fillter-widget .slider-range span.ui-slider-handle.ui-state-default.ui-corner-all {
border: 1px solid <?php echo htmlspecialchars($color1); ?>;
background: <?php echo htmlspecialchars($color1); ?>;
}

.post-thumb .price-tag {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.packages-big-slider .slick-arrow {
background: <?php echo htmlspecialchars($color2); ?>;
}

.packages-big-slider .slick-arrow:hover {
background-color: <?php echo htmlspecialchars($color1); ?>;
}


.room-details-wrapper .main-slider .slick-arrow {
background: <?php echo htmlspecialchars($color2); ?>;
}

.room-details-wrapper .main-slider .slick-arrow:hover {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.room-cat a {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.entry-meta li i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.room-details-wrapper .room-details-tab .nav.desc-tab-item li a.nav-link.active,
.room-details-wrapper .room-details-tab .nav.desc-tab-item li a.nav-link:hover {
color: <?php echo htmlspecialchars($color1); ?>;
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.comment-area .comment-list li .comment-desc a.reply-comment:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

.room-details-wrapper .room-details-tab .review-form button:hover {
background: <?php echo htmlspecialchars($color2); ?>;
}

.sidebar-wrap .widget.booking-widget .widget-title {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.sidebar-wrap .widget.category-widget .single-cat a:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

.single-service-box.service-white-bg:hover .service-counter {
color: <?php echo htmlspecialchars($color1); ?>;
}

.single-service-box.service-white-bg:hover {
background-color: <?php echo htmlspecialchars($color1); ?>;
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.feedback-slider-two ul.slick-dots li button::before {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.feedback-slider-two ul.slick-dots li.slick-active button {
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.service-details-section .service-sidebar .widgets h4.widget-title:before,
.service-details-section .service-sidebar .widgets h4.widget-title:after {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.service-details-section .service-sidebar .service-cat .service-cat-list li a:hover,
.service-details-section .service-sidebar .service-cat .service-cat-list li a:focus {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.single-blog-wrap .post-thumbnail::before {
background: <?php echo htmlspecialchars($color2); ?>;
}

.single-blog-wrap .blog-meta li i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.single-blog-wrap h3 a {
color: <?php echo htmlspecialchars($color2); ?>;
}

.sidebar-wrap .widget.search-widget {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.sidebar-wrap .widget.recent-news li .recent-post-desc h6 a {
color: <?php echo htmlspecialchars($color2); ?>;
}

.sidebar-wrap .widget.recent-news li .recent-post-desc h6 a:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

.comment-title::after,
.comment-title::before,
.comment-form-title::after,
.comment-form-title::before {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.gallery-filter li {
color: <?php echo htmlspecialchars($color2); ?>;
}

.gallery-filter li:before {
color: <?php echo htmlspecialchars($color1); ?>;
}

.gallery-filter li:hover,
.gallery-filter li.active {
color: <?php echo htmlspecialchars($color1); ?>;
}

.gallery-items .gallery-item::before {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.packages-sidebar .widget h4.widget-title:before,
.packages-sidebar .widget h4.widget-title:after {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

input,
select,
textarea,
.nice-select {
color: <?php echo htmlspecialchars($color2); ?>;
}

.packages-area-v1 .packages-sidebar .widget.price_ranger_widget .ui-widget .ui-slider-handle {
background: <?php echo htmlspecialchars($color1); ?>;
}

.packages-area-v1 .packages-sidebar .widget.price_ranger_widget .ui-widget .ui-widget-header {
background: <?php echo htmlspecialchars($color1); ?>;
}

.packages-area-v1 .packages-post-item .entry-content .post-meta ul li span i,
.ma-package-section .packages-post-item .entry-content .post-meta ul li span i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.packages-details-area .packages-details-wrapper .box-wrap h4.title:after {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.packages-details-area .packages-details-wrapper .schedule-wrapp .single-schedule .icon i {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.packages-details-area .packages-sidebar .information-widget ul.list li:before {
color: <?php echo htmlspecialchars($color1); ?>;
}

.feature-accordion .card .card-header button:hover,
.feature-accordion .card .card-header button.active-accordion {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.feature-accordion .card .card-header button {
color: <?php echo htmlspecialchars($color2); ?>;
}

.contact-info-section .contact-info-box .contact-icon {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.contact-info-section .contact-info-box:hover .contact-icon {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.contact-form h2.form-title::after {
background: <?php echo htmlspecialchars($color1); ?>;
}

.user-dashboard .user-sidebar .links li a:hover,
.user-dashboard .user-sidebar .links li.active-menu>a {
color: <?php echo htmlspecialchars($color1); ?>;
}

.user-dashboard .main-table .dataTables_wrapper td a.btn {
border: 1px solid <?php echo htmlspecialchars($color1); ?>;
}

.user-dashboard .main-table .dataTables_wrapper td a.btn:hover {
background: <?php echo htmlspecialchars($color1); ?>;
}

.user-dashboard .main-table .dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link {
background-color: <?php echo htmlspecialchars($color1); ?> !important;
}

.user-dashboard .main-table .dataTables_wrapper .dataTables_paginate .paginate_button .page-link:hover {
background-color: <?php echo htmlspecialchars($color1); ?> !important;
}

.user-dashboard .view-order-page .order-info-area .print .btn {
background: <?php echo htmlspecialchars($color1); ?>;
}

.user-dashboard .user-profile-details .edit-info-area .file-upload-area span {
background: <?php echo htmlspecialchars($color1); ?>;
}

.user-dashboard .user-profile-details .edit-info-area .btn {
background: <?php echo htmlspecialchars($color1); ?>;
}

.hero-section.slider-two .slick-arrow:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

.hero-section.slider-two ul.slick-dots li button::before {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.hero-section.slider-two ul.slick-dots li.slick-active button {
border-color: <?php echo htmlspecialchars($color1); ?>;
}

.btn.btn-black {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.single-feature-box .feature-icon::before {
background: <?php echo htmlspecialchars($color1); ?>;
}

.latest-room {
background-color: <?php echo htmlspecialchars($color2); ?>;
}

.latest-room .shape-three {
background-color: <?php echo htmlspecialchars($color1); ?>;
}

.room-box .room-content i {
color: <?php echo htmlspecialchars($color1); ?>;
}

.feedback-slider-two .client-name .client-job {
color: <?php echo htmlspecialchars($color1); ?>;
}

.single-latest-blog .latest-blog-desc .post-date i {
color: <?php echo htmlspecialchars($color1); ?>;
}
.packages-details-area .packages-details-wrapper .places-box a:hover {
background-color: <?php echo htmlspecialchars($color1); ?>;
}
.search-widget ul.categories li:hover::before, .search-widget ul.categories li:hover a, .search-widget ul.categories li.active::before, .search-widget ul.categories li.active a {
color: <?php echo htmlspecialchars($color1); ?>;
}
.single-feature-box .feature-icon i {
color: <?php echo htmlspecialchars($color1); ?>;
}
.sidebar-wrap .widget.category-widget ul li:hover a,
.sidebar-wrap .widget.category-widget ul li:hover::before,
.sidebar-wrap .widget.category-widget ul li.active::before,
.sidebar-wrap .widget.category-widget ul li.active a {
color: <?php echo htmlspecialchars($color1); ?>;
}
button.cookie-consent__agree {
background-color: <?php echo htmlspecialchars($color1); ?>;
}
.error-txt a {
background-color: <?php echo htmlspecialchars($color1); ?>;
border: 1px solid <?php echo htmlspecialchars($color1); ?>;
}

.error-txt a:hover {
color: <?php echo htmlspecialchars($color1); ?>;
}

footer .widget.footer-widget .recent-post li::before {
color: <?php echo htmlspecialchars($color1); ?>;
}
.latest-room .room-box h6 a {
color: <?php echo htmlspecialchars($color1); ?>;
}
.header-top-area .top-menu .btn.btn-primary {
    background-color: <?php echo htmlspecialchars($color1); ?>;
}
.header-top-area .top-menu .btn.btn-outline-primary {
    color: <?php echo htmlspecialchars($color1); ?>;
    border-color: <?php echo htmlspecialchars($color1); ?>;
}
