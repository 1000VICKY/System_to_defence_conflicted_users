@include("admin.common.header")

<div class="container brilliant-block">
  <p>マスターイベント情報一覧</p>
</div>

<div class="container brilliant-block">
  {{ Form :: open([
    "url" => action("Admin\EventController@index"),
    "method" => "GET",
  ])}}
  <div class="row">
    <div class="col">
      <label>任意のイベント名で検索</label>
      {{ Form :: input("text", "keyword", $keyword, [
        "class" => "form-control",
        "id" => "keyword",
      ])}}
    </div>
    <div class="col">
      <label>日付で検索</label>
      {{ Form :: input("text", "event_start", $event_start, [
        "class" => "form-control",
        "id" => "event_start",
      ])}}
    </div>
    <div class="col">
      <label>左記内容で検索する</label>
      <p>{{ Form :: input("submit", "search_user", "入力した内容でイベント検索", [
        "class" => "form-control btn btn-dark",
        "id" => "search_user_button",
      ])}}</p>
    </div>
  </div>
  {{ Form :: close()}}
</div>

<div class="container brilliant-block">
  {{$event_list->links()}}

  @if ($event_list->count() > 0)
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-2" scope="col">ID</th>
        <th class="col-4" scope="col">イベント名</th>
        <th class="col-4" scope="col">イベント日時</th>
        <th class="col-2" scope="col">参加者一覧</th>
      </tr>
    </thead>
    <tbody>
      @foreach($event_list as $key => $value)
      <tr class="d-flex @if ($value->future === 0) past-event-row @endif">
        <td class="col-2"><p class="btn btn-outline-dark">{{$value->id}}</p></td>
        <td class="col-4">{{$value->event_name}}</td>
        <td class="col-4">{{$value->event_start}}</td>
        <td class="col-2"><a href="{{ action("Admin\EventController@detail", ["event_id" => $value->id]) }}" class="btn btn-dark">参加者一覧</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p>マスターイベント情報が存在しません。</p>
  @endif
</div>
@include("admin.common.footer")
