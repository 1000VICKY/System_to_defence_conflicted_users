@include("admin.common.header")

<p>マスター会員情報一覧</p>
<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">ID</th>
        <th class="col-3" scope="col">氏名</th>
        <th class="col-3" scope="col">TEL<br>メール</th>
        <th class="col-2" scope="col">職業<br>性別</th>
        <th class="col-2" scope="col">CSV番号</th>
        <th class="col-1" scope="col">詳細ボタン</th>
    </tr>
    </thead>
    <tbody>
      @foreach($unique_user_list as $key => $value)
      <tr @if($value->gender === "男性") class="male d-flex" @else class="d-flex female" @endif>
        <td class="col-1"><p class="btn btn-outline-dark">{{$value->id}}</p></td>
        <td class="col-3">
          <p>{{$value->family_name}} {{$value->given_name}}/{{$value->age}}歳<br>({{$value->family_name_sort}} {{$value->given_name_sort}})</p>
        </td>
        <td class="col-3">{{$value->phone_number}}<br>{{$value->email}}</td>
        <td class="col-2">{{$value->job}}<br>{{$value->gender}}</td>
        <td class="col-2">{{$value->reception_number}}</td>
        <td class="col-1"><a href="{{action("Admin\UserController@detail", ["unique_user_id" => $value->id])}}" class="btn btn-dark">参加履歴</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@include("admin.common.footer")
