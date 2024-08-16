<footer class="footer">
  <div class="container-fluid">
    <div class="d-block mx-auto">
      {!! !is_null($footerTextInfo) ? replaceBaseUrl($footerTextInfo->copyright_text, 'summernote') : '' !!}
    </div>
  </div>
</footer>
