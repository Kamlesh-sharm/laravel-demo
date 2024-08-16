<div class="col-lg-8">
  @if (count($roomInfos) == 0)
    <div class="row text-center">
      <div class="col">
        <h3>{{ __('No Room Found!') }}</h3>
      </div>
    </div>
  @else
    @foreach ($roomInfos as $roomInfo)
      <!-- Single Room -->
      <div class="single-room list-style">
        <div class="row align-items-center no-gutters">
          <div class="col-lg-6">
            <div class="room-thumb">
              <img src="{{ asset('assets/img/rooms/' . $roomInfo->featured_img) }}" alt="room">
            </div>
          </div>

          <div class="col-lg-6">
            <div class="room-desc">
              <div class="room-cat">
                <p>{{ $roomInfo->name }}</p>
              </div>
              <h4>
                <a href="{{ route('room_details', ['id' => $roomInfo->room_id, 'slug' => $roomInfo->slug]) }}">{{ convertUtf8($roomInfo->title) }}</a>
              </h4>
              <ul class="room-info list-inline">
                <li><i class="far fa-bed"></i>{{ $roomInfo->bed . ' ' . __('Bed') }}</li>
                <li><i class="far fa-bath"></i>{{ $roomInfo->bath . ' ' . __('Bath') }}</li>
              </ul>
              <div class="room-price">
                <p>{{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }} {{ $roomInfo->rent }} {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  @endif
</div>
