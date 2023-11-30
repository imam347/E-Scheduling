<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="assets/img/logo.png" alt="" style="width: 20%; height: 20%;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
