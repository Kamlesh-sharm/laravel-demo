@extends('backend.layout')

@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/custom_dropzone.css') }}">
@endsection

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Package') }}</h4>
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
        <a href="#">{{ __('Packages Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.packages_management.packages') }}">{{ __('Packages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Package') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add New Tour Package') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.packages_management.packages') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
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
                  <label for="" class="mb-2"><strong>{{ __('Slider Images') . ' *' }} </strong></label>
                  <form action="{{ route('admin.packages_management.imagesstore') }}" id="my-dropzone"
                    enctype="multipart/formdata" class="dropzone create">
                    @csrf
                    <div class="fallback">
                      <input name="file" type="file" multiple />
                    </div>
                  </form>
                  <div class=" mb-0" id="errpreimg">

                  </div>
                  <p class="text-warning">{{ __('Image Size : 750 x 400') }}</p>
                </div>
              </div>

              <form id="packageForm" action="{{ route('admin.packages_management.store_package') }}" method="POST">
                @csrf
                {{-- uploaded slider images start --}}
                <div id="sliders"></div>
                {{-- uploaded slider images end --}}

                {{-- featured image start --}}
                <div class="form-group">
                  <label for="">{{ __('Featured Image*') }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                  </div>
                  <br><br>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="featured_img">
                    </div>
                  </div>
                  <p class="text-warning">{{ __('Image Size : 300 x 360') }}</p>
                </div>
                {{-- featured image end --}}

                <div class="row">

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Number of Days') }} *</label>
                      <input type="number" class="form-control" name="number_of_days" value=""
                        placeholder="Number of Tour Days" min="1">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Max Persons') }}</label>
                      <input type="number" class="form-control" name="max_persons"
                        placeholder="Enter No. Of Max Persons">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Contact Email') }}</label>
                      <input type="email" class="form-control" name="email" placeholder="Enter Contact Email">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Contact Number') }}</label>
                      <input type="number" class="form-control" name="phone" placeholder="Enter Contact Number">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Pricing Type') }} * (in {{ $websiteInfo->base_currency_text }})</label>
                      <div>

                        <div class="d-sm-inline mr-3">
                          <input type="radio" id="pricing_type_fixed" class="mr-1" name="pricing_type"
                            value="fixed">
                          <label for="pricing_type_fixed">{{ __('Fixed') }}</label>
                        </div>

                        <div class="d-sm-inline">
                          <input id="pricing_type_per_person" type="radio" class="mr-1" name="pricing_type"
                            value="per-person">
                          <label for="pricing_type_per_person">{{ __('Per Person') }}</label>
                        </div>
                      </div>

                      <div class="row mt-2">
                        <div class="col-lg-12">
                          <input type="number" step="0.01" id="fixed-price" class="form-control d-none"
                            name="fixed_package_price" placeholder="Enter Package Price (Fixed)">

                          <input type="number" step="0.01" id="per-person-price" class="form-control d-none"
                            name="per_person_package_price" placeholder="Enter Package Price (Per Person)">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Plan Type*') }}</label>
                      <select name="plan_type" class="form-control">
                        <option selected disabled>{{ __('Select a Type') }}</option>
                        <option value="daywise">{{ __('Daywise') }}</option>
                        <option value="timewise">{{ __('Timewise') }}</option>
                      </select>

                      <p id="daywise-text" class="mt-2 mb-0 text-warning d-none">
                        {{ __('Plans need to be added according to per day, after adding package.') }}
                      </p>

                      <p id="timewise-text" class="mt-2 mb-0 text-warning d-none">
                        {{ __('Plans need to be added according to per time frame, after adding package.') }}
                      </p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Vendor') }}</label>
                      <select class="form-control select2" name="vendor_id">
                        <option value="">{{ __('Please Select') }}</option>
                        @foreach ($vendors as $item)
                          <option value="{{ $item->id }}"> {{ $item->username }}</option>
                        @endforeach
                      </select>
                      <p class="text-warning">
                        {{ __('if you do not select any vendor , then this package will be for Admin.') }}</p>
                    </div>
                  </div>
                </div>

                <div id="accordion" class="mt-5">
                  @foreach ($languages as $language)
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
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
                                  placeholder="Enter Title">
                              </div>
                            </div>

                            <div class="col-lg-6 {{ $basicSettings->package_category_status == 1 ? '' : 'd-none' }}">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $categories = App\Models\PackageManagement\PackageCategory::where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Category') }}</label>
                                <select name="{{ $language->code }}_category" class="form-control">
                                  <option selected disabled>{{ __('Select a Category') }}</option>

                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Package Description*') }}</label>
                                <textarea id="{{ $language->code }}_description" class="form-control summernote"
                                  name="{{ $language->code }}_description" data-height="300"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Package Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="Enter Meta Keywords" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Package Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-12">
                              @php
                                $currLang = $language;
                              @endphp
                              @foreach ($languages as $language)
                                @continue($currLang->id == $language->id)

                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" value=""
                                      onchange="cloneContent('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }} <strong
                                        class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                      {{ __('Language') }}</span>
                                  </label>
                                </div>
                              @endforeach
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
                {{ __('Save') }}
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
    var storeUrl = "{{ route('admin.packages_management.imagesstore') }}";
    var removeUrl = "{{ route('admin.packages_management.imagermv') }}";
    var loadImgs = 0;
  </script>
  <script src="{{ asset('assets/js/custom_dropzone.min.js') }}"></script>
@endsection
