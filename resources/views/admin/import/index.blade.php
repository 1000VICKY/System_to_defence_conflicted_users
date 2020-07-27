
@include("admin.common.header")


<div class="container brilliant-block">
  <h2>新規CSVファイルのインポート処理</h2>
</div>

<div class="container brilliant-block">
  {{
    Form::open([
      "url" => action("Admin\ImportController@upload"),
      "method" => "post",
      "enctype" => "multipart/form-data",
    ])
  }}
  <form class="form">
    <div class="form-group">
      <label for="exampleFormControlFile1">CSVファイルをインポートして下さい</label>
      {{ Form::file("csv_file", [
          "id" => "csv_file_upload",
          "class" => "csv-file-upload form-control-file",
      ]) }}
      @if ($errors->has("csv_file"))
        <span class="error_validation">{{ $errors->first("csv_file") }}</span>
      @endif
    </div>
    <button type="submit" class="btn btn-primary mb-2">CSVインポートの実行</button>
  </form>
</div>

<div class="container brilliant-block">
  <p>バッティング検証に必要なデータは以下一覧です<br><small>全て、必須項目となります。</small></p>
  <div class="row">
    @foreach ($required_header as $key => $value)
    <div class="col-3">
      <p class="btn btn-outline-dark">{{$value}}</p>
    </div>
    @endforeach
  </div>
</div>
@include("admin.common.footer")
