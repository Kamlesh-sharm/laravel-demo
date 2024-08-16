@extends('backend.layout')
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/custom_dropzone.css') }}">
@endsection
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Room') }}</h4>
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
        <a href="#">{{ __('Rooms Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Rooms') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Room') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add New Room') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.rooms_management.rooms') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1" id="roomErrors" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <label for="" class="mb-2"><strong>{{ __('Slider Images') . ' *' }} </strong></label>
                  <form action="{{ route('admin.rooms_management.imagesstore') }}" id="my-dropzone"
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

              <form id="roomForm" action="{{ route('admin.rooms_management.store_room') }}" method="POST">
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
                  <p class="text-warning">{{ __('Image Size : 370 x 250') }}</p>
                </div>
                {{-- featured image end --}}

                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Room Status*') }}</label>
                      <select name="status" class="form-control">
                        <option selected disabled>{{ __('Select a Status') }}</option>
                        <option value="1">{{ __('Show') }}</option>
                        <option value="0">{{ __('Hide') }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Rent / Night') }} (in {{ $websiteInfo->base_currency_text }}) *</label>
                      <input type="number" step="0.01" class="form-control" name="rent"
                        placeholder="Enter Room Rent">
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Quantity*') }}</label>
                      <input type="number" class="form-control" name="quantity" placeholder="Enter No. Of Room">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Beds*') }}</label>
                      <input type="number" class="form-control" name="bed" placeholder="Enter No. Of Bed">
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Baths*') }}</label>
                      <input type="number" class="form-control" name="bath" placeholder="Enter No. Of Bath">
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Max. Guests') }}</label>
                      <input type="number" class="form-control" name="max_guests" placeholder="Enter maximum guests">
                      <p class="text-warning mb-0">{{ __('Leave blank if you want to make it unlimited.') }}</p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Latitude') }}</label>
                      <input type="text" class="form-control" name="latitude" placeholder="Enter latitude for map">
                      <p class="text-warning mb-0">{{ __('Will be used to show in google map.') }}</p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Longitude') }}</label>
                      <input type="text" class="form-control" name="longitude"
                        placeholder="Enter longitude for map">
                      <p class="text-warning mb-0">{{ __('Will be used to show in google map.') }}</p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Vendor') }}</label>
                      <select class="form-control select2" name="vendor_id">
                        <option value="">{{ __('Please Select') }}</option>
                        @foreach ($vendors as $item)
                          <option value="{{ $item->id }}"> {{ $item->username }}</option>
                        @endforeach
                      </select>
                      <p class="text-warning">
                        {{ __('if you do not select any vendor , then this room will be for Admin.') }}</p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Address') }}</label>
                      <input type="text" class="form-control" name="address" placeholder="Enter Address">
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="text" class="form-control" name="phone" placeholder="Enter Phone">
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email') }}</label>
                      <input type="email" class="form-control" name="email" placeholder="Enter Email">
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
                            <div
                              class="
                            @if ($websiteInfo->room_category_status == 0) col-lg-12
                            @else
                            col-lg-6 @endif">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Room Title*') }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="Enter Title">
                              </div>
                            </div>

                            @if ($websiteInfo->room_category_status == 1)
                              <div class="col-lg-6">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  @php
                                    $categories = App\Models\RoomManagement\RoomCategory::where('language_id', $language->id)
                                        ->where('status', 1)
                                        ->get();
                                  @endphp

                                  <label>{{ __('Category*') }}</label>
                                  <select name="{{ $language->code }}_category" class="form-control">
                                    <option selected disabled>
                                      {{ __('Select a Category') }}
                                    </option>

                                    @foreach ($categories as $category)
                                      <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                      </option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            @endif
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $amenities = App\Models\RoomManagement\RoomAmenity::where('language_id', $language->id)
                                      ->orderBy('serial_number', 'asc')
                                      ->get();
                                @endphp

                                <label>{{ __('Room Amenities*') }}</label>
                                <div>
                                  @foreach ($amenities as $amenity)
                                    <div class="d-inline mr-3">
                                      <input id="{{ $language->code }}_amenities{{ $amenity->id }}" type="checkbox"
                                        class="mr-1" name="{{ $language->code }}_amenities[]"
                                        value="{{ $amenity->id }}">
                                      <label
                                        for="{{ $language->code }}_amenities{{ $amenity->id }}">{{ $amenity->name }}</label>
                                    </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Summary*') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_summary" placeholder="Enter Summary" rows="3"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Room Description*') }}</label>
                                <textarea id="{{ $language->code }}DescriptionSummernote" class="form-control summernote"
                                  name="{{ $language->code }}_description" data-height="300"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Room Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="Enter Meta Keywords" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Room Meta Description') }}</label>
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
                                    <span class="form-check-sign">Clone for <strong
                                        class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                      Language</span>
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
              <button type="submit" form="roomForm" class="btn btn-success">
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
  <script src="{{ asset('assets/js/admin-room.js') }}"></script>
  <script>
    "use strict";
    var storeUrl = "{{ route('admin.rooms_management.imagesstore') }}";
    var removeUrl = "{{ route('admin.rooms_management.imagermv') }}";
    var loadImgs = 0;
  </script>
  <script src="{{ asset('assets/js/custom_dropzone.min.js') }}"></script>
@endsection
