@include("admin.common.header")
<div class="container">
  @foreach($unique_user_list ?? '' as $key => $value)
  <div class="row row-cols-3">
    <div class="col-sm-2">{{$value->users->id}}</div>
    <div class="col-sm-6">{{$value->users->family_name}} {{$value->users->given_name}}</div>
    <div class="col-sm-2">{{$value->users->family_name_sort}} {{$value->users->given_name_sort}}</div>
    <div class="col-sm-2"><a href="{{ action("Admin\UserController@detail", ["unique_user_id" => $value->users->id]) }}" class="btn btn-dark">参加履歴</a></div>
  </div>
  @endforeach
</div>
@include("admin.common.footer")
