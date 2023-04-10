<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Skin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background-color: #f6d4d061;
            color: gray;
        }

        .container {
            height: 100vh;
            max-width: 800px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loginFormDiv {
            height: 50%;
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

        button {
            background-color: #E9B0A6;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 22px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <section>
        <div class="container">
            <div class="loginFormDiv px-5 py-4">
                <h2 class="text-center">LOGIN</h2>
                <form action="{{route('login')}}" method="post" autocomplete="off" id="loginForm">
                    @csrf
                    <div class="px-5 text-center">
                        <input type="email" name="email" placeholder="Enter Email" autocomplete="false" autofocus="off">
                        <input type="password" name="password" placeholder="Enter Password" autocomplete="false" autofocus="off">
                        <button type="submit" data-src="no-validation" class="px-5 py-1 mt-3">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </section>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <script>
        $(document).ready(function() {
            const form = document.getElementById('loginForm');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let url = `{{url('admin/login')}}`;
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                validation = validateForm();
                if (validation) {
                    const formData = new FormData(form);
                    formData.append('_token', csrfToken);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', url);
                    xhr.onreadystatechange = function() {
                        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                            var response = JSON.parse(this.responseText);

                            if (response.success == true) {
                                window.location.href = `{{url('/release')}}`;
                            } else {
                                message = response.message[0][0];
                                swal({
                                    title: "Error",
                                    text: message,
                                    icon: "error",
                                });
                            }
                        }
                    };
                    xhr.send(formData);
                } else {
                    swal({
                        title: "Some Fields Missing",
                        text: "Please fill all fields.",
                        icon: "error",
                    });
                }
            });

            function validateForm() {
                let errorCount = 0;
                $("form#loginForm :input").each(function() {
                    let val = $(this).val();
                    if (val == '' && $(this).attr('data-src') !== 'no-validation') {
                        errorCount++
                        $(this).css('border-bottom', '1px solid red');
                    } else {
                        $(this).css('border-bottom', '1px solid gray');
                    }
                });
                if (errorCount > 0) {
                    return false;
                }
                return true;
            }
        })
    </script>
</body>

</html>