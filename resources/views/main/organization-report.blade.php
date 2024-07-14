@component('mail::table')
    |ФИО|ИНН|Руководитель|Учредитель| ИП |
    |:--|:--|:---        |:---      |:---|
    @foreach($reportRows as $row)
        | {{$row['name']}} | {{ $row['inn'] }} | {{implode(';', $row['supervisor'])}} | {{implode(';', $row['founder'])}} | {{implode(';', $row['individual'])}} |
    @endforeach
@endcomponent
