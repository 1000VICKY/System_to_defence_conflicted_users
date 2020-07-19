@include("admin.common.header")

<div class="container brilliant-block">
  <ul class="list-group list-group-flush border border-secondary">
    <li class="list-group-item">
        {{$unique_user_info->family_name}} {{$unique_user_info->given_name}}さんの情報変更
    </li>
  </ul>
</div>

<div class="container brilliant-block">
  {{ Form :: open([
    "url" => action("Admin\UserController@postUpdate", [
        "unique_user_id" => $unique_user_info->id,
    ]),
    "method" => "POST",
  ])}}
  <div class="row">
    <div class="col form-group">
      <label>氏名:名字</label>
      @if ($errors->has("family_name"))
      <span class="error_validation">{{$errors->first("family_name")}}</span>
      @endif
      {{ Form :: input("text", "family_name", $unique_user_info->family_name, [
        "class" => "form-control form-control-lg",
        "id" => "family_name",
        "placeholder" => "例)山田"
      ])}}
    </div>
    <div class="col form-group">
      <label>氏名:名前</label>
      @if ($errors->has("given_name"))
      <span class="error_validation">{{$errors->first("given_name")}}</span>
      @endif
      {{ Form :: input("text", "given_name",  $unique_user_info->given_name, [
        "class" => "form-control form-control-lg",
        "id" => "given_name",
        "placeholder" => "例)太郎"
      ])}}
    </div>
  </div>
  <div class="row">
    <div class="col form-group">
      <label>氏名:名字(フリガナ)</label>
      @if ($errors->has("family_name_sort"))
      <span class="error_validation">{{$errors->first("family_name_sort")}}</span>
      @endif
      {{ Form :: input("text", "family_name_sort",  $unique_user_info->family_name_sort, [
        "class" => "form-control form-control-lg",
        "id" => "family_name_sort",
        "placeholder" => "例)ヤマダ"
      ])}}
    </div>
    <div class="col form-group">
      <label>氏名:名前(フリガナ)</label>
      @if ($errors->has("given_name_sort"))
      <span class="error_validation">{{$errors->first("given_name_sort")}}</span>
      @endif
      {{ Form :: input("text", "given_name_sort",  $unique_user_info->given_name_sort, [
        "class" => "form-control form-control-lg",
        "id" => "given_name_sort",
        "placeholder" => "例)タロウ"
      ])}}
    </div>
  </div>
  <div class="row">
    <div class="col form-group">
      <label>年齢</label>
      @if ($errors->has("age"))
      <span class="error_validation">{{$errors->first("age")}}</span>
      @endif
      {{ Form :: select("age", $age_list,  $unique_user_info->age, [
        "class" => "form-control form-control-lg",
        "id" => "age",
      ])}}
    </div>
    <div class="col form-group">
      <label>性別</label>
      @if ($errors->has("gender"))
      <span class="error_validation">{{$errors->first("gender")}}</span>
      @endif
      {{ Form :: select("gender", [
        "男性" => "男性",
        "女性" => "女性"],
        $unique_user_info->gender,
        [
          "class" => "form-control form-control-lg",
           "id" => "gender",
      ])}}
    </div>
  </div>

  <div class="row">
    <div class="col form-group">
      <label>電話番号</label>
      @if ($errors->has("phone_number"))
      <span class="error_validation">{{$errors->first("phone_number")}}</span>
      @endif
      {{ Form :: input("text", "phone_number",  $unique_user_info->phone_number, [
        "class" => "form-control form-control-lg",
        "id" => "phone_number",
        "placeholder" => "例)090-0000-0000"
      ])}}
    </div>
    <div class="col form-group">
      <label>メールアドレス</label>
      @if ($errors->has("email"))
      <span class="error_validation">{{$errors->first("email")}}</span>
      @endif
      {{ Form :: input("text", "email",  $unique_user_info->email, [
        "class" => "form-control form-control-lg",
        "id" => "email",
        "placeholder" => "例)taro@gmail.com"
      ])}}
    </div>
  </div>

  <div class="row">
    <div class="col form-group">
      <label>CSV番号</label>
      @if ($errors->has("reception_number"))
      <span class="error_validation">{{$errors->first("reception_number")}}</span>
      @endif
      {{ Form :: input("text", "reception_number",  $unique_user_info->reception_number, [
        "class" => "form-control form-control-lg",
        "id" => "reception_number",
        "placeholder" => "例)9999-0001"
      ])}}
    </div>
    <div class="col form-group">
      <label>職業</label>
      @if ($errors->has("job"))
      <span class="error_validation">{{$errors->first("job")}}</span>
      @endif
      {{ Form :: input("text", "job",  $unique_user_info->job, [
        "class" => "form-control form-control-lg",
        "id" => "job",
        "placeholder" => "例)会社員"
      ])}}
    </div>
  </div>

  <div class="form-group">
    {{ Form :: input("hidden", "unique_user_id", $unique_user_info->id)}}
    {{ Form :: input("submit", "create_new_user", "新規会員登録", [
      "class" => "form-control form-control-lg btn btn-dark",
      "id" => "create_new_user",
    ])}}
  </div>
  {{ Form :: close()}}
</div>
@include("admin.common.footer")
