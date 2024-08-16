<div class="col-lg-4">
  <!-- Sidebar Area -->
  <div class="sidebar-wrap">
    <div class="widget search-widget">
      <h4 class="widget-title">{{ __('Search Here') }}</h4>
      <form action="{{ route('blogs') }}" method="GET">
        <input type="hidden" name="category" value="{{request()->input('category')}}">
        <input type="text" name="term" placeholder="{{ __('Search By Post Name') }}" value="{{request()->input('term')}}">
        <button><i class="far fa-search"></i></button>
      </form>
    </div>

    <div class="widget category-widget">
      <h4 class="widget-title">{{ __('Category') }}</h4>
      @if (count($blogCategories) == 0)
          <h4>{{ __('No Blog Category Found!') }}</h4>
      @else
        <ul>
            <li class="@if(empty(request()->input('category'))) active @endif"><a href="{{ route('blogs') }}">{{ __('All') }}</a></li>
            @foreach ($blogCategories as $blogCategory)
              <li class="@if($blogCategory->id == request()->input('category')) active @endif"><a href="{{ route('blogs', ['category' => $blogCategory->id]) }}">{{ $blogCategory->name }}</a></li>
            @endforeach
        </ul>
      @endif
    </div>

    <div class="widget recent-news">
      <h4 class="widget-title">{{ __('Latest Posts') }}</h4>
      @if (count($recentBlogs) == 0)
        <h4>{{ __('No Latest Post Found!') }}</h4>
      @else
        <ul>
          @foreach ($recentBlogs as $recentBlog)
            <li>
              <div class="recent-post-img">
                <img class="lazy" data-src="{{ asset('assets/img/blogs/' . $recentBlog->blog->blog_img) }}" alt="image">
              </div>
              <div class="recent-post-desc">
                <h6>
                  <a href="{{ route('blog_details', ['id' => $recentBlog->blog_id, 'slug' => $recentBlog->slug]) }}">
                    {{ strlen($recentBlog->title) > 30 ? convertUtf8(substr($recentBlog->title, 0, 30)) . '...' : convertUtf8($recentBlog->title) }}
                  </a>
                </h6>
                <span class="date">{{ date_format($recentBlog->blog->created_at, 'F d, Y') }}</span>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
</div>

