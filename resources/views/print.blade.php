<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Data Jadwal</title>
</head>
<body onload="window.print()">


<center>
  

<div class="table-responsive">
  <table border="1" cellpadding="10" style="border-style: solid;">
    <tr>
      <td colspan="1"><center>
        <div>
            <img src="{{ url('') }}/assets/img/jabar.jpg" alt="" style="width: 150px">
        </div></center>
      </td>
      <td colspan="8"><center><h4>JADWAL JAM MENGAJAR</h4><h4>SMK TIK YADIKA CICALENGKA</h4><h4>TAHUN PELAJARAN <span id="tahun"></span>/<span id="nextTahun"></span></h4></center></td>
      <td colspan="1"><center>
        <div>
          <img src="{{ url('') }}/assets/img/logo.png" alt="" style="width: 150px">
        </div></center>
      </td>
      </tr>
    <tr>
      <th scope="col" rowspan="2">Hari</th>
      <th scope="col" rowspan="2">Jam</th>
      <th scope="col" rowspan="2">Waktu</th>
      <th scope="col" colspan="{{ $rooms->count() }}">Kelas</th>
    </tr>
    <tr>
        @foreach($rooms as $room)
        <th>{{ $room->name }}</th>

        @endforeach
    </tr>
    @foreach($days as $day)
    <tr>
        <td rowspan="{{ $times->count() }}">{{ $day->name }}</td>
        <td>1</td>
        <td>{{ $times[0]->range_min }} - {{ $times[0]->range_max }}</td>
        @foreach($rooms as $room)
        @if($results[$room->id]->count())
        <td>{{ ($results[$room->id][0]->teacher_id) }}</td>
        @else       
        <td>-</td>
        @endif
        @endforeach
    </tr>
    @foreach($times as $index=>$time)
    @if($index >0)
    <tr>
        <td>{{ $index+1 }}</td>
        <td>{{ $times[$index]->range_min }} - {{ $times[$index]->range_max }}</td>
        @foreach($rooms as $room)
        @if($results[$room->id]->count())
        <td>{{ ($results[$room->id][$index]->teacher_id) }}</td>
        @else
        <td>-</td>
        @endif
        @endforeach
    </tr>
    @endif
    @endforeach
    {{-- <tr>
        <td></td>
    </tr> --}}
    @endforeach

                 {{-- @foreach ($results as $noah)
                <tr class="">
                          <td>
                            {{ $index +1 }}
                          </td>
                          <td class="py-1">
                            {{ $noah->code_room_id}}
                          </td>
                        
                          <td>
                           {{ $noah->lesson_id  }}
                          </td>
                     
                            <td>
                              {{ $noah->teacher_id}}
                          </td>
                            <td>
                              {{ $noah->day_id}}
                          </td>
                            <td>
                              {{ $noah->time_id}}
                          </td>
                </tr>
                   @endforeach --}}
    </table>
    </div>
</center>
  
</body>
</html>

<script>
  var dt = new Date();
  var year = dt.getFullYear();
  var nextYear = dt.getFullYear() + 1;
  document.getElementById("tahun").innerHTML = year;
  document.getElementById("nextTahun").innerHTML = nextYear;

</script>