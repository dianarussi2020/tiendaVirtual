$('.login-content [data-toggle="flip"]').click(function() {
    $('.login-box').toggleClass('flipped');
    return false;
});

document.addEventListener('DOMContentLoaded',function(){
    //validar si existe el formulario login
    if(document.querySelector("#formLogin")){
        let formLogin = document.querySelector("#formLogin");
        formLogin.onsubmit = function(e){
            e.preventDefault();// previene recargue pagina
            // coge valor identificado con esos id
            let strEmail = document.querySelector('#txtEmail').value;
            let strPassword = document.querySelector('#txtPassword').value;
            //condicional para validar campos vacios
            if(strEmail == "" || strPassword == ""){
                swal("Por favor","Escribe usuario y contraseña.", "error");
                return false;
            }else{
                var request = (window.XMLHttpRequest) ? new XMLHttpRequest() 
                    : new ActiveXObject('Microsoft.XMLHTTP');
                var ajaxUrl = base_url+'/Login/loginUser'; 
                var formData = new FormData(formLogin);
                request.open("POST",ajaxUrl,true);
                request.send(formData);
                
                request.onreadystatechange = function(){
                    if(request.readyState != 4) return;
                    if(request.status == 200){
                        var objData = JSON.parse(request.responseText);
                        if(objData.status){
                            window.location = base_url + '/dashboard';
                        }else{
                            swal("Atención", objData.msg, "error");
                            document.querySelector('#txtPassword').value = "";
                        }
                    }else{
                        swal("Atención","Error en el proceso", "error");
                    }
                    return false;
                }
            }
        }
    }

    if(document.querySelector("#formResetPass")){
        let formResetPass = document.querySelector("#formResetPass");
        formResetPass.onsubmit = function(e){
            e.preventDefault();

            let strEmail = document.querySelector('#txtEmailReset').value;
            if(strEmail == ""){
                swal("Por favor", "Escribe tu correo electrónico", "error");
                return false;
            }else{
                var request = (window.XMLHttpRequest) ? new XMLHttpRequest() 
                    : new ActiveXObject('Microsoft.XMLHTTP');
                var ajaxUrl = base_url+'/Login/resetPass';
                var formData = new FormData(formResetPass);
                request.open("POST",ajaxUrl,true);
                request.send(formData);
                request.onreadystatechange = function(){
                    if(request.readyState != 4) return;
                    if(request.status == 200){
                        var obkData = JSON.parse(request.responseText);
                        if(obkData.status){
                            swal({
                                title: "",
                                text: obkData.msg,
                                type: "success",
                                confirmButtonText: "Aceptar",
                                closeOnConfirm: false,
                            }, function(isConfirm){
                                if(isConfirm){
                                    window.location = base_url;
                                }
                            });
                        }else{
                            swal("Atención", objData.msg, "error");
                        }
                    }else{
                        swal("Atención", "Error en eñ proceso", "error");
                    }
                    return false;
                }
            }
        }
    }

    if(document.querySelector("#formCambiarPass")){
        let formCambiarPass = document.querySelector("#formCambiarPass");
        formCambiarPass.onsubmit = function(e){
            e.preventDefault();
            //obtener los valores del form
            let strPassword = document.querySelector('#txtPassword').value;
            let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;
            let idUsuario = document.querySelector('#idUsuario').value
            //validacion
            if(strPassword == "" || strPasswordConfirm == ""){
                swal("Por favor", "Escribe la nueva contraseña.", "error");
                return false;
            }else{
                if(strPassword.length < 5){
                    swal("Atención", "La contraseña debe tener un minimo de 5 caracteres.", "info");
                    return false;
                }
                if(strPassword != strPasswordConfirm){
                    swal("Atención", "Las contraseñas no son iguales.", "info");
                    return false;
                }
                var request = (window.XMLHttpRequest) ? new XMLHttpRequest() 
                    : new ActiveXObject('Microsoft.XMLHTTP');
                var ajaxUrl = base_url+'/Login/setPassword';
                var formData = new FormData(formCambiarPass);
                request.open("POST",ajaxUrl,true);
                request.send(formData);
                request.onreadystatechange = function(){
                    if(request.readyState != 4) return;
                    if(request.status == 200){
                        var objData = JSON.parse(request.responseText);
                        if(objData.status){
                            swal({
                                title: "",
                                text: objData.msg,
                                type: "success",
                                confirmButtonText: "Iniciar Sesión",
                                closeOnConfirm: false,
                            }, function(isConfirm){
                                if(isConfirm){
                                    window.location = base_url+ '/login';
                                }
                            });
                        }else{
                            swal("Atención", objData.msg, "error");
                        }
                    }else{
                        swal("Atención", "Error en el proceso", "error");
                    }
                }
            }
        }
    }
},false);