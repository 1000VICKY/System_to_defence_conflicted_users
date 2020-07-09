@include("admin.common.header")
<p>{{ $error->getMessage() }}</p>
<p>{{ $error->getLine() }}</p>
<p>{{ $error->getFile() }}</p>
@include("admin.common.footer")
