@include("admin.common.header")
<div class="container brilliant-block">
  <div class="row justify-content-center">
    <div class="col-8">
      <div class="alert alert-danger" role="alert">
        <p>{{ $errors}}</p>
        {{-- <p>{{ $errors->getMessage() }}</p>
        <p>{{ $errors->getLine() }}</p>
        <p>{{ $errors->getFile() }}</p> --}}
      </div>
    </div>
  </div>
</div>
@include("admin.common.footer")
