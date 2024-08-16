@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Testimonial Section') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Testimonial Section') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Testimonial Section') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="testimonialSecForm"
                action="{{ route('admin.home_page.update_testimonial_section', ['language' => request()->input('language')]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($websiteInfo->theme_version == 'theme_two')
                  <div class="form-group">
                    <div class="thumb-preview">
                      @if (!empty($data->testimonial_section_image))
                        <img src="{{ asset('assets/img/testimonial_section/' . $data->testimonial_section_image) }}"
                          alt="image" class="uploaded-img">
                      @else
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                      @endif
                    </div>
                    <br><br>

                    <div class="mt-3">
                      <div role="button" class="btn btn-primary btn-sm upload-btn">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="testimonial_section_image">
                      </div>
                    </div>
                    @if ($errors->has('testimonial_section_image'))
                      <p class="mt-2 mb-0 text-danger">{{ $errors->first('testimonial_section_image') }}</p>
                    @endif

                  </div>
                @endif

                <div class="form-group">
                  <label for="">{{ __('Testimonial Section Title*') }}</label>
                  <input type="text" class="form-control" name="testimonial_section_title"
                    value="{{ $data != null ? $data->testimonial_section_title : '' }}">
                  @if ($errors->has('testimonial_section_title'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('testimonial_section_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('Testimonial Section Subtitle*') }}</label>
                  <input type="text" class="form-control" name="testimonial_section_subtitle"
                    value="{{ $data != null ? $data->testimonial_section_subtitle : '' }}">
                  @if ($errors->has('testimonial_section_subtitle'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('testimonial_section_subtitle') }}</p>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="testimonialSecForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-6">
              <div class="card-title d-inline-block">{{ __('Testimonials') }}</div>
            </div>
            <div class="col-lg-6">
              <a href="{{ route('admin.home_page.testimonial_section.create_testimonial') . '?language=' . request()->input('language') }}"
                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Testimonial') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($testimonialInfos) == 0)
                <h3 class="text-center">{{ __('NO TESTIMONIAL FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('#') }}</th>
                        @if ($websiteInfo->theme_version == 'theme_two')
                          <th scope="col">{{ __('Image') }}</th>
                        @endif
                        <th scope="col">{{ __('Name') }}</th>
                        @if ($websiteInfo->theme_version == 'theme_two')
                          <th scope="col">{{ __('Designation') }}</th>
                        @endif
                        <th scope="col">{{ __('Comment') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($testimonialInfos as $testimonialInfo)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          @if ($websiteInfo->theme_version == 'theme_two')
                            <td>
                              @if (!is_null($testimonialInfo->client_image))
                                <img
                                  src="{{ asset('assets/img/testimonial_section/' . $testimonialInfo->client_image) }}"
                                  alt="user" width="40">
                              @else
                                -
                              @endif
                            </td>
                          @endif
                          <td>
                            {{ $testimonialInfo->client_name }}
                          </td>
                          @if ($websiteInfo->theme_version == 'theme_two')
                            <td>
                              {{ !is_null($testimonialInfo->client_designation) ? $testimonialInfo->client_designation : '-' }}
                            </td>
                          @endif
                          <td>
                            @if ($websiteInfo->theme_version == 'theme_one')
                              {{ strlen($testimonialInfo->comment) > 50 ? convertUtf8(substr($testimonialInfo->comment, 0, 50)) . '...' : convertUtf8($testimonialInfo->comment) }}
                            @else
                              {{ strlen($testimonialInfo->comment) > 20 ? convertUtf8(substr($testimonialInfo->comment, 0, 20)) . '...' : convertUtf8($testimonialInfo->comment) }}
                            @endif
                          </td>
                          <td>{{ $testimonialInfo->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm mb-1 mr-1"
                              href="{{ route('admin.home_page.testimonial_section.edit_testimonial', $testimonialInfo->id) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.home_page.testimonial_section.delete_testimonial') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="testimonial_id" value="{{ $testimonialInfo->id }}">

                              <button type="submit" class="btn btn-danger btn-sm mb-1 deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
