document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);
  
  // Verifique se existe um ID para determinar se é uma edição ou criação
  const id = urlParams.get('id');

  if (id) {
    let cpf = $('input[name="cpf"]').val(urlParams.get('cpf'));
    // Preencher o formulário com os dados recebidos
    $('input[name="id"]').val(urlParams.get('id')); 
    $('input[name="nome"]').val(urlParams.get('nome'));
    // $('input[name="cpf"]').val(urlParams.get('cpf'));
    $('input[name="estado_civil"]').val(urlParams.get('estado_civil'));
    $('input[name="data_nascimento"]').val(urlParams.get('data_nascimento'));
    $('input[name="profissao"]').val(urlParams.get('profissao'));
    $('input[name="titulo"]').val(urlParams.get('titulo'));
    $('input[name="email"]').val(urlParams.get('email'));
    $('input[name="telefone"]').val(urlParams.get('telefone'));
    $('input[name="endereco"]').val(urlParams.get('endereco'));
    $('select[name="status"]').val(urlParams.get('status'));

    // const imagePath = `../users/uploads/${cpf.val()}.jpg`;
    const imagePathBase = `../users/uploads/${cpf.val()}`;
    const imageExtensions = ['.png', '.jpg', '.jpeg']; // Extensões possíveis
    let imagePath = null;

    // Função para verificar se a imagem existe
    function checkImage(extension) {
      return new Promise((resolve, reject) => {
        const img = new Image();
        img.src = `${imagePathBase}${extension}`;
        img.onload = () => resolve(`${imagePathBase}${extension}`); // Retorna o caminho da imagem se carregada com sucesso
        img.onerror = () => reject(); // Rejeita se ocorrer um erro ao carregar a imagem
      });
    }

    (async function loadImage() {
      try {
        for (let ext of imageExtensions) {
          try {
            imagePath = await checkImage(ext); // Tenta carregar a imagem
            $('#foto-preview').attr('src', imagePath); // Se carregada, exibe a imagem
            return; // Sai da função se a imagem for encontrada
          } catch (error) {
            // Caso a imagem não seja encontrada, tenta a próxima extensão
            continue;
          }
        }
        // Se nenhuma das imagens for encontrada, exibe a imagem padrão
        $('#foto-preview').attr('src', '../assets/images/avatar/icon.png');
      } catch (error) {
        // Caso nenhum arquivo seja encontrado, exibe a imagem padrão
        $('#foto-preview').attr('src', '../assets/images/avatar/icon.png');
      }
    })();

  } else {
    // Configurações para criação de novo sócio
    $('input[name="id"]').val("new"); 
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

const cadUsuarioForm = document.getElementById("socio-form");

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
    text: "Deseja salvar este sócio?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, salvar!',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const response = await fetch("./queries/sql_set_socios.php", {
          method: "POST",
          body: dadosForm,
        });

        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const dados = await response;

        if (dados.status) {
           
          await notyf.success('Dados salvos com sucesso!');
          setTimeout(() => {
            window.location.href = "./associate.php";
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