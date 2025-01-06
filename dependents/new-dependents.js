document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);

  // Verifique se existe um ID para determinar se é uma edição ou criação
  const id = urlParams.get('id');

  if (id) {
    $('#div-password').addClass('d-none');
    
    // Obtenha os valores da URL
    const cpf = urlParams.get('cpf');
    const nome = urlParams.get('nome');
    const data_nascimento = urlParams.get('data_nascimento');
    const titulo = urlParams.get('titulo');
    const telefone = urlParams.get('telefone');
    const status = urlParams.get('status');
    const titular = urlParams.get('titular_id');

    // Preencher o formulário com os dados recebidos
    $('input[name="id"]').val(id); 
    $('input[name="nome"]').val(nome);
    $('input[name="cpf"]').val(cpf);
    $('input[name="data_nascimento"]').val(data_nascimento);
    $('input[name="telefone"]').val(telefone);
    $('input[name="titulo"]').val(titulo);
    $('select[name="status"]').val(status);

    // Tentativa de carregar a imagem com diferentes extensões
    const imagePathBase = `../dependents/uploads/${cpf}`;
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

    // Tentando carregar a imagem com as extensões possíveis
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
    // Chame a função getAssociates com o valor do titular
    getAssociates(titular);

  } else {
    // Caso contrário, preenche os campos para criação de um novo usuário
    $('input[name="id"]').val("new"); 
    $('input[name="nome"]').val("");
    $('input[name="user"]').val("");
    $('select[name="userType"]').val("");
    $('input[name="email"]').val("");
    $('select[name="status"]').val("");
    $('input[name="password"]').val("");

    // Chame a função getAssociates sem valor selecionado
    getAssociates();
  }
});

document.getElementById('upload-button')?.addEventListener('click', function() {
  document.getElementById('foto').click();
});

function previewImage(event) {
  var reader = new FileReader();
  reader.onload = function() {
    var output = document.getElementById('foto-preview');
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);

}

const depedentsForm = document.getElementById("dependents-form");
var notyf = new Notyf({
  duration: 2000, // Duração da notificação em milissegundos
  position: {
    x: 'right',
    y: 'top',
  },
});

depedentsForm.addEventListener("submit", function(e){
  e.preventDefault();
  const dadosForm = new FormData(depedentsForm);

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
        const response = await fetch("./queries/sql_set_dependents.php", {
          method: "POST",
          body: dadosForm,
        });

        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const dados = await response.json();

        if (dados.status) {
          notyf.success('Dados salvos com sucesso!');
          setTimeout(() => {
            window.location.href = "./dependents.php";
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

const getAssociates = (selectedValue = '') => {
  fetch("./queries/sql_get_select_depedents.php")
    .then(response => response.json())
    .then(data => {
      const select = document.getElementById("titular_id");
      
      if (data.status === false) {
        console.error(data.msg);
        return;
      }
      
      // Limpar o select antes de adicionar novos valores
      select.innerHTML = '';

      // Criar a primeira opção (nenhuma)
      const option = document.createElement("option");
      option.value = '';
      option.textContent = 'Selecione o titular';
      select.appendChild(option);

      // Adicionar as opções dos sócios
      data.forEach(socio => {
        const option = document.createElement("option");
        option.value = socio.id;
        option.textContent = socio.value;
        select.appendChild(option);
      });

      // Definir o valor selecionado, se houver
      if (selectedValue) {
        select.value = selectedValue;
      }
    })
    .catch(error => {
      console.error('Erro ao carregar os sócios:', error);
    });
}