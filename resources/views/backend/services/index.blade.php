@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Services Management') }}</h4>
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
        <a href="#">{{ __('Services Management') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Services') }}</div>
            </div>

            <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">
              <a
                href="{{ route('admin.services_management.create_service') }}"
                class="btn btn-primary btn-sm float-right"
              ><i class="fas fa-plus"></i> {{ __('Add Service') }}</a>

              <button
                class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('admin.services_management.bulk_delete_service') }}"
              ><i class="flaticon-interface-5"></i> {{ __('Delete') }}</button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($serviceContents) == 0)
                <h3 class="text-center">{{ __('NO SERVICE FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($serviceContents as $serviceContent)
                        <tr>
                          <td>
                            <input
                              type="checkbox"
                              class="bulk-check"
                              data-val="{{ $serviceContent->service_id }}"
                            >
                          </td>
                          <td><i class="{{ $serviceContent->service->service_icon }}"></i></td>
                          <td>
                            {{ strlen($serviceContent->title) > 30 ? convertUtf8(substr($serviceContent->title, 0, 30)) . '...' : convertUtf8($serviceContent->title) }}
                          </td>
                          <td>
                            <form
                              id="featureForm{{ $serviceContent->service_id }}"
                              class="d-inline-block"
                              action="{{ route('admin.services_management.update_featured_service') }}"
                              method="post"
                            >
                              @csrf
                              <input type="hidden" name="serviceId" value="{{ $serviceContent->service_id }}">

                              <select
                                class="form-control {{ $serviceContent->service->is_featured == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="is_featured"
                                onchange="document.getElementById('featureForm{{ $serviceContent->service_id }}').submit();"
                              >
                                <option value="1" {{ $serviceContent->service->is_featured == 1 ? 'selected' : '' }}>
                                  {{ __('Yes') }}
                                </option>
                                <option value="0" {{ $serviceContent->service->is_featured == 0 ? 'selected' : '' }}>
                                  {{ __('No') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>{{ $serviceContent->service->serial_number }}</td>
                          <td>
                            <a
                              class="btn btn-secondary btn-sm mr-1 mb-1"
                              href="{{ route('admin.services_management.edit_service', $serviceContent->service_id) }}"
                            >
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form
                              class="deleteForm d-inline-block"
                              action="{{ route('admin.services_management.delete_service') }}"
                              method="post"
                            >
                              @csrf
                              <input
                                type="hidden"
                                name="service_id"
                                value="{{ $serviceContent->service_id }}"
                              >
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
