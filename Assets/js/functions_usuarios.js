var tableUsuarios;
document.addEventListener('DOMContentLoaded', function(){
    tableUsuarios = $('#tableUsuarios').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ningún dato disponible en esta tabla =(",
            sInfo:
              "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sInfoPostFix: "",
            sSearch: "Buscar:",
            sUrl: "",
            sInfoThousands: ",",
            sLoadingRecords: "Cargando...",
            oPaginate: {
              sFirst: "Primero",
              sLast: "Último",
              sNext: "Siguiente",
              sPrevious: "Anterior",
            },
            oAria: {
              sSortAscending:
                ": Activar para ordenar la columna de manera ascendente",
              sSortDescending:
                ": Activar para ordenar la columna de manera descendente",
            },
            buttons: {
              copy: "Copiar",
              colvis: "Visibilidad",
            },
          },
        "ajax":{
            "url": " "+base_url+"/Usuarios/getUsuarios",
            "dataSrc":""
        },
        "columns":[
            {"data":"idpersona"},
            {"data":"nombres"},
            {"data":"apellidos"},
            {"data":"email_user"},
            {"data":"telefono"},
            {"data":"nombrerol"},
            {"data":"status"},
            {"data":"options"}
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 5,
        "order":[[0,"desc"]]  
    });
    
    let formUsuario = document.querySelector("#formUsuario");
    formUsuario.onsubmit = function(e){
        e.preventDefault();
        let strIdentificacion = document.querySelector('#txtIdentificacion').value;
        let strNombre = document.querySelector('#txtNombre').value;
        let strApellido = document.querySelector('#txtApellido').value;
        let strEmail = document.querySelector('#txtEmail').value;
        let intTelefono = document.querySelector('#txtTelefono').value;
        let intTipousuario = document.querySelector('#listRolid').value;

        if(strIdentificacion == '' || strApellido == '' || strNombre == '' 
          || strEmail == '' || intTelefono == '' || intTipousuario == ''){
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Usuarios/setUsuario'; 
        let formData = new FormData(formUsuario);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                var objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormUsuario').modal("hide");
                    formUsuario.reset();
                    swal("Usuarios", objData.msg ,"success");
                    tableUsuarios.api().ajax.reload(function(){
                    });
                }else{
                    swal("Error", objData.msg , "error");
                }
            }
        }
    }
},false);
window.addEventListener('load',function(){
    fntRolesUsuario();
    fntViewUsuario();
}, false);
function fntRolesUsuario(){
    let ajaxUrl = base_url+'/Roles/getSelectRoles';
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() 
                                            : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            document.querySelector('#listRolid').innerHTML = request.responseText;
            document.querySelector('#listRolid').value = 1;
            $('#listRolid').selectpicker('render');
        }
    }
}

function fntViewUsuario(){
    var btnViewUsuario = document.querySelectorAll(".btnViewUsuario");
    btnViewUsuario.forEach(function(btnViewUsuario){
        btnViewUsuario.addEventListener('click', function(){
            var idpersona = this.getAttribute("us");
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() 
                        : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Usuarios/getUsuario/'+idpersona;
            request.open("GET",ajaxUrl,true);
            request.send();
            request.onreadystatechange = function(){
                if(request.status == 200){
                    var objData = JSON.parse(request.responseText);        
                    if(objData.status)
                    {
                       var estadoUsuario = objData.data.status == 1 ? 
                        '<span class="badge badge-success">Activo</span>' : 
                        '<span class="badge badge-danger">Inactivo</span>';        
                        document.querySelector("#celIdentificacion").innerHTML = objData.data.identificacion;
                        document.querySelector("#celNombre").innerHTML = objData.data.nombres;
                        document.querySelector("#celApellido").innerHTML = objData.data.apellidos;
                        document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
                        document.querySelector("#celEmail").innerHTML = objData.data.email_user;
                        document.querySelector("#celTipoUsuario").innerHTML = objData.data.nombrerol;
                        document.querySelector("#celEstado").innerHTML = estadoUsuario;
                        document.querySelector("#celFechaRegistro").innerHTML = objData.data.fechaRegistro; 
                        $('#modalViewUser').modal('show');
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
            }
            $('#modalViewUser').modal('show');
        });
    });
}
/** function fntViewUsuario() {
    document.querySelector('#tableUsuarios').addEventListener('click', function(e) {
        if (e.target && e.target.closest('.btnViewUsuario')) {
            let btn = e.target.closest('.btnViewUsuario');
            let idpersona = btn.getAttribute("us");
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Usuarios/getUsuario/' + idpersona;
            request.open("GET", ajaxUrl, true);
            request.send();

            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    // Aquí puedes manejar la respuesta y llenar el modal con los datos
                    let objData = JSON.parse(request.responseText);
                    if (objData) {
                        document.querySelector('#celIdentificacion').textContent = objData.identificacion;
                        document.querySelector('#celNombre').textContent = objData.nombres;
                        document.querySelector('#celApellido').textContent = objData.apellidos;
                        document.querySelector('#celTelefono').textContent = objData.telefono;
                        document.querySelector('#celEmail').textContent = objData.email_user;
                        document.querySelector('#celTipoUsuario').textContent = objData.nombrerol;
                        document.querySelector('#celEstado').textContent = objData.status == 1 ? 'Activo' : 'Inactivo';
                        document.querySelector('#celFechaRegistro').textContent = objData.fechaRegistro;
                        $('#modalViewUser').modal('show');
                    } else {
                        swal("Error", "No se pudo obtener la información del usuario.", "error");
                    }
                }
            }
        }
    });
} */

function openModal()
{
    document.querySelector('#idUsuario').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
    document.querySelector("#formUsuario").reset();
    $('#modalFormUsuario').modal('show');
}