<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CoreUI Multi Select Example</title>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS + jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        #employeeSelect {
            width: 100%;
            /* Làm rộng select */
        }

        #employeeSelect option {
            padding: 10px;/ font-size: 14px;
        }

        .select2-results__options {
            max-height: 400px !important;
        }
    </style>
</head>

<body>
    <form id="addEmpForm"  method="POST" action="{{ route('project.confirm-add-emp', $data['project']) }}">
        @csrf
        <button id="addButton" type="submit" style="margin-bottom: 15px" disabled>Add Employee To Project</button>
        <select id="employeeSelect" name="employees[]" multiple="multiple" class="form-control">
            @foreach ($data['listEmp'] as $emp)
                <option value="{{ $emp->id }}">{{ $emp->full_name }} - Team: {{ $emp->team->name }}</option>
            @endforeach
        </select>
    </form>

    <script>
        $(document).ready(function() {
            $('#employeeSelect').select2({
                placeholder: "Select emp...",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true
            });

            $('#employeeSelect').on('change', function() {
                if ($(this).val().length > 0) {
                    $('#addButton').prop('disabled', false);
                } else {
                    $('#addButton').prop('disabled', true);
                }
            });
        });
    </script>


</body>

</html>
