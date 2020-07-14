@include("admin.common.header")
<p>マスターイベント情報一覧</p>
<div class="container brilliant-block">
  {{$event_list->links()}}
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
</div>
@include("admin.common.footer")
