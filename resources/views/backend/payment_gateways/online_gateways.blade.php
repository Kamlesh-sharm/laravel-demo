@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Online Gateways') }}</h4>
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
        <a href="#">{{ __('Payment Gateways') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Online Gateways') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-lg-4">
      <div class="card">
        <form
          action="{{ route('admin.payment_gateways.update_paypal_info') }}"
          method="post"
        >
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Paypal') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Paypal Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="1"
                        class="selectgroup-input"
                        {{ $paypal->status == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="0"
                        class="selectgroup-input"
                        {{ $paypal->status == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('status'))
                    <p class="mb-0 text-danger">{{ $errors->first('status') }}</p>
                  @endif
                </div>

                @php
                  $paypalInfo = json_decode($paypal->information, true);
                @endphp

                <div class="form-group">
                  <label>{{ __('Paypal Test Mode') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="sandbox_status"
                        value="1"
                        class="selectgroup-input"
                        {{ $paypalInfo['sandbox_status'] == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="sandbox_status"
                        value="0"
                        class="selectgroup-input"
                        {{ $paypalInfo['sandbox_status'] == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('sandbox_status'))
                    <p class="mb-0 text-danger">{{ $errors->first('sandbox_status') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paypal Client ID') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="client_id"
                    value="{{ $paypalInfo['client_id'] }}"
                  >
                  @if ($errors->has('client_id'))
                    <p class="mb-0 text-danger">{{ $errors->first('client_id') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paypal Client Secret') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="client_secret"
                    value="{{ $paypalInfo['client_secret'] }}"
                  >
                  @if ($errors->has('client_secret'))
                    <p class="mb-0 text-danger">{{ $errors->first('client_secret') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>


    <div class="col-lg-4">
      <div class="card">
        <form class="" action="{{route('admin.payment_gateways.update_stripe_info')}}" method="post">
          @csrf
          <div class="card-header">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-title">{{ __('Stripe') }}</div>
                  </div>
              </div>
          </div>
          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                @csrf
                @php
                    $stripeInfo = json_decode($stripe->information, true);
                @endphp
                <div class="form-group">
                    <label>{{ __('Stripe') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="1" class="selectgroup-input" {{$stripe->status == 1 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="0" class="selectgroup-input" {{$stripe->status == 0 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('Stripe Key') }}</label>
                    <input class="form-control" name="key" value="{{$stripeInfo['key']}}">
                    @if ($errors->has('key'))
                        <p class="mb-0 text-danger">{{$errors->first('key')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('Stripe Secret') }}</label>
                    <input class="form-control" name="secret" value="{{$stripeInfo['secret']}}">
                    @if ($errors->has('secret'))
                        <p class="mb-0 text-danger">{{$errors->first('secret')}}</p>
                    @endif
                </div>

              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" id="displayNotif" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <form
          action="{{ route('admin.payment_gateways.update_instamojo_info') }}"
          method="post"
        >
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Instamojo') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Instamojo Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="1"
                        class="selectgroup-input"
                        {{ $instamojo->status == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="0"
                        class="selectgroup-input"
                        {{ $instamojo->status == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('status'))
                    <p class="mb-0 text-danger">{{ $errors->first('status') }}</p>
                  @endif
                </div>

                @php
                  $instamojoInfo = json_decode($instamojo->information, true);
                @endphp

                <div class="form-group">
                  <label>{{ __('Instamojo Test Mode') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="sandbox_status"
                        value="1"
                        class="selectgroup-input"
                        {{ $instamojoInfo['sandbox_status'] == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="sandbox_status"
                        value="0"
                        class="selectgroup-input"
                        {{ $instamojoInfo['sandbox_status'] == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('sandbox_status'))
                    <p class="mb-0 text-danger">{{ $errors->first('sandbox_status') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Instamojo API Key') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="instamojo_key"
                    value="{{ $instamojoInfo['instamojo_key'] }}"
                  >
                  @if ($errors->has('instamojo_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('instamojo_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Instamojo Auth Token') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="instamojo_token"
                    value="{{ $instamojoInfo['instamojo_token'] }}"
                  >
                  @if ($errors->has('instamojo_token'))
                    <p class="mb-0 text-danger">{{ $errors->first('instamojo_token') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <form
          action="{{ route('admin.payment_gateways.update_paystack_info') }}"
          method="post"
        >
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Paystack') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Paystack Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="1"
                        class="selectgroup-input"
                        {{ $paystack->status == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="0"
                        class="selectgroup-input"
                        {{ $paystack->status == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('status'))
                    <p class="mb-0 text-danger">{{ $errors->first('status') }}</p>
                  @endif
                </div>

                @php
                  $paystackInfo = json_decode($paystack->information, true);
                @endphp

                <div class="form-group">
                  <label>{{ __('Paystack Secret Key') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="paystack_key"
                    value="{{ $paystackInfo['paystack_key'] }}"
                  >
                  @if ($errors->has('paystack_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('paystack_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paystack Business Email') }}</label>
                  <input
                    type="email"
                    class="form-control"
                    name="paystack_email"
                    value="{{ $paystackInfo['paystack_email']}}"
                  >
                  @if ($errors->has('paystack_email'))
                    <p class="mb-0 text-danger">{{ $errors->first('paystack_email') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <form
          action="{{ route('admin.payment_gateways.update_flutterwave_info') }}"
          method="post"
        >
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Flutterwave') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Flutterwave Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="1"
                        class="selectgroup-input"
                        {{ $flutterwave->status == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="0"
                        class="selectgroup-input"
                        {{ $flutterwave->status == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('status'))
                    <p class="mb-0 text-danger">{{ $errors->first('status') }}</p>
                  @endif
                </div>

                @php
                  $flutterwaveInfo = json_decode($flutterwave->information, true);
                @endphp

                <div class="form-group">
                  <label>{{ __('Flutterwave Public Key') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="flutterwave_public_key"
                    value="{{ $flutterwaveInfo['flutterwave_public_key'] }}"
                  >
                  @if ($errors->has('flutterwave_public_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('flutterwave_public_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Flutterwave Secret Key') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="flutterwave_secr
                    t_key"
                    value="{{ $flutterwaveInfo['flutterwave_secret_key'] }}"
                  >
                  @if ($errors->has('flutterwave_secret_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('flutterwave_secret_key') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <form
          action="{{ route('admin.payment_gateways.update_razorpay_info') }}"
          method="post"
        >
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Razorpay') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Razorpay Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="1"
                        class="selectgroup-input"
                        {{ $razorpay->status == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="0"
                        class="selectgroup-input"
                        {{ $razorpay->status == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('status'))
                    <p class="mb-0 text-danger">{{ $errors->first('status') }}</p>
                  @endif
                </div>

                @php
                  $razorpayInfo = json_decode($razorpay->information, true);
                @endphp

                <div class="form-group">
                  <label>{{ __('Razorpay Key') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="razorpay_key"
                    value="{{ $razorpayInfo['razorpay_key'] }}"
                  >
                  @if ($errors->has('razorpay_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('razorpay_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Razorpay Secret') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="razorpay_secret"
                    value="{{ $razorpayInfo['razorpay_secret'] }}"
                  >
                  @if ($errors->has('razorpay_secret'))
                    <p class="mb-0 text-danger">{{ $errors->first('razorpay_secret') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <form
          action="{{ route('admin.payment_gateways.update_mercadopago_info') }}"
          method="post"
        >
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('MercadoPago') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="form-group">
              <label>{{ __('MercadoPago Status') }}</label>
              <div class="selectgroup w-100">
                <label class="selectgroup-item">
                  <input
                    type="radio"
                    name="status"
                    value="1"
                    class="selectgroup-input"
                    {{ $mercadopago->status == 1 ? 'checked' : '' }}
                  >
                  <span class="selectgroup-button">{{ __('Active') }}</span>
                </label>
                <label class="selectgroup-item">
                  <input
                    type="radio"
                    name="status"
                    value="0"
                    class="selectgroup-input"
                    {{ $mercadopago->status == 0 ? 'checked' : '' }}
                  >
                  <span class="selectgroup-button">{{ __('Deactive') }}</span>
                </label>
              </div>
              @if ($errors->has('status'))
                <p class="mb-0 text-danger">{{ $errors->first('status') }}</p>
              @endif
            </div>

            @php
              $mercadopagoInfo = json_decode($mercadopago->information, true);
            @endphp

            <div class="form-group">
              <label>{{ __('MercadoPago Test Mode') }}</label>
              <div class="selectgroup w-100">
                <label class="selectgroup-item">
                  <input
                    type="radio"
                    name="sandbox_status"
                    value="1"
                    class="selectgroup-input"
                    {{ $mercadopagoInfo["sandbox_status"] == 1 ? 'checked' : '' }}
                  >
                  <span class="selectgroup-button">{{ __('Active') }}</span>
                </label>
                <label class="selectgroup-item">
                  <input
                    type="radio"
                    name="sandbox_status"
                    value="0"
                    class="selectgroup-input"
                    {{ $mercadopagoInfo["sandbox_status"] == 0 ? 'checked' : '' }}
                  >
                  <span class="selectgroup-button">{{ __('Deactive') }}</span>
                </label>
              </div>
              @if ($errors->has('sandbox_status'))
                <p class="mb-0 text-danger">{{ $errors->first('sandbox_status') }}</p>
              @endif
            </div>

            <div class="form-group">
              <label>{{ __('MercadoPago Token') }}</label>
              <input
                type="text"
                class="form-control"
                name="mercadopago_token"
                value="{{ $mercadopagoInfo['mercadopago_token'] }}"
              >
              @if ($errors->has('mercadopago_token'))
                <p class="mb-0 text-danger">{{ $errors->first('mercadopago_token') }}</p>
              @endif
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <form
          action="{{ route('admin.payment_gateways.update_mollie_info') }}"
          method="post"
        >
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Mollie') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Mollie Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="1"
                        class="selectgroup-input"
                        {{ $mollie->status == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="0"
                        class="selectgroup-input"
                        {{ $mollie->status == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('status'))
                    <p class="mb-0 text-danger">{{ $errors->first('status') }}</p>
                  @endif
                </div>

                @php
                  $mollieInfo = json_decode($mollie->information, true);
                @endphp

                <div class="form-group">
                  <label>{{ __('Mollie API Key') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="mollie_key"
                    value="{{ $mollieInfo['mollie_key'] }}"
                  >
                  @if ($errors->has('mollie_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('mollie_key') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <form
          action="{{ route('admin.payment_gateways.update_paytm_info') }}"
          method="post"
        >
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Paytm') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Paytm Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="1"
                        class="selectgroup-input"
                        {{ $paytm->status == 1 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="status"
                        value="0"
                        class="selectgroup-input"
                        {{ $paytm->status == 0 ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('status'))
                    <p class="mb-0 text-danger">{{ $errors->first('status') }}</p>
                  @endif
                </div>

                @php
                  $paytmInfo = json_decode($paytm->information, true);
                @endphp

                <div class="form-group">
                  <label>{{ __('Paytm Environment') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="environment"
                        value="local"
                        class="selectgroup-input"
                        {{ $paytmInfo['environment'] == 'local' ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Local') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="environment"
                        value="production"
                        class="selectgroup-input"
                        {{ $paytmInfo['environment'] == 'production' ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Production') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('environment'))
                    <p class="mb-0 text-danger">{{ $errors->first('environment') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paytm Merchant Key') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="merchant_key"
                    value="{{ $paytmInfo['merchant_key'] }}"
                  >
                  @if ($errors->has('merchant_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('merchant_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paytm Merchant MID') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="merchant_mid"
                    value="{{ $paytmInfo['merchant_mid'] }}"
                  >
                  @if ($errors->has('merchant_mid'))
                    <p class="mb-0 text-danger">{{ $errors->first('merchant_mid') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paytm Merchant Website') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="merchant_website"
                    value="{{ $paytmInfo['merchant_website'] }}"
                  >
                  @if ($errors->has('merchant_website'))
                    <p class="mb-0 text-danger">{{ $errors->first('merchant_website') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Industry Type Id') }}</label>
                  <input
                  type="text"
                  class="form-control"
                  name="industry"
                  value="{{ $paytmInfo['industry'] }}"
                >
                  @if ($errors->has('industry'))
                    <p class="mb-0 text-danger">{{ $errors->first('industry') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
