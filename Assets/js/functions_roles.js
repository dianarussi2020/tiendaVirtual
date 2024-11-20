let tableRoles;
// al momento de cargar html
document.addEventListener("DOMContentLoaded", function () {
  tableRoles = $("#tableRoles").dataTable({
    aProcessing: true,
    aServerSide: true,
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
    ajax: {
      url: " " + base_url + "/Roles/getRoles",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "nombrerol" },
      { data: "descripcion" },
      { data: "status" },
      { data: "options" },
    ],
    resonsieve: "true",
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });

  //NUEVO ROL
  let formRol = document.querySelector("#formRol");
  formRol.onsubmit = function (e) {
    e.preventDefault();
    let intIdRol = document.querySelector("#idRol").value;
    let strNombre = document.querySelector("#txtNombre").value;
    let strDescripcion = document.querySelector("#txtDescripcion").value;
    let intStatus = document.querySelector("#listStatus").value;
    if (strNombre == "" || strDescripcion == "" || intStatus == "") {
      swal("Atención", "Todos los campos son obligatorios.", "error");
      return false;
    }
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    let ajaxUrl = base_url + "/Roles/setRol";
    let formData = new FormData(formRol);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {
        let objData = JSON.parse(request.responseText);
        if (objData.status) {
          $("#modalFormRol").modal("hide");
          formRol.reset();
          swal("Roles de usuario", objData.msg, "success");
          tableRoles.api().ajax.reload(function () {
            fntEditRol();
          });
        } else {
          swal("Error", objData.msg, "error");
        }
      }
    };
  };
});

$("#tableRoles").DataTable();

function openModal() {
  document.querySelector("#idRol").value = "";
  document.querySelector(".modal-header").classList.replace("headerUpdate", "headerRegister");
  document.querySelector("#btnActionForm").classList.replace("btn-info","btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nuevo rol";
  document.querySelector("#formRol").reset();
  $("#modalFormRol").modal("show");
}

window.addEventListener('load', function() {
  fntEditRol();
  fntDelRol();
}, false);

function fntEditRol() {
  setTimeout(() => {
    let btnEditRol = document.querySelectorAll(".btnEditRol");
    btnEditRol.forEach(function (btnEditRol) {
      btnEditRol.addEventListener("click", function () {
        // Cambiar el título y limpiar formulario para edición
        document.querySelector("#titleModal").innerHTML = "Actualizar Rol";
        document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
        document.querySelector("#btnActionForm").classList.replace("btn-primary","btn-info");
        document.querySelector("#btnText").innerHTML = "Actualizar";

        let idRol = this.getAttribute("rl");
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest(): new ActiveXObject("Microsoft.XMLHTTP");
        let ajaxetUser = base_url+'/Roles/getRol/'+idRol;
        request.open("GET",ajaxetUser,true);
        request.send();

        request.onreadystatechange = function(){
          if(request.readyState == 4 && request.status == 200){
            let objData =JSON.parse(request.responseText);
            if(objData.status){
              document.querySelector("#idRol").value = objData.data.id;
              document.querySelector("#txtNombre").value = objData.data.nombrerol;
              document.querySelector("#txtDescripcion").value = objData.data.descripcion;
              if(objData.data.status == 1){
                var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
              }else{
                var optionSelect = '<option value="2" selected class="notBlock">Inactivo</option>';
              }
              let htmlSelect = `${optionSelect}
                              <option value="1">Activo</option>
                              <option value="2">Inactivo</option>
                              `;
              document.querySelector("#listStatus").innerHTML = htmlSelect;
              $('#modalFormRol').modal('show');
            }else{
              swal("Error",objData.msg,"error");
            }
          }
        }
      });
    });
  }, 100); // Espera breve para asegurarte de que DataTables haya cargado
}

function fntDelRol(){
  setTimeout(() => {
    let btnDelRol = document.querySelectorAll(".btnDelRol");
    btnDelRol.forEach(function(btnDelRol) {
      btnDelRol.addEventListener('click', function(){
        let idrol = this.getAttribute("rl");
        swal({
          title: "Eliminar Rol",
          text: "¿Realmente quiere eliminar el Rol?",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, eliminar!",
          cancelButtonText: "No, cancelar!",
          closeOnConfirm: false,
          closeOnCancel: true
        }, function(isConfirm) {
          if (isConfirm) 
          {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Roles/delRol/';
            let strData = "id="+idrol;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
              if(request.readyState == 4 && request.status == 200){
                let objData = JSON.parse(request.responseText);
                  if(objData.status)
                  {
                    swal("Eliminar!", objData.msg , "success");
                    tableRoles.api().ajax.reload(function(){
                      fntEditRol();
                      fntDelRol();
                    });
                  }else{
                    swal("Atención!", objData.msg , "error");
                  }
                }
              }
            }
        }); 
      });
    });
  },100);
}

/*function fntEditRol() {
  document.querySelector("#tableRoles").addEventListener("click", function (e) {
    if (e.target && e.target.closest(".btnEditRol")) {
      let btnEdit = e.target.closest(".btnEditRol");
      let idRol = btnEdit.getAttribute("rl"); // Obtiene el ID del rol
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
      let ajaxUrl = base_url + "/Roles/getRol/" + idRol;
      request.open("GET", ajaxUrl, true);
      request.send();
      request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
          let objData = JSON.parse(request.responseText);
          if (objData.status) {
            document.querySelector("#idRol").value = objData.data.id;
            document.querySelector("#txtNombre").value = objData.data.nombrerol;
            document.querySelector("#txtDescripcion").value =
              objData.data.descripcion;
            document.querySelector("#listStatus").value = objData.data.status;

            // Actualiza el título y el botón
            document.querySelector("#titleModal").innerHTML = "Actualizar Rol";
            document
              .querySelector(".headerRegister")
              .classList.replace("headerRegister", "headerUpdate");
            document.querySelector("#btnText").innerHTML = "Actualizar";
          } 
        }
      };
    }
  });
}*/