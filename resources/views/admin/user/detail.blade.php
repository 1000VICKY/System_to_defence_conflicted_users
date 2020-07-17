@include("admin.common.header")

<div class="container brilliant-block">
  <div class="alert alert-secondary" role="alert">
    <div class="alert-heading">
      <h4>
        <span class="btn btn-outline-dark">{{$unique_user_info->id}}</span>
        {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さん
      </h4>
    </div>
    <p>現在、{{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さんの詳細情報を閲覧中です。</p>
    <hr>
    <p class="mb-0">
      <a href="{{action("Admin\UserController@contact", ["unique_user_id" => $unique_user_info->id])}}" class="btn btn-dark">
        {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さんの接触履歴を確認
      </a>
    </p>
  </div>
</div>


@if ($not_attended_events->count() > 0)
<div class="container brilliant-block">
  <ul class="list-group list-group-flush border border-secondary">
    <li class="list-group-item">
      ({{$unique_user_info->id}}){{$unique_user_info->family_name}} {{$unique_user_info->given_name}}
      さんの参加可能な本日以降のイベント一覧
    </li>
    <li class="list-group-item">
      参加者一覧をクリックすると、参加予定のユーザー一覧を確認できます。
    </li>
  </ul>
</div>

<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">イベントID</th>
        <th class="col-4" scope="col">イベント名</th>
        <th class="col-2" scope="col">イベント開始日時</th>
        <th class="col-1" scope="col">衝突率</th>
        <th class="col-1" scope="col">参加予定</th>
        <th class="col-2" scope="col">参加者詳細</th>
        <th class="col-1" scope="col">参加する</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($not_attended_events as $k => $v)
      <tr class="d-flex">
        <td class="col-1"><p class="btn btn-outline-dark">{{$v->id}}</p></td>
        <td class="col-4">{{$v->event_name}}</td>
        <td class="col-2">{{$v->event_start}}</td>
        <td class="col-1">
          {{$v->percentage}}%<br>
          ({{$v->numerator}}人/{{$v->denominator}}人中)
        </td>
        <td class="col-1">予定なし</td>
        <td class="col-2"><a class="btn btn-dark" href="{{action("Admin\EventController@detail", ["event_id" => $v->id])}}">参加者一覧</a></td>
        <td class="col-1">
          {{ Form :: open([
              "url" => action("Admin\UserController@attend", [
                  "unique_user_id" => $unique_user_info->id,
                  "event_id" => $v->id,
                ]),
              "method" => "POST",
          ])}}
          {{ Form :: input("submit", "button_to_attend", "参加する", [
              "class" => "btn btn-dark"
          ])}}
          {{ Form :: input("hidden", "unique_user_id", $unique_user_info->id) }}
          {{ Form :: input("hidden", "event_id", $v->id) }}
          {{ Form :: close() }}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif

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

@if ($attended_events->count() > 0 || $future_events->count() > 0)
<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">イベントID</th>
        <th class="col-4" scope="col">イベント名</th>
        <th class="col-3" scope="col">イベント開始日時</th>
        <th class="col-1" scope="col">参加状態</th>
        <th class="col-1" scope="col">変更</th>
        <th class="col-2" scope="col">参加者詳細</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($future_events as $k => $v)
      <tr class="d-flex">
        <td class="col-1"><p class="btn btn-outline-dark">{{$v->events->id}}</p></td>
        <td class="col-4">{{$v->events->event_name}}</td>
        <td class="col-3">{{$v->events->event_start}}</td>
        <td class="col-1">
          @if ((int)$v->is_participated === 1)
          参加予定
          @else
          不参加
          @endif
        <td class="col-1">
            {{ Form :: open([
                "url" => action("Admin\UserController@participate", [
                    "unique_user_id" => $unique_user_info->id,
                    "event_id" => $v->events->id,
                ])
            ])}}
            @if ((int)$v->is_participated === 1)
            {{ Form :: input("submit", "change_participated", "参加予定", [
                "class" => "btn btn-outline-dark",
            ])}}
            {{ Form :: input("hidden", "is_participated", 0)}}
            @else
            {{ Form :: input("submit", "change_participated", "不参加", [
                "class" => "btn btn-outline-dark",
            ])}}
            {{ Form :: input("hidden", "is_participated", 1)}}
            @endif
            {{ Form :: input("hidden", "unique_user_id", $unique_user_info->id)}}
            {{ Form :: input("hidden", "event_id", $v->events->id)}}
            {{ Form :: close() }}
          </td>
        <td class="col-2"><a class="btn btn-dark" href="{{action("Admin\EventController@detail", ["event_id" => $v->events->id])}}">参加者一覧</a></td>
      </tr>
      @endforeach
      @foreach ($attended_events as $k => $v)
      <tr class="d-flex past-event-row">
        <td class="col-1"><p class="btn btn-outline-dark">{{$v->events->id}}</p></td>
        <td class="col-4">{{$v->events->event_name}}</td>
        <td class="col-3">{{$v->events->event_start}}</td>
        <td class="col-1">
          @if ((int)$v->is_participated === 1)
          参加済み
          @else
          不参加
          @endif
        </td>
        <td class="col-1">
          {{ Form :: open([
              "url" => action("Admin\UserController@participate", [
                  "unique_user_id" => $unique_user_info->id,
                  "event_id" => $v->events->id,
              ])
          ])}}
          @if ((int)$v->is_participated === 1)
          {{ Form :: input("submit", "change_participated", "変更", [
              "class" => "btn btn-outline-dark",
          ])}}
          {{ Form :: input("hidden", "is_participated", 0)}}
          @else
          {{ Form :: input("submit", "change_participated", "変更", [
              "class" => "btn btn-outline-dark",
          ])}}
          {{ Form :: input("hidden", "is_participated", 1)}}
          @endif
          {{ Form :: input("hidden", "unique_user_id", $unique_user_info->id)}}
          {{ Form :: input("hidden", "event_id", $v->events->id)}}
          {{ Form :: close() }}
        </td>
        <td class="col-2"><a class="btn btn-dark" href="{{action("Admin\EventController@detail", ["event_id" => $v->events->id])}}">参加者一覧</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@else
<div class="container brilliant-block">
  <div class="row">
    <div class="col">
      <p>参加履歴がありません。</p>
    </div>
  </div>
</div>
@endif
@include("admin.common.footer")
