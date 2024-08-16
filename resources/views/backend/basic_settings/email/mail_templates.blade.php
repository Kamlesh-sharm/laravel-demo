@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Email Settings') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
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
        <a href="#">{{ __('Email Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Mail Templates') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-6">
              <div class="card-title">
                {{ __('Mail Templates') }}
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($templates) == 0)
                <h3 class="text-center">{{ __('NO MAIL TEMPLATE FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Mail Type') }}</th>
                        <th scope="col">{{ __('Mail Subject') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($templates as $template)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td class="text-capitalize">
                              {{ $template->mail_type }}
                              @if ($template->mail_type == 'payment received')
                                <p class="mb-0">
                                    <small class="text-secondary">({{ __('After Admin accepts Payments via Offline Gateways') }})</small>
                                </p>
                              @endif
                              @if ($template->mail_type == 'payment cancelled')
                                <p class="mb-0">
                                    <small class="text-secondary">({{ __('After Admin rejects Payments via Offline Gateways') }})</small>
                                </p>
                              @endif
                          </td>
                          <td>{{ $template->mail_subject }}</td>
                          <td>
                            <a
                              class="btn btn-secondary btn-sm"
                              href="{{ route('admin.basic_settings.edit_mail_template', ['id' => $template->id]) }}"
                            >
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
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
