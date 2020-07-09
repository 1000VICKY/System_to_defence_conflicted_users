@include("admin.common.header")
<div class="container">
  @foreach($event_list as $key => $value)
  <div class="row row-cols-3">
    <div class="col-sm-2">{{$value->id}}</div>
    <div class="col-sm-6">{{$value->event_name}}</div>
    <div class="col-sm-2">{{$value->event_start}}</div>
    <div class="col-sm-2"><a href="{{ action("Admin\EventController@user", ["event_id" => $value->id]) }}" class="btn btn-dark">参加者一覧</a></div>
  </div>
  @endforeach
</div>
@include("admin.common.footer")
