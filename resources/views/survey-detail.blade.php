<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Detail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">


    <style>
        body {
            background-color: #f6d4d061;
            color: gray;
        }

        .container {
            /* height: 100vh; */
            margin-top: 50px;
            max-width: 100%;
            display: flex;
            /* justify-content: center;
            align-items: center; */
        }

        .loginFormDiv {
            /* height: 50%; */
            width: 100%;
            border-radius: 15px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .loginFormDiv input {
            border: none;
            border-bottom: 1px solid gray;
            display: block;
            width: 100%;
            background-color: white;
            margin-top: 25px;
            padding: 8px;
        }

        .loginFormDiv input:focus,
        .loginFormDiv input:active {
            box-shadow: none;
            outline: none;
            border: none;
            border-bottom: 1px solid gray;
            display: block;
            width: 100%;
            background-color: white;
        }

        .backButton{
            color: white;
            background: #E9B0A6;
            border: none;
            padding: 8px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
        }

    </style>
</head>

<body>
    <section>
        <div class="container">
            <div class="loginFormDiv px-5 py-4">
                <button id="backButton" class="backButton">Back to Users</button>
                <h2 class="text-center">Survey Question</h2>


                <table id="" class="display" style="width:100%; margin-top: 70px;">
                    <thead>
                        <tr>
                            <th class="text-center pr-3">#</th>
                            <th class="text-center">Question</th>
                            <th class="text-center">Answer</th>
                            <th class="text-center">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($survey as $data)
                        <tr>
                            <td class="p-2" style="color: #E9B0A6 ; font-size:18px; font-family: 'Roboto', sans-serif !important; font-weight:400;">{{ $data->user_id }}</td>
                            <td class="p-2" style="color: #E9B0A6 ; font-size:18px; font-family: 'Roboto', sans-serif !important; font-weight:400;">{{$data->question ?? 'n/a'}}</td>
                            <td class="p-2" style="color: #E9B0A6 ; font-size:18px; font-family: 'Roboto', sans-serif !important; font-weight:400;">{{$data->answer ?? 'n/a'}}</td>
                            <td class="p-2" style="color: #E9B0A6 ; font-size:18px; font-family: 'Roboto', sans-serif !important; font-weight:400;">{{$data->email ?? 'n/a'}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').dataTable({
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "bAutoWidth": false
            });
        })

        $('#backButton').click(function() {
            window.history.back();
        })


    </script>
</body>

</html>
