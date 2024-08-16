@extends('vendors.layout')
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/custom_dropzone.css') }}">
@endsection
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Package') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Packages Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('vendor.packages_management.packages') }}">{{ __('Packages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Package') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Update Tour Package') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.packages_management.packages') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
          @if ($package->package_content)
            <a target="_blank" class="btn btn-success btn-sm float-right d-inline-block mr-2"
              href="{{ route('package_details', ['id' => $package->id, 'slug' => $package->package_content->slug]) }}">
              <span class="btn-label">
                <i class="fas fa-eye"></i>
              </span>
              {{ __('Preview') }}
            </a>
          @endif
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">

            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1" id="packageErrors" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <label for="" class="mb-2"><strong>{{ __('Gallery Images') }} **</strong></label>
                  <div id="reload-slider-div">
                    <div class="row mt-2">
                      <div class="col">
                        <table class="table" id="img-table">

                        </table>
                      </div>
                    </div>
                  </div>
                  <form action="{{ route('admin.packages_management.imagesstore') }}" id="my-dropzone"
                    enctype="multipart/formdata" class="dropzone create">
                    @csrf
                    <div class="fallback">
                      <input name="file" type="file" multiple />
                    </div>
                    <input type="hidden" value="{{ $package->id }}" name="package_id">
                  </form>
                  <div class=" mb-0" id="errpreimg">

                  </div>
                  <p class="text-warning">{{ __('Image Size : 750 x 400') }}</p>
                </div>
              </div>
              <form id="packageForm"
                action="{{ route('vendor.packages_management.update_package', ['id' => $package->id]) }}" method="POST">
                @csrf
                {{-- featured image start --}}
                <div class="form-group">
                  <label for="">{{ __('Featured Image*') }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/package/' . $package->featured_img) }}" alt="..."
                      class="uploaded-img">
                  </div>
                  <br><br>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="featured_img">
                    </div>
                  </div>
                  <p class="text-warning">{{ __('Image Size : 125 x 150') }}</p>
                </div>
                {{-- featured image end --}}
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Number of Days') }} *</label>
                      <input type="number" class="form-control" name="number_of_days"
                        placeholder="{{ __('Number of Tour Days') }}" value="{{ $package->number_of_days }}"
                        min="1">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Max Persons') }}</label>
                      <input type="number" class="form-control" name="max_persons" placeholder="Enter No. Of Max Persons"
                        value="{{ $package->max_persons }}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Contact Email') }}</label>
                      <input type="email" class="form-control" name="email" placeholder="Enter Contact Email"
                        value="{{ $package->email }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Contact Number') }}</label>
                      <input type="number" class="form-control" name="phone" placeholder="Enter Contact Number"
                        value="{{ $package->phone }}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Pricing Type') }} * (in {{ $websiteInfo->base_currency_text }})</label>
                      <div>
                        <div class="d-sm-inline mr-3">
                          <input type="radio" class="mr-1" name="pricing_type" value="fixed"
                            {{ $package->pricing_type == 'fixed' ? 'checked' : '' }}>
                          <label for="">{{ __('Fixed') }}</label>
                        </div>
                        <div class="d-sm-inline">
                          <input type="radio" class="mr-1" name="pricing_type" value="per-person"
                            {{ $package->pricing_type == 'per-person' ? 'checked' : '' }}>
                          <label for="">{{ __('Per Person') }}</label>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-lg-12">
                          <input type="number" step="0.01" id="fixed-price"
                            class="form-control {{ $package->pricing_type == 'fixed' ? '' : 'd-none' }} "
                            name="fixed_package_price" placeholder="Enter Package Price (Fixed)"
                            value="{{ $package->pricing_type == 'fixed' ? $package->package_price : '' }}">

                          <input type="number" step="0.01" id="per-person-price"
                            class="form-control {{ $package->pricing_type == 'per-person' ? '' : 'd-none' }}"
                            name="per_person_package_price" placeholder="Enter Package Price (Per Person)"
                            value="{{ $package->pricing_type == 'per-person' ? $package->package_price : '' }}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Plan Type*') }}</label>
                      <select name="plan_type" class="form-control">
                        <option disabled>{{ __('Select a Type') }}</option>
                        <option value="daywise" {{ $package->plan_type == 'daywise' ? 'selected' : '' }}>
                          {{ __('Daywise') }}
                        </option>
                        <option value="timewise" {{ $package->plan_type == 'timewise' ? 'selected' : '' }}>
                          {{ __('Timewise') }}
                        </option>
                      </select>
                      <p id="daywise-text" class="mt-2 mb-0 text-warning d-none">
                        {{ __('Plan need to be set according to per day.') }}
                      </p>
                      <p id="timewise-text" class="mt-2 mb-0 text-warning d-none">
                        {{ __('Plan need to be set according to per time frame.') }}
                      </p>
                    </div>
                  </div>
                </div>
                <div id="accordion" class="mt-5">
                  @foreach ($languages as $language)
                    @php
                      $packageContent = $language
                          ->packageDetails()
                          ->where('package_id', $package->id)
                          ->first();
                      $title = !empty($packageContent) ? $packageContent->title : '';
                      $categoryId = !empty($packageContent) ? $packageContent->package_category_id : '';
                      $summary = !empty($packageContent) ? $packageContent->summary : '';
                      $description = !empty($packageContent) ? $packageContent->description : '';
                      $meta_keywords = !empty($packageContent) ? $packageContent->meta_keywords : '';
                      $meta_description = !empty($packageContent) ? $packageContent->meta_description : '';
                    @endphp
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }}
                            {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>
                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="{{ $basicSettings->package_category_status == 1 ? 'col-lg-6' : 'col-lg-12' }}">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Package Title*') }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="Enter Title" value="{{ $title }}">
                              </div>
                            </div>
                            <div class="col-lg-6 {{ $basicSettings->package_category_status == 1 ? '' : 'd-none' }}">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $categories = App\Models\PackageManagement\PackageCategory::where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->get();
                                @endphp
                                <label>{{ __('Category') }} *</label>
                                <select name="{{ $language->code }}_category" class="form-control">
                                  <option disabled selected>{{ __('Select a Category') }}</option>
                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                      {{ $categoryId == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                    </option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Package Description*') }}</label>
                                <textarea class="form-control summernote" id="{{ $language->code }}_description"
                                  name="{{ $language->code }}_description" data-height="300">{{ replaceBaseUrl($description, 'summernote') }}</textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Package Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="Enter Meta Keywords" data-role="tagsinput"
                                  value="{{ $meta_keywords }}">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Package Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description">{{ $meta_description }}</textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="packageForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
  <script src="{{ asset('assets/js/admin-package.js') }}"></script>
  <script>
    "use strict";
    var storeUrl = "{{ route('vendor.packages_management.imagesstore') }}";
    var removeUrl = "{{ route('vendor.packages_management.imagermv') }}";
    var packageLoadImgs = 0;

    var rmvdbUrl = "{{ route('vendor.packages_management.imgdbrmv') }}";
    var packageLoadImgs = "{{ route('vendor.packages_management.images', $package->id) }}";
  </script>
  <script src="{{ asset('assets/js/custom_dropzone.min.js') }}"></script>
@endsection
