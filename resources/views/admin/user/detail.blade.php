@include("admin.common.header")

<div class="container brilliant-block">
  <ul class="list-group list-group-flush border border-secondary">
    <li class="list-group-item">
      ({{$unique_user_info->id}}){{$unique_user_info->family_name}} {{$unique_user_info->given_name}}
      さんの参加可能な本日以降のイベント一覧
    </li>
    <li class="list-group-item">
      参加者一覧をクリックすると、参加予定のユーザー一覧を確認できます。
    </li>
    <li class="list-group-item">
      <a href="{{action("Admin\UserController@contact", ["unique_user_id" => $unique_user_info->id])}}" class="btn btn-dark">
        {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さんの接触履歴を確認
      </a>
    </li>
  </ul>
</div>

<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">イベントID</th>
        <th class="col-4" scope="col">イベント名</th>
        <th class="col-4" scope="col">イベント開始日時</th>
        <th class="col-1" scope="col">衝突率</th>
        <th class="col-2" scope="col">参加者詳細</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($not_attended_events as $k => $v)
      <tr class="d-flex">
        <td class="col-1"><p class="btn btn-outline-dark">{{$v->id}}</p></td>
        <td class="col-4">{{$v->event_name}}</td>
        <td class="col-4">{{$v->event_start}}</td>
        <td class="col-1">
          {{$v->percentage}}%<br>
          ({{$v->numerator}}人/{{$v->denominator}}人中)
        </td>
        <td class="col-2"><a class="btn btn-dark" href="{{action("Admin\EventController@detail", ["event_id" => $v->id])}}">参加者一覧</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>


<div class="container brilliant-block">
  <ul class="list-group list-group-flush border border-secondary">
    <li class="list-group-item">
      {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}
      さんの参加履歴および参加予定イベント一覧
    </li>
    <li class="list-group-item">
      参加者一覧をクリックすると、そのイベントに参加した会員ユーザー一覧を確認できます。
    </li>
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
      @foreach ($future_events as $k => $v)
      <tr class="d-flex">
        <td class="col-1"><p class="btn btn-outline-dark">{{$v->events->id}}</p></td>
        <td class="col-4">{{$v->events->event_name}}</td>
        <td class="col-4">{{$v->events->event_start}}</td>
        <td class="col-1">参加予定</td></td>
        <td class="col-2"><a class="btn btn-dark" href="{{action("Admin\EventController@detail", ["event_id" => $v->events->id])}}">参加者一覧</a></td>
      </tr>
      @endforeach
      @foreach ($attended_events as $k => $v)
      <tr class="d-flex past-event-row">
        <td class="col-1"><p class="btn btn-outline-dark">{{$v->events->id}}</p></td>
        <td class="col-4">{{$v->events->event_name}}</td>
        <td class="col-4">{{$v->events->event_start}}</td>
        <td class="col-1">参加済</td>
        <td class="col-2"><a class="btn btn-dark" href="{{action("Admin\EventController@detail", ["event_id" => $v->events->id])}}">参加者一覧</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@include("admin.common.footer")
