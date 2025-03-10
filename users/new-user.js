document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);
  
  // Verifique se existe um ID para determinar se é uma edição ou criação
  const id = urlParams.get('id');

  if (id && !type) {
    $('#div-password').addClass('d-none');
    let cpf = $('input[name="cpf"]').val(urlParams.get('cpf'));

    // Preencher o formulário com os dados recebidos
    $('input[name="id"]').val(urlParams.get('id')); 
    $('input[name="nome"]').val(urlParams.get('nome'));
    $('input[name="user"]').val(urlParams.get('user'));
    $('select[name="userType"]').val(urlParams.get('userType'));
    $('input[name="email"]').val(urlParams.get('email'));
    $('select[name="status"]').val(urlParams.get('status'));
    $('input[name="password"]').removeAttr("required");

    // Carregar a imagem do dependente
    const imagePathBase = `../users/uploads/${cpf.val()}`;
    const imageExtensions = ['.png', '.jpg', '.jpeg']; // Extensões possíveis
    const imgElement = document.getElementById('foto-preview');

    loadImage(imagePathBase, imageExtensions, imgElement);
       
  } else if(!id && type == "navbar"){
    $('#div-password').addClass('d-none');
    $('input[name="id"]').val(id); 
    $('input[name="nome"]').val(nome);
    $('input[name="cpf"]').val(cpf);
    $('input[name="user"]').val(user);
    $('select[name="userType"]').val(tipo);
    $('input[name="email"]').val(email);
    $('select[name="status"]').val(status);


    // Carregar a imagem do dependente
    const imagePathBase = `../users/uploads/${cpf}`;
    const imageExtensions = ['.png', '.jpg', '.jpeg']; // Extensões possíveis
    const imgElement = document.getElementById('foto-preview');

    loadImage(imagePathBase, imageExtensions, imgElement);
    $('input[name="password"]').removeAttr("required");

  } else {
    $('input[name="id"]').val("new"); 
    $('input[name="nome"]').val("");
    $('input[name="user"]').val("");
    $('select[name="userType"]').val("");
    $('input[name="email"]').val("");
    $('select[name="status"]').val("");
    $('input[name="password"]').val("");
    // Configurações para criação de novo sócio
    $('input[name="password"]').removeAttr("required");
  }
  
});

document.getElementById('upload-button').addEventListener('click', function() {
  document.getElementById('foto').click();
});

function previewImage(event) {
  var reader = new FileReader();
  reader.onload = function(){
    var output = document.getElementById('foto-preview');
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
  
}

const cadUsuarioForm = document.getElementById("user-form");

var notyf = new Notyf({
  position: {
    x: 'right',
    y: 'top',
  },
});

cadUsuarioForm.addEventListener("submit", function(e){
  e.preventDefault();
  const dadosForm = new FormData(cadUsuarioForm);
  Swal.fire({
    title: 'Você tem certeza?',
    text: "Deseja salvar os dados para esse usuário?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, salvar!',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
   
    if (result.isConfirmed) {
      try {
        const response = await fetch("./queries/sql_set_users.php", {
          method: "POST",
          body: dadosForm,
        });

        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const dados = await response;

        if (dados.status == 200) {
          var button = document.getElementById("btn-submit");
          button.classList.add("disabled");
          await notyf.success('Dados salvos com sucesso!');
           setTimeout(() => {
            window.location.href = "./users.php";
          }, 2400);
          
        } else {
          notyf.error('Erro!', dados.msg);
        }
      } catch (error) {
        notyf.error('Erro!', 'Ocorreu um erro ao enviar os dados. Tente novamente.', 'error');
      }
    }
  });
});