@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('SEO Informations') }}</h4>
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
        <a href="#">{{ __('Basic Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('SEO Informations') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.basic_settings.update_seo_informations') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update SEO Informations') }}</div>
              </div>

              <div class="col-lg-2">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Home Page') }}</label>
                  <input class="form-control" name="meta_keyword_home" value="{{ $data->meta_keyword_home }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Home Page') }}</label>
                  <textarea class="form-control" name="meta_description_home" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_home }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Blogs Page') }}</label>
                  <input class="form-control" name="meta_keyword_blogs" value="{{ $data->meta_keyword_blogs }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Blogs Page') }}</label>
                  <textarea class="form-control" name="meta_description_blogs" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_blogs }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For About Us Page') }}</label>
                  <input class="form-control" name="meta_keyword_about_us" value="{{ $data->meta_keyword_about_us }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For About Us Page') }}</label>
                  <textarea class="form-control" name="meta_description_about_us" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_about_us }}</textarea>
                </div>
              </div>


              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Contact Us Page') }}</label>
                  <input class="form-control" name="meta_keyword_contact_us" value="{{ $data->meta_keyword_contact_us }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Contact Us Page') }}</label>
                  <textarea class="form-control" name="meta_description_contact_us" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_contact_us }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Gallery Page') }}</label>
                  <input class="form-control" name="meta_keyword_gallery" value="{{ $data->meta_keyword_gallery }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Gallery Page') }}</label>
                  <textarea class="form-control" name="meta_description_gallery" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_gallery }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For FAQ Page') }}</label>
                  <input class="form-control" name="meta_keyword_faq" value="{{ $data->meta_keyword_faq }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For FAQ Page') }}</label>
                  <textarea class="form-control" name="meta_description_faq" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_faq }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Rooms Page') }}</label>
                  <input class="form-control" name="meta_keyword_rooms" value="{{ $data->meta_keyword_rooms }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Rooms Page') }}</label>
                  <textarea class="form-control" name="meta_description_rooms" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_rooms }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Services Page') }}</label>
                  <input class="form-control" name="meta_keyword_services" value="{{ $data->meta_keyword_services }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Services Page') }}</label>
                  <textarea class="form-control" name="meta_description_services" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_services }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Package Page') }}</label>
                  <input class="form-control" name="meta_keyword_packages" value="{{ $data->meta_keyword_packages }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Package Page') }}</label>
                  <textarea class="form-control" name="meta_description_packages" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_packages }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Registration Page') }}</label>
                  <input class="form-control" name="meta_keyword_registration"
                    value="{{ $data->meta_keyword_registration }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Registration Page') }}</label>
                  <textarea class="form-control" name="meta_description_registration" rows="5"
                    placeholder="Enter Meta Description">{{ $data->meta_description_registration }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Login Page') }}</label>
                  <input class="form-control" name="meta_keyword_login" value="{{ $data->meta_keyword_login }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Login Page') }}</label>
                  <textarea class="form-control" name="meta_description_login" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_login }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Forget Password Page') }}</label>
                  <input class="form-control" name="meta_keyword_forget_password"
                    value="{{ $data->meta_keyword_forget_password }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Forget Password Page') }}</label>
                  <textarea class="form-control" name="meta_description_forget_password" rows="5"
                    placeholder="Enter Meta Description">{{ $data->meta_description_forget_password }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Vendor Registration Page') }}</label>
                  <input class="form-control" name="meta_keyword_vendor_registration"
                    value="{{ $data->meta_keyword_vendor_registration }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Vendor Registration Page') }}</label>
                  <textarea class="form-control" name="meta_description_vendor_registration" rows="5"
                    placeholder="Enter Meta Description">{{ $data->meta_description_vendor_registration }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Vendor Login Page') }}</label>
                  <input class="form-control" name="meta_keyword_vendor_login"
                    value="{{ $data->meta_keyword_vendor_login }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Vendor Login Page') }}</label>
                  <textarea class="form-control" name="meta_description_vendor_login" rows="5"
                    placeholder="Enter Meta Description">{{ $data->meta_description_vendor_login }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Vendor Forget Password Page') }}</label>
                  <input class="form-control" name="meta_keyword_vendor_forget_password"
                    value="{{ $data->meta_keyword_vendor_forget_password }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Vendor Forget Password Page') }}</label>
                  <textarea class="form-control" name="meta_description_vendor_forget_password" rows="5"
                    placeholder="Enter Meta Description">{{ $data->meta_description_vendor_forget_password }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Vendors Page') }}</label>
                  <input class="form-control" name="meta_keyword_vendors" value="{{ $data->meta_keyword_vendors }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Vendors Page') }}</label>
                  <textarea class="form-control" name="meta_description_vendors" rows="5" placeholder="Enter Meta Description">{{ $data->meta_description_vendors }}</textarea>
                </div>
              </div>

            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit"
                    class="btn btn-success {{ $data == null ? 'd-none' : '' }}">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
