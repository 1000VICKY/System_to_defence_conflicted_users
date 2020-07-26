@include("admin.common.header")
<div class="container brilliant-block">
  <div class="row justify-content-center">
    <div class="col-8">
      <div class="alert alert-danger" role="alert">
        <p>{{ get_class($error) }}</p>
        <p>{{ $error->getMessage() }}</p>
        <p>{{ $error->getLine() }}</p>
        <p>{{ $error->getFile() }}</p>
      </div>
    </div>
  </div>
</div>
@include("admin.common.footer")
