<!DOCTYPE html>
<html>
    <head>
        <title>Ministry of Health | Immunisation Registry</title>
        <style>
            thead {
                background-color: #04AA6D;
            }
        </style>
    </head>
    <body>
        <div>
            <table>
                <tr>
                    <td colspan="4" style="text-align: center; vertical-align: middle;">
                        <div>
                            <img src="/img/android-icon-192x192.png" alt="Coat of Arms" height="76px" width="76px" opacity="1"><br>
                            <h4>
                                Republic of Zambia<br>
                                Ministry of Health <br><br>
                                {{ $certificate->target_disease }} Vaccination Certificate <br>
                                <small>{{ $certificate->created_at }}</small>
                            </h4>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php
                            $rassshireniye = '.png';
                            echo '<img src="/img/qrcodes/' . $certificate->certificate_uuid . '' . $rassshireniye .'" height="156px" width="156px" opacity="1" margin="0" padding="0" />'; 
                        ?>
                    </td>
                    <td colspan="3">
                        <div>
                            <b>Certificate UUID</b> {{ $certificate->certificate_uuid }}<br>
                            <b>Trusted Vaccine Code:</b> {{ $certificate->trusted_vaccine_code }}<br>
                            <br>
                            <b>Last Name:</b> {{ $certificate->client['last_name'] }}<br>
                            <b>First Name:</b> {{ $certificate->client['first_name'] }}<br>
                            <b>Other Names:</b> {{ $certificate->client['other_names'] }}<br>
                            <b>NRC:</b> {{ $certificate->client['NRC'] }}<br>
                            <b>Passport Number:</b> {{ $certificate->client['passport_number'] }}<br>
                            <b>Sex:</b> {{ $certificate->client['sex'] }}<br>
                            <b>Date of Birth:</b> {{ $certificate->client['date_of_birth']->format('d-M-Y') }}
                            <br>
                            <br>
                        </div>
                    </td>
                </tr>
            </table>
            
    
            <div>
                <table font-family="Arial, Helvetica, sans-serif" border-collapse="collapse" width="100%">
                    <thead>
                        <tr>
                            <th><b>Date</b></th>
                            <th><b>Vaccine</b></th>
                            <th><b>Dose Number</b></th>
                            <th><b>Batch Number</b></th>
                            <th><b>Vaccinating Organization</b></th>
                            <th><b>Facility</b></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($certificate->vaccinations as $vaccination)
                        <tr>
                            <td>{{ $vaccination['date']->format('d-M-Y') }}</td>
                            <td>{{ $vaccination['vaccine']->product_name }}</td>
                            <td>{{ $vaccination['dose_number'] }}</td>
                            <td>{{ $vaccination['vaccine_batch_number'] }}</td>
                            <td>{{ $vaccination['vaccinating_organization'] }}</td>
                            <td>{{ $vaccination['facility']->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
