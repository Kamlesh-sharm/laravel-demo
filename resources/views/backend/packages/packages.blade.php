@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Packages') }}</h4>
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
        <a href="#">{{ __('Packages') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-3">
              <div class="card-title d-inline-block">{{ __('Packages') }}</div>
            </div>

            <div class="col-md-6">
              <form id="searchForm" action="{{ route('admin.packages_management.packages') }}" method="GET">
                <input type="hidden" name="language" value="{{ $defaultLang->code }}">

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <select class="form-control select2" name="vendor"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="">{{ __('All') }}</option>
                        <option {{ request()->input('vendor') == 'admin' ? 'selected' : '' }} value="admin">
                          {{ __('Admin') }}
                        </option>
                        @foreach ($vendors as $item)
                          <option value="{{ $item->id }}"
                            {{ request()->input('vendor') == $item->id ? 'selected' : '' }}>
                            {{ $item->username }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <input type="text" name="title" class="form-control" placeholder="Title"
                        value="{{ request()->input('title') }}">
                    </div>
                  </div>
                </div>

              </form>
            </div>

            <div class="col-lg-3 mt-2 mt-lg-0">
              <a href="{{ route('admin.packages_management.create_package') }}"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add Package') }}</a>

              <button class="btn btn-danger btn-sm float-lg-right float-right mr-2 d-none bulk-delete"
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
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Vendor') }}</th>
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
                            <input type="checkbox" class="bulk-check" data-val="{{ $package->id }}">
                          </td>
                          <td>
                            <a target="_blank"
                              href="{{ route('package_details', ['id' => $package->id, 'slug' => $package->package_content->slug]) }}">{{ strlen($package->package_content->title) > 25 ? mb_substr($package->package_content->title, 0, 25, 'utf-8') . '...' : $package->package_content->title }}</a>
                          </td>
                          <td>
                            @if ($package->vendor_id)
                              <a
                                href="{{ route('admin.vendor_management.vendor_details', ['id' => $package->vendor_id, 'language' => $defaultLang->code]) }}">{{ $vendor = optional($package->vendor)->username }}</a>
                            @else
                              <a href="javascript:void(0)">{{ __('Admin') }}</a>
                            @endif
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
                              target="_blank">{{ __('Manage') }}</a>
                          </td>
                          <td>
                            <a class="btn btn-primary btn-sm"
                              href="{{ route('admin.packages_management.view_plans', ['package_id' => $package->id, 'language' => $defaultLang->code]) }}"
                              target="_blank">{{ __('Manage') }}</a>
                          </td>
                          <td>
                            <form id="featureForm{{ $package->id }}" class="d-inline-block"
                              action="{{ route('admin.packages_management.update_featured_package') }}" method="post">
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
                            <a class="btn btn-secondary btn-sm mr-1 mb-1"
                              href="{{ route('admin.packages_management.edit_package', $package->id) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit mr__3"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.packages_management.delete_package') }}" method="post">
                              @csrf
                              <input type="hidden" name="package_id" value="{{ $package->id }}">

                              <button type="submit" class="btn btn-danger btn-sm mb-1 deleteBtn">
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
        <div class="card-footer">
          {{ $packages->appends([
                  'vendor' => request()->input('vendor'),
              ])->links() }}
        </div>
      </div>
    </div>
  </div>

@endsection
