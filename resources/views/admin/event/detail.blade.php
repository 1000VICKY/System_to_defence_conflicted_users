@include("admin.common.header")
<div class="container brilliant-block">
  <ul class="list-group list-group-flush border border-secondary">
    <li class="list-group-item">イベント名「{{$event_info->event_name}}」への参加者一覧を取得</li>
    <li class="list-group-item">開催日:{{$event_info->event_start}}</li>
  </ul>
</div>
<div class="container brilliant-block">
  @foreach($attended_unique_users as $key => $value)
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">ID</th>
        <th class="col-3" scope="col">氏名</th>
        <th class="col-3" scope="col">TEL<br>メール</th>
        <th class="col-2" scope="col">職業<br>性別</th>
        <th class="col-2" scope="col">CSV番号</th>
        <th class="col-1" scope="col">参加履歴</th>
      </tr>
    </thead>
    <tbody>
      <tr @if ($value->users->gender === "男性") class="male d-flex" @else class="female d-flex" @endif)>
        <td class="col-1"><p class="btn btn-outline-dark">{{$value->users->id}}</p></td>
        <td class="col-3">
          <p>{{$value->users->family_name}} {{$value->users->given_name}}/{{$value->users->age}}歳<br>
            ({{$value->users->family_name_sort}} {{$value->users->given_name_sort}})</p>
        </td>
        <td class="col-3">{{$value->users->phone_number}}<br>{{$value->users->email}}</td>
        <td class="col-2">{{$value->users->job}}<br>{{$value->users->gender}}</td>
        <td class="col-2">{{$value->users->reception_number}}</td>
        <td class="col-1"><a href="{{action("Admin\UserController@detail", ["unique_user_id" => $value->users->id])}}" class="btn btn-dark">参加履歴</a></td>
      </tr>
      @if (count($contacted_users[$value->users->id]) > 0)
      <!-- 接触履歴 -->
      <tr class="d-flex">
        <th class="col-1" scope="col">接触履歴</th>
        <th class="col-3" scope="col"></th>
        <th class="col-3" scope="col"></th>
        <th class="col-2" scope="col"></th>
        <th class="col-2" scope="col"></th>
      </tr>
      @foreach($contacted_users[$value->users->id] as $contact_key => $contact_value)
      <tr @if ($contact_value->users->gender === "男性") class="male d-flex" @else class="female d-flex" @endif)>
        <td class="col-1"></td>
        <td class="col-3">
          {{$contact_value->users->family_name}} {{$contact_value->users->given_name}}/{{$value->users->age}}歳<br>
          ({{$contact_value->users->family_name_sort}} {{$contact_value->users->given_name_sort}})
        </td>
        <td class="col-3">{{$contact_value->users->phone_number}}<br>{{$contact_value->users->email}}</td>
        <td class="col-2">{{$contact_value->users->job}}<br>{{$contact_value->users->gender}}</td>
        <td class="col-2">{{$contact_value->users->reception_number}}</td>
      </tr>
      @endforeach
      @endif
    </tbody>
  </table>
  @endforeach
</div>
@include("admin.common.footer")
