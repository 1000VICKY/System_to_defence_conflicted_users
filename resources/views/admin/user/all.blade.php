@include("admin.common.header")
<div class="container brilliant-block">
  <p>これまでに登録した全CSVデータ一覧を閲覧中</p>
</div>
<div class="container brilliant-block">
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">ID</th>
        <th class="col-2" scope="col">氏名<br>性別</th>
        <th class="col-2" scope="col">受付日時<br>CSV番号</th>
        <th class="col-3" scope="col">参加イベント名</th>

        <th class="col-2" scope="col">TEL<br>メール</th>
        <th class="col-1" scope="col">職業</th>
        <th class="col-1" scope="col">ユーザーID</th>
    </tr>
    </thead>
    <tbody>
      @foreach($logs as $key => $value)
      <tr @if($value->gender === "男性") class="male d-flex" @else class="female d-flex" @endif>
        <td class="col-1"><p class="btn btn-outline-dark">{{$value->id}}</p></td>
        <td class="col-2">
          {{$value->family_name}} {{$value->given_name}}/{{$value->age}}歳<br>({{$value->family_name_sort}} {{$value->given_name_sort}})<br>{{$value->gender}}
        </td>
        <td class="col-2">{{$value->reception_date}}<br>{{$value->reception_number}}</td>
        <td class="col-3">{{$value->event_name}}</td>
        <td class="col-2">{{$value->phone_number}}<br>{{$value->email}}</td>
        <td class="col-1">{{$value->job}}</td>
        <td class="col-1"><p class="btn btn-outline-dark">{{$value->unique_user_id}}</p></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@include("admin.common.footer")
