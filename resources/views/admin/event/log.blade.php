@include("admin.common.header")

<div class="container brilliant-block">
  <h2>過去ログ一覧</h2>
</div>

@if ($all_logs->count() > 0)
<div class="container brilliant-block all-logs">
  {{$all_logs->links()}}
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">会員ID</th>
        <th class="col-2" scope="col">会員名</th>
        <th class="col-2" scope="col">電話番号/email</th>
        <th class="col-2" scope="col">イベント名</th>
        <th class="col-2" scope="col">イベント開始日時/CSV番号</th>
        <th class="col-1" scope="col">参加状態</th>
        <th class="col-1" scope="col">変更</th>
        <th class="col-1" scope="col">詳細</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($all_logs as $k => $v)
      <tr class="d-flex past-event-row">
        <td class="col-1">
          <span class="btn btn-outline-dark">{{$v->users->id}}</span>
        </td>
        <td class="col-2">
          {{$v->users->family_name}} {{$v->users->given_name}}({{$v->users->age}}歳)<br>
          <small>{{$v->users->family_name_sort}} {{$v->users->given_name_sort}}</small>
        </td>
        <td class="col-2">{{$v->users->phone_number}}<br>{{$v->users->email}}</td>
        <td class="col-2">
          <span class="btn btn-outline-dark">{{$v->events->id}}</span>
          {{$v->events->event_name}}
        </td>
        <td class="col-2">
          {{$v->events->event_start}}<br>{{$v->users->reception_number}}
        </td>
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
                  "unique_user_id" => $v->users->id,
                  "event_id" => $v->events->id,
              ])
          ])}}
          @if ((int)$v->is_participated === 1)
          {{ Form :: input("submit", "change_participated", "不参加に", [
              "class" => "btn btn-outline-dark",
          ])}}
          {{ Form :: input("hidden", "is_participated", 0)}}
          @else
          {{ Form :: input("submit", "change_participated", "参加済に", [
              "class" => "btn btn-outline-dark",
          ])}}
          {{ Form :: input("hidden", "is_participated", 1)}}
          @endif
          {{ Form :: input("hidden", "unique_user_id", $v->users->id)}}
          {{ Form :: input("hidden", "event_id", $v->events->id)}}
          {{ Form :: close() }}
        </td>
        <td class="col-1"><a class="btn btn-dark" href="{{action("Admin\UserController@detail", ["unique_user_id" => $v->users->id])}}">参加履歴</a></td>
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
