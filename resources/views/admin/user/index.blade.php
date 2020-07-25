@include("admin.common.header")

<div class="container brilliant-block">
  <h2>現在登録中のマスター会員情報一覧</h2>
</div>

<div class="container brilliant-block">
  <ul class="list-group list-group-flush border border-secondary">
    <li class="list-group-item">
      [参加履歴]ボタンをクリックすると
      指定したユーザーの参加履歴が閲覧できます。
    </li>
    <li class="list-group-item">
      [接触履歴]ボタンをクリックすると
      指定したユーザーと同席したユーザー一覧を確認できます。
    </li>
  </ul>
</div>

{{ Form :: open([
  "url" => action("Admin\UserController@index"),
  "method" => "GET",
])}}
<div class="container brilliant-block">
  <div class="row">
    <div class="col">
      <label>お名前</label>
      {{ Form :: input("text", "keyword", $keyword, [
        "class" => "form-control form-control-lg",
        "id" => "keyword",
        "placeholder" => "例)ヤマダタロウ or 山田 or 太郎など"
      ])}}
    </div>
    <div class="col">
      <label>メールアドレス</label>
      {{ Form :: input("text", "email", $email, [
        "class" => "form-control form-control-lg",
        "id" => "email",
        "placeholder" => "例)brilliant@gmail.com"
      ])}}
    </div>
  </diV>
</div>
<div class="container brilliant-block">
  <div class="row">
    <div class="col">
      {{ Form :: input("submit", "search_user", "入力した名前またはメールアドレスで検索", [
        "class" => "form-control form-control-lg btn btn-dark",
        "id" => "search_user_button",
      ])}}
    </div>
  </div>
</div>
{{ Form :: close()}}

<div class="container brilliant-block">
  {{$unique_user_list->links()}}
  @if ($unique_user_list->count() > 0)
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
      @foreach($unique_user_list as $key => $value)
      <tr @if($value->gender === "男性") class="male d-flex" @else class="d-flex female" @endif>
        <td class="col-1">
          <a class="btn btn-outline-dark" href="{{action("Admin\UserController@update", ["unique_user_id" => $value->id])}}">
            {{$value->id}}
          </a>
        </td>
        <td class="col-2">
          {{$value->family_name}} {{$value->given_name}}/{{$value->age}}歳<br>
          ({{$value->family_name_sort}} {{$value->given_name_sort}})</p>
        </td>
        <td class="col-3">{{$value->phone_number}}<br>{{$value->email}}</td>
        <td class="col-2">{{$value->job}}<br>{{$value->gender}}</td>
        <td class="col-2">{{$value->reception_number}}</td>
        <td class="col-1">
          <p><a href="{{action("Admin\UserController@detail", ["unique_user_id" => $value->id])}}" class="btn btn-dark">参加履歴</a></p>
        </td>
        <td class="col-1">
          <p><a href="{{action("Admin\UserController@contact", ["unique_user_id" => $value->id])}}" class="btn btn-dark">接触履歴</a></p>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p>マスター会員データが存在しません。</p>
  @endif
</div>
@include("admin.common.footer")
