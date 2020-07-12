@include("admin.common.header")
<div class="container brilliant-block">
  <ul class="list-group list-group-flush">
    <li class="list-group-item">
      {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}
      さんの参加履歴を閲覧中</li>
  </ul>
</div>
<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">イベントID</th>
        <th class="col-4" scope="col">イベント名</th>
        <th class="col-4" scope="col">イベント開始日時</th>
        <th class="col-1" scope="col">開催状況</th>
        <th class="col-2" scope="col">参加者詳細</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($before_events as $k => $v)
      <tr class="d-flex">
        <td class="col-1"><p class="btn btn-outline-dark">{{$v->events->id}}</p></td>
        <td class="col-4">{{$v->events->event_name}}</td>
        <td class="col-4">{{$v->events->event_start}}</td>
        <td class="col-1">開催前</td>
        <td class="col-2"><a class="btn btn-dark" href="{{action("Admin\EventController@detail", ["event_id" => $v->events->id])}}">参加者一覧</a></td>
      </tr>
      @endforeach
      @foreach ($attended_events as $k => $v)
      <tr class="d-flex past-event-row">
        <td class="col-1"><p class="btn btn-outline-dark">{{$v->events->id}}</p></td>
        <td class="col-4">{{$v->events->event_name}}</td>
        <td class="col-4">{{$v->events->event_start}}</td>
        <td class="col-1">開催済み</td>
        <td class="col-2"><a class="btn btn-dark" href="{{action("Admin\EventController@detail", ["event_id" => $v->events->id])}}">参加者一覧</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@include("admin.common.footer")
