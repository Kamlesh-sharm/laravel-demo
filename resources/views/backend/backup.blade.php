@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Backup') }}</h4>
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
        <a href="#">{{ __('Backup') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Backup Lists') }}</div>
          <form class="d-inline-block float-right" action="{{route('admin.backup.store')}}" method="post">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('Create Backup') }}</button>
          </form>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="alert alert-warning" role="alert">
                {{ __('Make sure your database is backed up frequently. Click on "Create Backup" to manually backup your database. The backup are stored in the [core/storage/app/public/] folder and can be downloaded from the list below (but cannot be accessed publicly).') }}
              </div>

              @if (count($backups) == 0)
                <h3 class="text-center">{{ __('NO BACKUP FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Date & Time') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($backups as $key => $backup)
                        <tr>
                          <td>{{$backup->created_at}}</td>
                          <td>
                            <form class="d-inline-block" action="{{route('admin.backup.download', $backup->id)}}" method="post">
                              @csrf
                              <input type="hidden" name="filename" value="{{$backup->filename}}">
                              <button type="submit" class="btn btn-secondary btn-sm">
                                <span class="btn-label">
                                  <i class="fas fa-arrow-alt-circle-down"></i>
                                </span>
                                {{ __('Download') }}
                              </button>
                            </form>
                            <form class="deleteForm d-inline-block" action="{{route('admin.backup.delete', $backup->id)}}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                {{ __('Delete') }}
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
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{$backups->links()}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
