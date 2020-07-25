@include("admin.common.header")

<div class="container brilliant-block">
  <h2>これまでに登録した全CSVデータ一覧</h2>
</div>

<div class="container brilliant-block">
  {{$logs->links()}}
  @if ($logs->count() > 0)
  <table class="table table-sm">
    <thead class="thead-dark">
      <tr class="d-flex">
        <th class="col-1" scope="col">ID</th>
        <th class="col-2" scope="col">氏名</th>
        <th class="col-2" scope="col">受付日時<br>CSV番号</th>
        <th class="col-3" scope="col">参加イベント名</th>
        <th class="col-3" scope="col">TEL<br>メール</th>
        <th class="col-1" scope="col">職業<br>性別</th>
    </tr>
    </thead>
    <tbody>
      @foreach($logs as $key => $value)
      <tr @if($value->gender === "男性") class="male d-flex" @else class="female d-flex" @endif>
        <td class="col-1">
          <p class="btn btn-outline-dark">{{$value->unique_user_id}}</p>
          <!-- {{ $value->id }} -->
        </td>
        <td class="col-2">
          {{$value->family_name}} {{$value->given_name}}/{{$value->age}}歳<br>({{$value->family_name_sort}} {{$value->given_name_sort}})
        </td>
        <td class="col-2">{{$value->reception_date}}<br>{{$value->reception_number}}</td>
        <td class="col-3">{{$value->event_name}}<br><hr>メモ:{{$value->question}}</td>
        <td class="col-3">{{$value->phone_number}}<br>{{$value->email}}</td>
        <td class="col-1">{{$value->job}}<br>{{$value->gender}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p>CSVデータが存在しません。</p>
  @endif
</div>
@include("admin.common.footer")
