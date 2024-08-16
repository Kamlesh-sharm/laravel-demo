@extends('frontend.layout')

@section('pageHeading')
  {{ __('Blog Details') }}
@endsection

@php
  $metaKeys = !empty($details->meta_keywords) ? $details->meta_keywords : '';
  $metaDesc = !empty($details->meta_description) ? $details->meta_description : '';
@endphp

@section('meta-keywords', "$metaKeys")
@section('meta-description', "$metaDesc")

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center lazy"
      data-bg="{{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }}">
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ strlen($details->title) > 30 ? mb_substr($details->title, 0, 30) . '...' : $details->title }}</h1>

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Post Details') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="blog-details-wrapper section-padding section-bg">
      <div class="container">
        <div class="row">
          <!-- Blog Details Section Start -->
          <div class="col-lg-8">
            <div class="post-details">
              <div class="entry-header">
                <div class="post-thumb">
                  <img class="lazy" data-src="{{ asset('assets/img/blogs/' . $details->blog->blog_img) }}"
                    alt="image">
                </div>

                <ul class="entry-meta list-inline">
                  <li><a href="#"><i class="far fa-user-alt"></i>{{ __('Admin') }}</a></li>
                  <li><a href="#"><i
                        class="far fa-calendar-alt"></i>{{ date_format($details->blog->created_at, 'F d, Y') }}</a></li>
                </ul>
                <h2 class="entry-title">{{ convertUtf8($details->title) }}</h2>
              </div>

              <div class="entry-content">
                <div class="summernote-content">
                  {!! $details->content !!}
                </div>
              </div>

              <div class="entry-footer d-flex justify-content-md-between">
                <ul class="social-share list-inline">
                  <li class="title">{{ __('Share') }}</li>
                  <li><a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                        class="fab fa-facebook-f"></i></a></li>
                  <li><a
                      href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i
                        class="fab fa-twitter"></i></a></li>
                  <li><a href="//plus.google.com/share?url={{ urlencode(url()->current()) }}"><i
                        class="fab fa-google-plus-g"></i></a></li>
                  <li><a
                      href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ convertUtf8($details->title) }}"><i
                        class="fab fa-linkedin-in"></i></a></li>
                </ul>
              </div>
            </div>

            <div id="disqus_thread"></div>


          </div>
          <!-- Blog Details Section End -->

          @includeIf('frontend.blogs.blog_sidebar')
        </div>
      </div>
    </section>
  </main>
@endsection

@section('script')
  <script>
    "use strict";
    var shortName = "{{ $websiteInfo->disqus_shortname }}";
  </script>
  <script src="{{ asset('assets/js/blog.js') }}"></script>
@endsection
