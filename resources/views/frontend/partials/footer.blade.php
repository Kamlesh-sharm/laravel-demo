<footer>
  <div class="container">
    @if ($sections->top_footer_section == 1)
      <div class="footer-top">
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="widget footer-widget">
              @if (!is_null($websiteInfo->footer_logo))
                <div class="footer-logo">
                  <img class="lazy" data-src="{{ asset('assets/img/' . $websiteInfo->footer_logo) }}" alt="footer logo">
                </div>
              @endif

              @if (!is_null($footerInfo))
                <p>{{ $footerInfo->about_company }}</p>
              @endif

              @if (count($socialLinkInfos) > 0)
                <ul class="social-icons">
                  @foreach ($socialLinkInfos as $socialLinkInfo)
                    <li>
                      <a href="{{ $socialLinkInfo->url }}"><i class="{{ $socialLinkInfo->icon }}"></i></a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="widget footer-widget">
              <h4 class="widget-title">{{ __('Quick Links') }}</h4>
              @if (count($quickLinkInfos) == 0)
                <h5 class="text-white">{{ __('No Quick Link Found!') }}</h5>
              @else
                <ul class="nav-widget clearfix">
                  @foreach ($quickLinkInfos as $quickLinkInfo)
                    <li><a href="{{ $quickLinkInfo->url }}">{{ $quickLinkInfo->title }}</a></li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>

          <div class="col-lg-4">
            <div class="widget footer-widget">
              <h4 class="widget-title">{{ __('Recent Blogs') }}</h4>
              @if (count($footerBlogInfos) == 0)
                <h5 class="text-white">{{ __('No Recent Blog Found!') }}</h5>
              @else
                <ul class="recent-post">
                  @foreach ($footerBlogInfos as $footerBlogInfo)
                    <li>
                      <h6>
                        <a
                          href="{{ route('blog_details', ['id' => $footerBlogInfo->blog_id, 'slug' => $footerBlogInfo->slug]) }}">
                          {{ strlen($footerBlogInfo->title) > 40 ? mb_substr($footerBlogInfo->title, 0, 40, 'UTF-8') . '...' : $footerBlogInfo->title }}
                        </a>
                      </h6>
                      <span
                        class="recent-post-date">{{ date_format($footerBlogInfo->blog->created_at, 'F d, Y') }}</span>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        </div>
      </div>
    @endif

    @if ($sections->copyright_section == 1)
      <div class="footer-bottom">
        <div class="row text-center">

          <div class="col-md-12">
            @if (!is_null($footerInfo))
              <div class="summernote-content">
                {!! $footerInfo->copyright_text !!}
              </div>
            @endif
          </div>
        </div>
      </div>
    @endif
  </div>
</footer>
