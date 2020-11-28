@component('mail::message')
# New apartments found

<table>
    <tr>
        <th>Street</th>
        <th>Price</th>
        <th></th>
    </tr>
    @foreach ($newApartments as $apartment)
        <tr>
            <td>{{ $apartment->getStreet() }}</td>
            <td>{{ $apartment->getPrice() }}</td>
            <td><a href="{{ $apartment->getUrl() }}">Open</a></td>
        </tr>
    @endforeach
</table>

@endcomponent
