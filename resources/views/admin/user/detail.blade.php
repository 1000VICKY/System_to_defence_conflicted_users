@include("admin.common.header")
<div class="container">
  @foreach($historical_data as $key => $value)
  <div class="row">
    <div class="col-sm">{{$value->events->id}}</div>
    <div class="col-sm">{{$value->events->event_name}}</div>
    <div class="col-sm">{{$value->events->event_start}}</div>
  </div>
  @endforeach
</div>
@include("admin.common.footer")
