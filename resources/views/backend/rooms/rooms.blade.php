@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Rooms') }}</h4>
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
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-3">
              <div class="card-title d-inline-block">{{ __('Rooms') }}</div>
            </div>
            <div class="col-lg-6">
              <form action="" method="get" id="Form">
                <div class="row">
                  <div class="col-lg-6">
                    <select name="vendor_id" class="form-control select2"
                      onchange="document.getElementById('Form').submit()">
                      <option value="" selected>{{ __('All') }}</option>
                      <option value="admin" @selected(request()->input('vendor_id') == 'admin')>{{ __('Admin') }}</option>
                      @foreach ($venodors as $vendor)
                        <option @selected(request()->input('vendor_id') == $vendor->id) value="{{ $vendor->id }}">{{ $vendor->username }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-6">
                    <input type="text" name="title" placeholder="Title" value="{{ request()->input('title') }}"
                      class="form-control">
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-3 mt-2 mt-lg-0">
              <a href="{{ route('admin.rooms_management.create_room') }}"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add Room') }}</a>

              <button class="btn btn-danger btn-sm float-right  mr-2 d-none bulk-delete"
                data-href="{{ route('admin.rooms_management.bulk_delete_room') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($rooms) == 0)
                <h3 class="text-center">{{ __('NO ROOM FOUND!') }}</h3>
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
                        @if ($websiteInfo->room_category_status == 1)
                          <th scope="col">{{ __('Category') }}</th>
                        @endif
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Rent') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($rooms as $room)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $room->id }}">
                          </td>
                          <td>
                            @if ($room->room_content)
                              <a target="blank"
                                href="{{ route('room_details', ['id' => $room->id, 'slug' => $room->room_content->slug]) }}">{{ strlen($room->room_content->title) > 30 ? mb_substr($room->room_content->title, 0, 30, 'utf-8') . '...' : $room->room_content->title }}</a>
                            @endif
                          </td>
                          <td>
                            @if ($room->vendor_id)
                              <a
                                href="{{ route('admin.vendor_management.vendor_details', ['id' => $room->vendor_id, 'language' => $defaultLang->code]) }}">{{ $vendor = optional($room->vendor)->username }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          @if ($websiteInfo->room_category_status == 1)
                            <td>{{ @$room->room_content->roomCategory->name }}</td>
                          @endif
                          <td>
                            <form id="featureForm{{ $room->id }}" class="d-inline-block"
                              action="{{ route('admin.rooms_management.update_featured_room') }}" method="post">
                              @csrf
                              <input type="hidden" name="roomId" value="{{ $room->id }}">

                              <select
                                class="form-control {{ $room->is_featured == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
                                name="is_featured"
                                onchange="document.getElementById('featureForm{{ $room->id }}').submit();">
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
                            {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                            {{ $room->rent }}
                            {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1 mb-1"
                              href="{{ route('admin.rooms_management.edit_room', $room->id) }}">
                              <i class="fas fa-edit"></i>
                            </a>

                            <form class="deleteForm d-inline-block mb-1"
                              action="{{ route('admin.rooms_management.delete_room') }}" method="post">
                              @csrf
                              <input type="hidden" name="room_id" value="{{ $room->id }}">

                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <i class="fas fa-trash"></i>
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
          {{ $rooms->appends([
                  'vendor_id' => request()->input('vendor_id'),
              ])->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
