<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <meta http-equiv="content-script-type" content="text/javascript" />
        <meta charset="utf-8">
        <title>Заголовок сайта</title>


    </head>
    <style>
    .textErr {
        color: Red;
    }
    </style>

    <script>
    $(document).ready(function() {
        var flagNick = false, flagName = true, flagLastname = true, flagEmail = false, flagPass = false;

        function buttonSetVisible() {
            if( flagNick && flagName && flagLastname && flagEmail && flagPass){
                $('#myButton').attr('disabled', false);
            }
            else{
                $('#myButton').attr('disabled', true);
            }
        }

        $('#nickname').on('input', function() {
            flagNick = false;
            if(!($(this).val()).length || (/[\W]/.test(this.value))){           // проверяем, что введены латинские буквы и цифры
                $('#nicknameErr').html("Некорректный никнейм");
                $('#nicknameOk').html("<span style = \"color:Red\">!</span>");
            }
            else{
                var symbol1 = ($(this).val()).substring(0,1);
                if(/[^A-Za-z]/.test(symbol1)){                                  // проверяем первый символ на литиницу
                    $('#nicknameErr').html("Некорректный никнейм");
                    $('#nicknameOk').html("<span style = \"color:Red\">!</span>");
                }
                else{
                    if( dbCheckCollumnValue("nick", this.value) ){              // проверяем на повтор ника в БД
                        $('#nicknameErr').html("Человек с таким никнеймом уже зарегистрирован");
                        $('#nicknameOk').html("<span style = \"color:Red\">!</span>");
                    }else{
                        flagNick = true;
                        $('#nicknameErr').html("") ;
                        $('#nicknameOk').html("<span style = \"color:Green\">\\/</span>");
                    }
                }
            }
            buttonSetVisible();
        });

        $('#name').on('input', function() {
            if(!/[^а-яА-ЯёЁ]/.test(this.value)){
                $('#nameErr').html("");
                $('#nameOk').html("<span style = \"color:Green\">\\/</span>");
                flagName = true;
            }
            else{
                $('#nameErr').html("Разрешены только русские буквы");
                $('#nameOk').html("<span style = \"color:Red\">!</span>");
                flagName = false;
            }
            buttonSetVisible();
        });

        $('#lastname').on('input', function() {
            if(!/[^а-яА-ЯёЁ]/.test(this.value)){
                $('#lastnameErr').html("");
                $('#lastnameOk').html("<span style = \"color:Green\">\\/</span>");
                flagLastname = true;
            }
            else{
                $('#lastnameErr').html("Разрешены только русские буквы");
                $('#lastnameOk').html("<span style = \"color:Red\">!</span>");
                flagLastname = false;
            }
            buttonSetVisible();
        });

        $('#email').on('input', function() {
            flagEmail = false;
            if(/^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i.test(this.value)){
                if( dbCheckCollumnValue("email", this.value) ){              // проверяем на повтор email в БД
                    $('#emailErr').html("На этот ящик уже зарегистрирован аккаунт");
                    $('#emailOk').html("<span style = \"color:Red\">!</span>");
                }else{
                    flagEmail = true;
                    $('#emailErr').html("") ;
                    $('#emailOk').html("<span style = \"color:Green\">\\/</span>");
                }
            }
            else{
                $('#emailErr').html("Некорректный email");
                $('#emailOk').html("<span style = \"color:Red\">!</span>");
            }
            buttonSetVisible();
        });

        $('#password').on('input', function() {
            flagPass = false;
            if(($(this).val()).length<5){
                $('#passwordOk').html("<span style = \"color:Red\">!</span>");
                $('#passwordErr').html("Пожалуйста, выдумайте пароль длиннее 5 символов");
            }
            else{
                $('#passwordOk').html("<span style = \"color:Green\">\\/</span>");
                $('#passwordErr').html("");
                flagPass = true;
            }
            buttonSetVisible();
        });

        function dbCheckCollumnValue(collumn, value){
            console.log('сообщение в консоль 22');
            var res = 2;
            $.ajax({
                type:'get',
                url:'/dbCheckCollumnValue',
                async: false,                           // синхронный режим
                data:{
                    collumn     :collumn,
                    value       :value
                },
                dataType: 'JSON',
                success: function (msg) {
                    if(msg=="0"){
                        res = false;
                    }else{
                        res = true;
                    }
                }
            });
            console.log("dbCheckCollumnValue return "+res);
            return res;
        };
    });

        function handlerButton() {                 // обработка нажатия кнопок
            console.log('сообщение в консоль 1');
            $.ajax({
                type:'get',
                url:'/dbInsertUser',
                data:{
                    nick:       document.getElementById('nickname').value,
                    name:       document.getElementById('name').value,
                    lastname:   document.getElementById('lastname').value,
                    email:      document.getElementById('email').value,
                    password:   document.getElementById('password').value
                },
                dataType: 'JSON',
                success: function (msg) {
                    console.log("handlerButton() success ");
                    console.log(msg);
                    document.location.href = "{{ url('/ok') }}";
                },
                error: function(data, errorThrown)
                {
                    alert('request failed :'+errorThrown);
                }
            });
        };
    </script>

    <body>
        <div align="center">
            <table border="0"  width="800px">
                <tr>
                    <td width="140px" align="left"><b>Регистрация:</b></td>
                    <td width="10px"></td>
                    <td align="left"></td>
                </tr>
                <tr>
                    <td width="140px" align="right">Никнейм:</td>
                    <td width="10px">
                        <span id="nicknameOk"><span style = "color:Red">!</span></span>
                    </td>
                    <td align="left">
                        <input type = "text" id ="nickname" value = "">
                        <span class="textErr"><span id="nicknameErr">Некорректный никнейм</span></span>
                    </td>
                </tr>
                <tr>
                    <td width="140px" align="right">Имя:</td>
                    <td>
                        <span id="nameOk"><span style = "color:Green">\/</span></span>
                    </td>
                    <td align="left">
                        <input type = "text" id ="name" value = "">
                        <span class="textErr"><span id="nameErr"></span></span>
                    </td>
                </tr>
                    <tr width="140px" align="right"><td>Фамилия:</td>
                    <td>
                        <span id="lastnameOk"><span style = "color:Green">\/</span></span>
                    </td>
                    <td align="left">
                        <input type = "text" id ="lastname" value = "">
                        <span class="textErr"><span id="lastnameErr"></span></span>
                    </td>
                </tr>
                <tr>
                    <td width="140px" align="right">Электронная почта:</td>
                    <td>
                        <span id="emailOk"><span style = "color:Red">!</span></span>
                    </td>
                    <td align="left">
                        <input type = "text" id ="email" value = "" size = "15">
                        <span class="textErr"><span id="emailErr">Некорректный email</span></span>
                    </td>
                </tr>
                <tr>
                    <td width="140px" align="right">Пароль:</td>
                    <td>
                        <span id="passwordOk"><span style = "color:Red" >!</span></span>
                    </td>
                    <td align="left">
                        <input type = "text" id ="password" value = "" size = "15">
                        <span class="textErr"><span id="passwordErr">Пожалуйста, выдумайте пароль длиннее 5 символов</span></span>
                    </td>
                    </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td align="left"><input type="button" disabled name="insert" id="myButton" value="Готово" onClick = "handlerButton()"></td>
                </tr>
        </table>
    </body>
</html>
