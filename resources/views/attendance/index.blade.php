<!DOCTYPE html>
<html>
<head>
    <title>Attendance Logs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Attendance Logs</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>UID</th>
                <th>Employee ID</th>
                <th>Punch Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
                <tr>
                    <td>{{ $att->id }}</td>
                    <td>{{ $att->uid }}</td>
                    <td>{{ $att->emp_id }}</td>
                    <td>{{ $att->punch_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $attendances->links() }}
</body>
</html>
