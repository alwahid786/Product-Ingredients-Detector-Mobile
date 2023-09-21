<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey</title>
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
            justify-content: center;
            align-items: center;
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
        .showDetailbutton{
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
                <h2 class="text-center">Survey Question</h2>


                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 0; ?>
                        @foreach($surveyUser as $user)

                        <tr>
                            <td class="py-2">{{$count}}</td>
                            <td class="py-2" style="color: #E9B0A6 ; font-size:18px; font-family: 'Roboto', sans-serif !important; font-weight:400;">{{ $user->user->name ?? 'n/a' }}</td>
                            <td class="py-2" style="color: #E9B0A6 ; font-size:18px; font-family: 'Roboto', sans-serif !important; font-weight:400;">{{$user->user->email ?? 'n/a'}}</td>
                            <td class="py-2 text-center">
                                <a href="{{ url('survey-detail/'.$user->user->id) }}">
                                    <button class="showDetailbutton">Show Detail</button>
                                </a>
                            </td>
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
    @if(session()->has('success'))
    <script>
        $(document).ready(function() {
            swal({
                title: "Success",
                text: "Tag removed from restricted list.",
                icon: "success",
            });
        });
    </script>
    @endif
    @if(session()->has('error'))
    <script>
        $(document).ready(function() {
            swal({
                title: "Error",
                text: "Something went wrong, try again.",
                icon: "error",
            });
        });
    </script>
    @endif

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
    </script>
</body>

</html>
