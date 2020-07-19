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
      <a href="{{action("Admin\UserController@detail", ["unique_user_id" => $unique_user_info->id])}}" class="btn btn-dark">
        {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さんの参加履歴を確認
      </a>
    </p>
  </div>
</div>

<div class="container brilliant-block">
  <ul class="list-group list-group-flush border border-secondary">
    <li class="list-group-item">
      ({{$unique_user_info->id}}){{$unique_user_info->family_name}} {{$unique_user_info->given_name}}
      さんがこれまでに同席したユーザー一覧となります。
    </li>
    <li class="list-group-item">
      参加者一覧をクリックすると、参加予定のユーザー一覧を確認できます。
    </li>
  </ul>
</div>
@if ($contacted_user_list->count() > 0)
<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">ID</th>
        <th class="col-2" scope="col">氏名</th>
        <th class="col-3" scope="col">TEL<br>メール</th>
        <th class="col-2" scope="col">職業<br>性別</th>
        <th class="col-2" scope="col">CSV番号</th>
        <th class="col-1" scope="col">詳細ボタン</th>
        <th class="col-1" scope="col">接触履歴</th>
    </tr>
    </thead>
    <tbody>
      @foreach($contacted_user_list as $key => $value)
      <tr @if($value->users->gender === "男性") class="male d-flex" @else class="d-flex female" @endif>
        <td class="col-1"><p class="btn btn-outline-dark">{{$value->users->id}}</p></td>
        <td class="col-2">
          <p>
            {{$value->users->family_name}} {{$value->users->given_name}}/{{$value->users->age}}歳<br>
            ({{$value->users->family_name_sort}} {{$value->users->given_name_sort}})
          </p>
        </td>
        <td class="col-3">{{$value->users->phone_number}}<br>{{$value->users->email}}</td>
        <td class="col-2">{{$value->users->job}}<br>{{$value->users->gender}}</td>
        <td class="col-2">{{$value->users->reception_number}}</td>
        <td class="col-1"><a href="{{action("Admin\UserController@detail", ["unique_user_id" => $value->users->id])}}" class="btn btn-dark">参加履歴</a></td>
        <td class="col-1"><a href="{{action("Admin\UserController@contact", ["unique_user_id" => $value->users->id])}}" class="btn btn-dark">接触履歴</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@else
<div class="container brilliant-block">
  <p>現在、接触済みユーザーは存在しません。</p>
</div>
@endif
@include("admin.common.footer")
