<html>

<head>
  <title>{{ $websiteInfo->website_title }} - {{ __('Maintainance Mode') }}</title>
  <!-- favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}" type="image/x-icon">
  <!-- bootstrap css -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/503.css') }}">
</head>

<body>
  <div class="container">
    <div class="content">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="maintain-img-wrapper">
            <img src="{{ asset('assets/img/' . $websiteInfo->maintenance_img) }}" alt="">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <h3 class="maintain-txt">
            {!! nl2br($websiteInfo->maintenance_msg) !!}
          </h3>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
