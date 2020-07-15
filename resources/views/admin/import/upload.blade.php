@include("admin.common.header")
<div class="container brilliant-block">
  @if (isset($imported_data["logs"]) && count($imported_data["logs"]) > 0)
    <div class="row">
      <p>以下、CSVファイルの[logs]データのインポートに成功しました。</p>
    </div>
    <ul class="list-group list-group-flush border border-secondary">
    @foreach($imported_data["logs"] as $key => $value)
      <li class="list-group-item">{{$value}}</li>
    @endforeach
    </ul>
  @else
  <p>CSVデータがインポートされませんでした。</p>
  @endif
</div>

<div class="container brilliant-block">
  @if (isset($imported_data["unique_users"]) && count($imported_data["unique_users"]) > 0)
    <div class="row">
      <p>以下、CSVファイルの[unique_users]データのインポートに成功しました。</p>
    </div>
    <ul class="list-group list-group-flush border border-secondary">
    @foreach($imported_data["unique_users"] as $key => $value)
      <li class="list-group-item">{{$value}}</li>
    @endforeach
    </ul>
  @else
  <p>CSVデータがインポートされませんでした。</p>
  @endif
</div>

<div class="container brilliant-block">
  @if (isset($imported_data["events"]) && count($imported_data["events"]) > 0)
    <div class="row">
      <p>以下、CSVファイルの[events]データのインポートに成功しました。</p>
    </div>
    <ul class="list-group list-group-flush border border-secondary">
    @foreach($imported_data["events"] as $key => $value)
    <li class="list-group-item">{{$value}}</li>
    @endforeach
    </ul>
  @else
  <p>CSVデータがインポートされませんでした。</p>
  @endif
</div>

<div class="container brilliant-block">
  @if (isset($imported_data["attended_events"]) && count($imported_data["attended_events"]) > 0)
    <div class="row">
      <p>以下、CSVファイルの[attended_events]データのインポートに成功しました。</p>
    </div>
    <ul class="list-group list-group-flush border border-secondary">
    @foreach($imported_data["attended_events"] as $key => $value)
    <li class="list-group-item">{{$value}}</li>
    @endforeach
    </ul>
  @else
  <p>CSVデータがインポートされませんでした。</p>
  @endif
</div>
@include("admin.common.footer")
