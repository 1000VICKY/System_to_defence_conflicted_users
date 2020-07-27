@include("admin.common.header")


<div class="container brilliant-block">
  <div class="alert alert-secondary" role="alert">
    <div class="alert-heading">
      <h4>
        <span class="btn btn-outline-dark">{{$unique_user_info->id}}</span>
        {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さん
      </h4>
    </div>
    <p>現在、{{$unique_user_info->family_name}} {{$unique_user_info->given_name}}({{$unique_user_info->age}}歳/{{$unique_user_info->gender}})さんの接触履歴を閲覧中です。</p>
    <hr>
    <p class="mb-0">
      <a href="{{action("Admin\UserController@detail", ["unique_user_id" => $unique_user_info->id])}}" class="btn btn-dark">
        {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さんの参加履歴を確認
      </a>
    </p>
  </div>
</div>

@if ($contacted_user_list->count() > 0)
<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">ID</th>
        <th class="col-2" scope="col">氏名</th>
        <th class="col-3" scope="col">TEL/メール</th>
        <th class="col-2" scope="col">職業/性別</th>
        <th class="col-2" scope="col">CSV番号</th>
        <th class="col-1" scope="col">詳細ボタン</th>
        <th class="col-1" scope="col">接触履歴</th>
    </tr>
    </thead>
    <tbody>
      @foreach($contacted_user_list as $key => $value)
      <tr @if($value->gender === "男性") class="male d-flex" @else class="d-flex female" @endif>
        <td class="col-1"><p class="btn btn-outline-dark">{{$value->id}}</p></td>
        <td class="col-2">
          <p>
            {{$value->family_name}} {{$value->given_name}}/{{$value->age}}歳<br>
            ({{$value->family_name_sort}} {{$value->given_name_sort}})
          </p>
        </td>
        <td class="col-3">{{$value->phone_number}}<br>{{$value->email}}</td>
        <td class="col-2">{{$value->job}}<br>{{$value->gender}}</td>
        <td class="col-2">{{$value->reception_number}}</td>
        <td class="col-1"><a href="{{action("Admin\UserController@detail", ["unique_user_id" => $value->id])}}" class="btn btn-dark">参加履歴</a></td>
        <td class="col-1"><a href="{{action("Admin\UserController@contact", ["unique_user_id" => $value->id])}}" class="btn btn-dark">接触履歴</a></td>
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
