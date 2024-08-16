@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Images') }}</h4>
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
        <a href="#">{{ __('Gallery Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Images') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Gallery Images') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a
                href="#"
                data-toggle="modal"
                data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"
              ><i class="fas fa-plus"></i> {{ __('Add Image') }}</a>

              <button
                class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('admin.gallery_management.bulk_delete_gallery_info') }}"
              ><i class="flaticon-interface-5"></i> {{ __('Delete') }}</button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($galleryInfos) == 0)
                <h3 class="text-center">{{ __('NO GALLERY INFORMATION FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($galleryInfos as $galleryInfo)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $galleryInfo->id }}">
                          </td>
                          <td>{{ $galleryInfo->galleryCategory->name }}</td>
                          <td>
                            <img
                              src="{{ asset('assets/img/gallery/' . $galleryInfo->gallery_img) }}"
                              alt="image"
                              width="40"
                            >
                          </td>
                          <td>
                            {{strlen($galleryInfo->title) > 30 ? mb_substr($galleryInfo->title,0,30,'utf-8') . '...' : $galleryInfo->title}}
                          </td>
                          <td>{{ $galleryInfo->serial_number }}</td>
                          <td>
                            <a
                              class="btn btn-secondary btn-sm mr-1 mb-1 editBtn"
                              href="#"
                              data-toggle="modal"
                              data-target="#editModal"
                              data-id="{{ $galleryInfo->id }}"
                              data-gallery_category_id="{{ $galleryInfo->gallery_category_id }}"
                              data-gallery_img="{{ asset('assets/img/gallery/' . $galleryInfo->gallery_img) }}"
                              data-title="{{ $galleryInfo->title }}"
                              data-serial_number="{{ $galleryInfo->serial_number }}"
                            >
                                <i class="fas fa-edit"></i>
                            </a>

                            <form
                              class="deleteForm d-inline-block"
                              action="{{ route('admin.gallery_management.delete_gallery_info') }}"
                              method="post"
                            >
                              @csrf
                              <input type="hidden" name="gallery_id" value="{{ $galleryInfo->id }}">
                              <button type="submit" class="btn btn-danger mb-1 btn-sm deleteBtn">
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

      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.gallery.create')

  {{-- edit modal --}}
  @include('backend.gallery.edit')
@endsection
