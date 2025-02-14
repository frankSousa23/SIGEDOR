<table style="width: 100%; margin-top: 15px">
    <tr>
        <td style="width: 30%"><strong>Cédula:</strong></td>
        <td style="width: 70%">{{ $teacher['cdi'] }}</td>
    </tr>
    <tr>
        <td><strong>Nombre Completo:</strong></td>
        <td>{{ $teacher['name'] }} {{ $teacher['surName'] }}</td>
    </tr>
    <tr>
        <td><strong>Sede:</strong></td>
        <td>{{ $relations['sede'] }}</td>
    </tr>
    <tr>
        <td><strong>Área:</strong></td>
        <td>{{ $relations['area'] }}</td>
    </tr>
    <tr>
        <td><strong>Email:</strong></td>
        <td>{{ $teacher['email'] }}</td>
    </tr>
    <tr>
        <td><strong>Teléfono:</strong></td>
        <td>{{ $teacher['phone'] }}</td>
    </tr>
    <tr>
        <td><strong>Fecha Nacimiento:</strong></td>
        <td>{{ $teacher['birthDate'] }}</td>
    </tr>
    <tr>
        <td><strong>Fecha Promoción:</strong></td>
        <td>{{ $teacher['datePromotion'] }}</td>
    </tr>
</table>
