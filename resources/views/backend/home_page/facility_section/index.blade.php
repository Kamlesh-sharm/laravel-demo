@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Facility Section') }}</h4>
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
        <a href="#">{{ __('Facility Section') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Facility Section') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-4">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <form id="facilitySecForm"
                action="{{ route('admin.home_page.update_facility_section', ['language' => request()->input('language')]) }}"
                method="POST">
                @csrf
                <div class="form-group">
                  <div class="thumb-preview" id="thumbPreview1">
                    @if (!empty($data->facility_section_image))
                      <img src="{{ asset('assets/img/facility_section/' . $data->facility_section_image) }}"
                        alt="image">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="...">
                    @endif
                  </div>
                  <br><br>

                  <input type="hidden" id="fileInput1" name="facility_section_image">
                  <button id="chooseImage1" class="choose-image btn btn-primary" type="button" data-multiple="false"
                    data-toggle="modal" data-target="#lfmModal1">{{ __('Choose Image') }}</button>
                  @if ($errors->has('facility_section_image'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('facility_section_image') }}</p>
                  @endif
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Facility Section Title*') }}</label>
                      <input type="text" class="form-control" name="facility_section_title"
                        value="{{ $data != null ? $data->facility_section_title : '' }}">
                      @if ($errors->has('facility_section_title'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('facility_section_title') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Facility Section Subtitle*') }}</label>
                      <input type="text" class="form-control" name="facility_section_subtitle"
                        value="{{ $data != null ? $data->facility_section_subtitle : '' }}">
                      @if ($errors->has('facility_section_subtitle'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('facility_section_subtitle') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="facilitySecForm" class="btn btn-success">
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
              <div class="card-title d-inline-block">{{ __('Facilities') }}</div>
            </div>
            <div class="col-lg-6">
              <a href="{{ route('admin.home_page.facility_section.create_facility') . '?language=' . request()->input('language') }}"
                class="btn btn-sm btn-primary float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($facilityInfos) == 0)
                <h3 class="text-center">{{ __('NO FACILITY FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('#') }}</th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($facilityInfos as $facilityInfo)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td><i class="{{ $facilityInfo->facility_icon }}"></i></td>
                          <td>{{ convertUtf8($facilityInfo->facility_title) }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm  mb-1 mr-1"
                              href="{{ route('admin.home_page.facility_section.edit_facility', ['id' => $facilityInfo->id]) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.home_page.facility_section.delete_facility') }}" method="post">
                              @csrf
                              <input type="hidden" name="facilityInfo_id" value="{{ $facilityInfo->id }}">
                              <button type="submit" class="btn btn-danger mb-1 btn-sm deleteBtn">
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
