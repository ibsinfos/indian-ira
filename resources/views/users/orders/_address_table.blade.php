<table class="table table-bordered cartTable" style="font-size: 13px;">
    <thead>
        <th class="text-center">Particulars</th>
        <th class="text-center">Billing Address</th>
        <th class="text-center">Shipping Address</th>
    </thead>

    <tbody>
        <tr>
            <th>Full Name:</th>
            <td>{{ $address->full_name }}</td>
            <td>{{ $address->shipping_full_name }}</td>
        </tr>
        <tr>
            <th>Address Line 1:</th>
            <td>{{ $address->address_line_1 }}</td>
            <td>{{ $address->shipping_address_line_1 }}</td>
        </tr>
        <tr>
            <th>Address Line 2:</th>
            <td>{{ $address->address_line_2 }}</td>
            <td>{{ $address->shipping_address_line_2 }}</td>
        </tr>
        <tr>
            <th>Area:</th>
            <td>{{ $address->area }}</td>
            <td>{{ $address->shipping_area }}</td>
        </tr>
        <tr>
            <th>Landmark:</th>
            <td>{{ $address->landmark }}</td>
            <td>{{ $address->shipping_landmark }}</td>
        </tr>
        <tr>
            <th>City:</th>
            <td>{{ $address->city }}</td>
            <td>{{ $address->shipping_city }}</td>
        </tr>
        <tr>
            <th>Pin / Zip / Postal Code:</th>
            <td>{{ $address->pin_code }}</td>
            <td>{{ $address->shipping_pin_code }}</td>
        </tr>
        <tr>
            <th>State:</th>
            <td>{{ $address->state }}</td>
            <td>{{ $address->shipping_state }}</td>
        </tr>
        <tr>
            <th>Country:</th>
            <td>{{ $address->country }}</td>
            <td>{{ $address->shipping_country }}</td>
        </tr>
    </tbody>
</table>
