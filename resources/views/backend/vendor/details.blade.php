@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Vendor Details') }}</h4>
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
        <a href="#">{{ __('Vendor Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Vendor') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Vendor Details') }}</a>
      </li>
    </ul>
    <a href="{{ route('admin.vendor_management.registered_vendor') }}"
      class="btn-md btn btn-primary ml-auto">{{ __('Back') }}</a>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="row">

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <div class="h4 card-title">{{ __('Vendor Information') }}</div>
              <h2 class="text-center">
                @if ($vendor->photo != null)
                  <img class="vendor-detail-img" src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}"
                    alt="..." class="uploaded-img">
                @else
                  <img class="vendor-detail-img" src="{{ asset('assets/img/blank_user.jpg') }}" alt="..."
                    class="uploaded-img">
                @endif

              </h2>
            </div>

            <div class="card-body">
              <div class="payment-information">
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Name') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$vendorInfo->name }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Username') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $vendor->username }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Email') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $vendor->email }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Phone') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $vendor->phone }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Country') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$vendorInfo->country }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('City') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$vendorInfo->city }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('State') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$vendorInfo->state }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Zip Code') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$vendorInfo->zip_code }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Address') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$vendorInfo->address }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Details') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$vendorInfo->details }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Balance') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                    {{ $vendor->amount }}
                    {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="card-title d-inline-block">{{ __('All Rooms') }}</div>
                    </div>

                    <div class="col-lg-3">
                      @includeIf('backend.partials.languages')
                    </div>

                    <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">

                      <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                        data-href="{{ route('admin.rooms_management.bulk_delete_room') }}">
                        <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                      </button>
                    </div>
                  </div>
                </div>

                <div class="card-body">
                  <div class="col-lg-12">
                    @if (count($allRooms) == 0)
                      <h3 class="text-center mt-2">{{ __('NO ROOM FOUND') . '!' }}</h3>
                    @else
                      <div class="table-responsive">
                        <table class="table table-striped mt-3" id="basic-datatables">
                          <thead>
                            <tr>
                              <th scope="col">
                                <input type="checkbox" class="bulk-check" data-val="all">
                              </th>
                              <th scope="col">{{ __('Title') }}</th>
                              <th scope="col">{{ __('Category') }}</th>
                              <th scope="col">{{ __('Quantity') }}</th>
                              <th scope="col">{{ __('Featured') }}</th>
                              <th scope="col">{{ __('Actions') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($allRooms as $room)
                              <tr>
                                <td>
                                  <input type="checkbox" class="bulk-check" data-val="{{ $room->id }}">
                                </td>
                                <td>
                                  {{ strlen($room->title) > 20 ? mb_substr($room->title, 0, 20, 'UTF-8') . '...' : $room->title }}
                                </td>
                                <td>{{ $room->categoryName }}</td>
                                <td>{{ $room->quantity }}</td>
                                <td>
                                  <form id="featuredForm-{{ $room->id }}" class="d-inline-block"
                                    action="{{ route('admin.rooms_management.update_featured_room', ['roomId' => $room->id]) }}"
                                    method="post">
                                    @csrf
                                    <select
                                      class="form-control form-control-sm {{ $room->is_featured == 1 ? 'bg-success' : 'bg-danger' }}"
                                      name="is_featured"
                                      onchange="document.getElementById('featuredForm-{{ $room->id }}').submit()">
                                      <option value="1" {{ $room->is_featured == 1 ? 'selected' : '' }}>
                                        {{ __('Yes') }}
                                      </option>
                                      <option value="0" {{ $room->is_featured == 0 ? 'selected' : '' }}>
                                        {{ __('No') }}
                                      </option>
                                    </select>
                                  </form>
                                </td>
                                <td>
                                  <a class="btn btn-secondary btn-sm mr-1"
                                    href="{{ route('admin.rooms_management.edit_room', ['id' => $room->id]) }}">
                                    <span class="btn-label">
                                      <i class="fas fa-edit"></i>
                                    </span>
                                  </a>

                                  <form class="deleteForm d-inline-block"
                                    action="{{ route('admin.rooms_management.delete_room', ['id' => $room->id]) }}"
                                    method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm deleteBtn">
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
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="card-title d-inline-block">{{ __('All Packages') }}</div>
                    </div>

                    <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">

                      <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                        data-href="{{ route('admin.packages_management.bulk_delete_package') }}"><i
                          class="flaticon-interface-5"></i> {{ __('Delete') }}</button>
                    </div>
                  </div>
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      @if (count($packages) == 0)
                        <h3 class="text-center">{{ __('NO TOUR PACKAGE FOUND!') }}</h3>
                      @else
                        <div class="table-responsive">
                          <table class="table table-striped mt-3" id="basic-datatables">
                            <thead>
                              <tr>
                                <th scope="col">
                                  <input type="checkbox" class="bulk-check" data-val="all">
                                </th>
                                <th scope="col">{{ __('Title') }}</th>
                                <th scope="col">{{ __('Price') }}</th>
                                <th scope="col">{{ __('Locations') }}</th>
                                <th scope="col">{{ __('Plans') }}</th>
                                <th scope="col">{{ __('Featured') }}</th>
                                <th scope="col">{{ __('Actions') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($packages as $package)
                                <tr>
                                  <td>
                                    <input type="checkbox" class="bulk-check" data-val="{{ $package->package_id }}">
                                  </td>
                                  <td>
                                    {{ strlen($package->package_content->title) > 25 ? mb_substr($package->package_content->title, 0, 25, 'utf-8') . '...' : $package->package_content->title }}
                                  </td>
                                  <td>
                                    @if ($package->pricing_type == 'negotiable')
                                      {{ __('Negotiable') }}
                                    @else
                                      {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                      {{ $package->package_price }}
                                      {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                      <span class="text-capitalize">{{ '(' . $package->pricing_type . ')' }}</span>
                                    @endif
                                  </td>
                                  <td>
                                    <a class="btn btn-primary btn-sm"
                                      href="{{ route('admin.packages_management.view_locations', ['package_id' => $package->id, 'language' => $defaultLang->code]) }}"
                                      target="_blank">Manage</a>
                                  </td>
                                  <td>
                                    <a class="btn btn-primary btn-sm"
                                      href="{{ route('admin.packages_management.view_plans', ['package_id' => $package->id, 'language' => $defaultLang->code]) }}"
                                      target="_blank">Manage</a>
                                  </td>
                                  <td>
                                    <form id="featureForm{{ $package->id }}" class="d-inline-block"
                                      action="{{ route('admin.packages_management.update_featured_package') }}"
                                      method="post">
                                      @csrf
                                      <input type="hidden" name="packageId" value="{{ $package->id }}">

                                      <select
                                        class="form-control {{ $package->is_featured == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
                                        name="is_featured"
                                        onchange="document.getElementById('featureForm{{ $package->id }}').submit();">
                                        <option value="1" {{ $package->is_featured == 1 ? 'selected' : '' }}>
                                          {{ __('Yes') }}
                                        </option>
                                        <option value="0" {{ $package->is_featured == 0 ? 'selected' : '' }}>
                                          {{ __('No') }}
                                        </option>
                                      </select>
                                    </form>
                                  </td>
                                  <td>
                                    <a class="btn btn-secondary btn-sm mr-1"
                                      href="{{ route('admin.packages_management.edit_package', $package->id) }}">
                                      <span class="btn-label">
                                        <i class="fas fa-edit mr__3"></i>
                                      </span>
                                    </a>

                                    <form class="deleteForm d-inline-block"
                                      action="{{ route('admin.packages_management.delete_package') }}" method="post">
                                      @csrf
                                      <input type="hidden" name="package_id" value="{{ $package->id }}">

                                      <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                        <span class="btn-label">
                                          <i class="fas fa-trash mr__3"></i>
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
        </div>
      </div>
    </div>
  @endsection
