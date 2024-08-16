@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Social Links') }}</h4>
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
        <a href="#">{{ __('Social Links') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="socialForm" action="{{ route('admin.basic_settings.store_social_link') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title">{{ __('Add Social Link') }}</div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="form-group">
                  <label for="">{{ __('Social Icon*') }}</label>
                  <div class="btn-group d-block">
                    <button type="button" class="btn btn-primary iconpicker-component"><i
                        class="fa fa-fw fa-heart"></i></button>
                    <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car"
                      data-toggle="dropdown"></button>
                    <div class="dropdown-menu"></div>
                  </div>
                  <input type="hidden" id="inputIcon" name="icon">
                  @if ($errors->has('icon'))
                    <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
                  @endif
                  <div class="text-warning mt-2">
                    <small>{{ __('Click on the dropdown icon to select a social link icon.') }}</small>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">{{ __('URL*') }}</label>
                  <input type="url" class="form-control" name="url" value="{{ old('url') }}"
                    placeholder="Enter URL of Social Media Account">
                  @if ($errors->has('url'))
                    <p class="mb-0 text-danger">{{ $errors->first('url') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('Serial Number*') }}</label>
                  <input type="number" class="form-control ltr" name="serial_number" value="{{ old('serial_number') }}"
                    placeholder="Enter Serial Number">
                  @if ($errors->has('serial_number'))
                    <p class="mb-0 text-danger">{{ $errors->first('serial_number') }}</p>
                  @endif
                  <p class="text-warning mt-2">
                    <small>{{ __('The higher the serial number is, the later the social link will be shown.') }}</small>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer pt-3">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Submit') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">
            {{ __('Social Links') }}
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($socialLinks) == 0)
                <h2 class="text-center">{{ __('NO SOCIAL LINK FOUND!') }}</h2>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('#') }}</th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('URL') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($socialLinks as $socialLink)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td><i class="{{ $socialLink->icon }}"></i></td>
                          <td>{{ $socialLink->url }}</td>
                          <td>{{ $socialLink->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1 mb-1"
                              href="{{ route('admin.basic_settings.edit_social_link', $socialLink->id) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="d-inline-block deleteForm"
                              action="{{ route('admin.basic_settings.delete_social_link') }}" method="post">
                              @csrf
                              <input type="hidden" name="id" value="{{ $socialLink->id }}">
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
