@include("admin.common.header")
<div class="container">
  @foreach($logs as $key => $value)
  <div class="row">
    <div class="col-sm">{{$value->id}}</div>
    <div class="col-sm">
      <p>{{$value->family_name}} {{$value->given_name}} ({{$value->age}}歳)</p>
      <p>({{$value->family_name_sort}} {{$value->given_name_sort}})</p>
    </div>
    <div class="col-sm-6">{{$value->event_name}}</div>
    <div class="col-sm">{{$value->event_start}}</div>
  </div>
  @endforeach
</div>
@include("admin.common.footer")
