@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Locations') }}</h4>
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
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Locations') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Package Locations') }}</div>
            </div>
            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>
            <div class="col-lg-5 mt-2 mt-lg-0">

              <a href="#" data-toggle="modal" data-target="#addLocationModal"
                class="locationBtn btn btn-primary btn-sm float-lg-right float-left"
                data-id="{{ Request::route('package_id') }}">{{ __('Add Location') }}</a>

              <button class="btn btn-danger btn-sm mr-1 float-right d-none bulk-delete"
                data-href="{{ route('admin.packages_management.bulk_delete_location') }}"><i
                  class="flaticon-interface-5"></i> {{ __('Delete') }}</button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($locations) == 0)
                <h3 class="text-center">{{ __('NO LOCATION FOUND FOR THIS PACKAGE!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Latitude') }}</th>
                        <th scope="col">{{ __('Longitude') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($locations as $location)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $location->id }}">
                          </td>
                          <td>
                            {{ strlen($location->name) > 100 ? convertUtf8(substr($location->name, 0, 100)) . '...' : convertUtf8($location->name) }}
                          </td>
                          <td>
                            {{ $location->latitude == null ? '-' : $location->latitude }}
                          </td>
                          <td>
                            {{ $location->longitude == null ? '-' : $location->longitude }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1 mb-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editLocationModal" data-id="{{ $location->id }}"
                              data-name="{{ $location->name }}" data-latitude="{{ $location->latitude }}"
                              data-longitude="{{ $location->longitude }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.packages_management.delete_location') }}" method="post">
                              @csrf
                              <input type="hidden" name="location_id" value="{{ $location->id }}">

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

  {{-- edit modal --}}
  @include('backend.packages.edit_location')

  {{-- add package location modal --}}
  @include('backend.packages.create_location')
@endsection
