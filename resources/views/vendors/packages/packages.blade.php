@extends('vendors.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('vendors.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Packages') }}</h4>
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
        <a href="#">{{ __('Packages') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Packages') }}</div>
            </div>
            <div class="col-lg-4">
            </div>

            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="{{ route('vendor.packages_management.create_package') }}"
                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Package') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('vendor.packages_management.bulk_delete_package') }}"><i
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
                            @if ($package->package_content)
                              <a href="{{ route('package_details', ['id' => $package->id, 'slug' => $package->package_content->slug]) }}"
                                target="_blank">{{ strlen($package->package_content->title) > 25 ? mb_substr($package->package_content->title, 0, 25, 'utf-8') . '...' : $package->package_content->title }}</a>
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
                              href="{{ route('vendor.packages_management.view_locations', ['package_id' => $package->id, 'language' => $defaultLang->code]) }}"
                              target="_blank">{{ __('Manage') }}</a>
                          </td>
                          <td>
                            <a class="btn btn-primary btn-sm"
                              href="{{ route('vendor.packages_management.view_plans', ['package_id' => $package->id, 'language' => $defaultLang->code]) }}"
                              target="_blank">{{ __('Manage') }}</a>
                          </td>
                          <td>
                            @if ($package->is_featured == 1)
                              <span class="badge badge-success">{{ __('Yes') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('No') }}</span>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-secondary mb-1 btn-sm mr-1"
                              href="{{ route('vendor.packages_management.edit_package', $package->id) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('vendor.packages_management.delete_package') }}" method="post">
                              @csrf
                              <input type="hidden" name="package_id" value="{{ $package->id }}">

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
