@extends('frontend.layout')

@section('pageHeading')
  @if (!is_null($pageHeading))
    {{ $pageHeading->blogs_title }}
  @endif
@endsection

@php
    $metaKeys = !empty($seo->meta_keyword_blogs) ? $seo->meta_keyword_blogs : '';
    $metaDesc = !empty($seo->meta_description_blogs) ? $seo->meta_description_blogs : '';
@endphp

@section('meta-keywords', "$metaKeys")
@section('meta-description', "$metaDesc")

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center lazy" data-bg="{{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }}" >
      <div class="container">
        <div class="breadcrumb-content text-center">
          @if (!is_null($pageHeading))
            <h1>{{ convertUtf8($pageHeading->blogs_title) }}</h1>
          @endif

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            @if (!is_null($pageHeading))
              <li>{{ convertUtf8($pageHeading->blogs_title) }}</li>
            @endif
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- All Blogs Section Start -->
    <section class="blog-wrapper section-padding section-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            @if (count($blogInfos) == 0)
                <div class="bg-white py-5">
                    <h3 class="text-center">{{ __('No Post Found!') }}</h3>
                </div>
            @else
              <div class="post-loop">
                @foreach ($blogInfos as $blogInfo)
                  <!-- Single Blog -->
                  <div class="single-blog-wrap">
                    <div class="post-thumbnail">
                      <img class="lazy" data-src="{{ asset('assets/img/blogs/' . $blogInfo->blog_img) }}" alt="image">
                    </div>

                    <div class="post-desc">
                      <ul class="blog-meta list-inline">
                        <li><a href="#"><i class="far fa-user-alt"></i>{{ __('Admin') }}</a></li>

                        @php
                          $date = \Carbon\Carbon::parse($blogInfo->created_at);
                        @endphp
                        <li><a href="#"><i class="far fa-calendar-alt"></i>{{ date_format($date, 'F d, Y') }}</a></li>
                      </ul>

                      <h3><a href="{{ route('blog_details', ['id' => $blogInfo->blog_id, 'slug' => $blogInfo->slug]) }}">{{ convertUtf8($blogInfo->title) }}</a></h3>

                      <a href="{{ route('blog_details', ['id' => $blogInfo->blog_id, 'slug' => $blogInfo->slug]) }}" class="btn filled-btn">
                        {{ __('View Post') }} <i class="far fa-long-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                @endforeach
              </div>

              <!-- Pagination Wrap -->
              {{$blogInfos->appends(['term' => request()->input('term'), 'category' => request()->input('category')])->links()}}
            @endif
          </div>

          @includeIf('frontend.blogs.blog_sidebar')
        </div>
      </div>
    </section>
    <!-- All Blogs Section End -->
  </main>
@endsection
