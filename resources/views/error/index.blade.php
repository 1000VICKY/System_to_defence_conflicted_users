@include("admin.common.header")
<div class="container brilliant-block">
  <p>{{ $error->getMessage() }}</p>
  <p>{{ $error->getLine() }}</p>
  <p>{{ $error->getFile() }}</p>
</div>
@include("admin.common.footer")
