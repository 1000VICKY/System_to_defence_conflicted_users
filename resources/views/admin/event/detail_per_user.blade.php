@include("admin.common.header")

<div class="container brilliant-block">
  <h2>{{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さん バッティング状況一覧</h2>
  <div class="alert alert-info" role="alert">
    <h3>イベント名/{{$event_info->event_name}}({{$event_info->event_start}}開始)</h3>
  </div>
  <p>へ参加している接触履歴のあるユーザー一覧です。</p>
</div>

@if (count($contacted_users[$unique_user_id]) > 0)
<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">ID</th>
        <th class="col-3" scope="col">氏名</th>
        <th class="col-3" scope="col">TEL/メール</th>
        <th class="col-2" scope="col">職業/性別</th>
        <th class="col-2" scope="col">CSV番号</th>
        <th class="col-1" scope="col">参加履歴</th>
      </tr>
    </thead>
    <tbody>
      @foreach($contacted_users[$unique_user_id] as $contact_key => $contact_value)
      <tr @if ($contact_value->gender === "男性") class="male d-flex" @else class="female d-flex" @endif)>
          <td class="col-1"><span class="btn btn-outline-dark">{{$contact_value->id}}</span></td>
          <td class="col-3">
          {{$contact_value->family_name}} {{$contact_value->given_name}}/{{$contact_value->age}}歳<br>
          ({{$contact_value->family_name_sort}} {{$contact_value->given_name_sort}})
          </td>
          <td class="col-3">{{$contact_value->phone_number}}<br>{{$contact_value->email}}</td>
          <td class="col-2">{{$contact_value->job}}<br>{{$contact_value->gender}}</td>
          <td class="col-2">{{$contact_value->reception_number}}</td>
          <td class="col-1"><a href="{{ action("Admin\UserController@detail", ["unique_user_id" => $contact_value->id]) }}" class="btn btn-dark">参加履歴</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@else
<div class="container brilliant-block">
  <ul class="list-group list-group-flush border border-secondary">
    <li class="list-group-item">バッティングユーザーは存在しません。</li>
  </ul>
</div>
@endif
@include("admin.common.footer")
