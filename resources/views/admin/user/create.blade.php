@include("admin.common.header")


<div class="container brilliant-block">
  <h2>新規会員情報登録</h2>
</div>

<div class="container brilliant-block">
  {{ Form :: open([
    "url" => action("Admin\UserController@postCreate"),
    "method" => "POST",
  ])}}

  <div class="row">
    <div class="col form-group">
    　<label>氏名:名字</label>
      @if ($errors->has("family_name"))
      <span class="error_validation">{{$errors->first("family_name")}}</span>
      @endif
      {{ Form :: input("text", "family_name", null, [
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
      {{ Form :: input("text", "given_name", null, [
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
      {{ Form :: input("text", "family_name_sort", null, [
        "class" => "form-control form-control-lg",
        "id" => "family_name_sort",
        "placeholder" => "例)ヤマダ"
      ])}}
    </div>
    <div class="col form-group">
      <label>氏名:お名前(フリガナ)</label>
      @if ($errors->has("given_name_sort"))
      <span class="error_validation">{{$errors->first("given_name_sort")}}</span>
      @endif
      {{ Form :: input("text", "given_name_sort", null, [
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
      {{ Form :: select("age", $age_list, "40", [
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
        "男性",
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
      {{ Form :: input("text", "phone_number", null, [
        "class" => "form-control form-control-lg",
        "id" => "phone_number",
        "placeholder" => "例)090-0000-0000"
      ])}}
      <span>※電話番号はevent-formの仕様に則って下さい。例=>9012345678など</span>
    </div>
    <div class="col form-group">
      <label>メールアドレス</label>
      @if ($errors->has("email"))
      <span class="error_validation">{{$errors->first("email")}}</span>
      @endif
      {{ Form :: input("text", "email", null, [
        "class" => "form-control form-control-lg",
        "id" => "email",
        "placeholder" => "例)taro@gmail.com"
      ])}}
    </div>
  </div>

  <div class="row">
    <div class="col form-group">
      <label>CSV番号(例:0000-0001)</label>
      @if ($errors->has("reception_number"))
      <span class="error_validation">{{$errors->first("reception_number")}}</span>
      @endif
      {{ Form :: input("text", "reception_number", null, [
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
      {{ Form :: input("text", "job", null, [
        "class" => "form-control form-control-lg",
        "id" => "job",
        "placeholder" => "例)会社員"
      ])}}
    </div>
  </div>


  <div class="form-group">
    {{ Form :: input("submit", "create_new_user", "新規会員登録", [
      "class" => "form-control form-control-lg btn btn-dark",
      "id" => "create_new_user",
    ])}}
  </div>
  {{ Form :: close()}}
</div>
@include("admin.common.footer")
