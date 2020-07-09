@include("admin.common.header")
<div class="container">
  <div class="row">
    <div class="alert alert-secondary" role="alert">
      A simple secondary alert—check it out!
    </div>
  </div>
</div>
<div class="container">
  @foreach($unique_user_list as $key => $value)
  <div class="row">
    <div class="col-sm">{{$value->id}}</div>
    <div class="col-sm">
      <p>{{$value->family_name}} {{$value->given_name}}({{$value->age}}歳)</p>
      <p>({{$value->family_name_sort}} {{$value->given_name_sort}})</p>
    </div>
    <div class="col-sm">{{$value->phone_number}}</div>
    <div class="col-sm">{{$value->email}}</div>
    <div class="col-sm">{{$value->job}}</div>
    <div class="col-sm">{{$value->gender}}</div>
    <div class="col-sm">{{$value->reception_number}}</div>
    <div class="col-sm"><a href="{{action("Admin\UserController@detail", ["unique_user_id" => $value->id])}}" class="btn btn-dark">参加履歴</a></div>
  </div>
  @endforeach
</div>
@include("admin.common.footer")
